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

class GenerateImageFromTextJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Dish $dish, protected string $rawRecipe) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->timeout('600')
            ->post('https://neuroimg.art/api/v1/generate', [
                'token' => env('NEUROIMG_TOKEN'),
                'model' => 'HUBG_Flux.1丨BeautifulRealistic-Alpha',
                'prompt' => 'Картинка блюда ' . $this->dish->name . ' по рецепту '.$this->rawRecipe,
                'width' => 1024,
                'height' => 1024,
                'steps' => 30,
                'stream' => false
            ]);


        if ($response->successful() && $response['status'] === 'SUCCESS') {
            $imageUrl = $response['image_url'];
            $imageContents = @file_get_contents($imageUrl);

            if ($imageContents) {
                $fileName = 'dishes/' . uniqid() . '.jpg';
                Storage::disk('public')->put($fileName, $imageContents);
                $this->dish->update(['photo' => url(Storage::url($fileName))]);
            }
        }
    }
}
