<?php

namespace App\Jobs;

use App\Models\Product\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class GenerateProductImageFromTextJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Product $product)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->product->image) {
            return;
        }

        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->timeout(600)
            ->post('https://neuroimg.art/api/v1/generate', [
                'token' => env('NEUROIMG_TOKEN'),
                'model' => 'HUBG_Flux.1丨BeautifulRealistic-Alpha',
                'prompt' => 'Продукт ' . $this->product->name . ' простой реалистичный',
                'width' => 1024,
                'height' => 1024,
                'steps' => 30,
                'stream' => false
            ]);

        if ($response->successful() && $response['status'] === 'SUCCESS') {
            $imageUrl = $response['image_url'];
            $imageContents = @file_get_contents($imageUrl);

            if ($imageContents) {
                $fileName = 'products/' . uniqid() . '.jpg';
                Storage::disk('public')->put($fileName, $imageContents);
                $this->product->update(['image' => url(Storage::url($fileName))]);
            }
        }
    }
}
