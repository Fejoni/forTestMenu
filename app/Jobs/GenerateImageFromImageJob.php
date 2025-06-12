<?php

namespace App\Jobs;

use App\Models\Dish\Dish;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class GenerateImageFromImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     */
    public function __construct(public Dish $dish, public string $imageUrl)
    {
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $image = @file_get_contents($this->imageUrl);
        if (!$image) return;

        $base64 = base64_encode($image);

        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->timeout('600')
            ->post('https://neuroimg.art/api/v1/img2img', [
                'token' => env('NEUROIMG_TOKEN'),
                'model' => 'HUBG_Flux.1丨BeautifulRealistic-Alpha',
                'negative_prompt' => 'Низкое качество',
                'init_image' => $base64,
                'denoising_strength' => 0.6,
                'width' => 1024,
                'height' => 1024,
                'steps' => 30,
                'stream' => false,
            ]);

        if ($response->successful() && $response['status'] === 'SUCCESS') {
            $finalUrl = $response['image_url'] ?? null;
            $img = @file_get_contents($finalUrl);

            if ($img) {
                $fileName = 'dishes/' . uniqid() . '.jpg';
                Storage::disk('public')->put($fileName, $img);
                $this->dish->update(['photo' => url(Storage::url($fileName))]);
            }
        }
    }
}
