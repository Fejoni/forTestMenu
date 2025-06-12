<?php

namespace App\Jobs;



use App\Models\Dish\Dish;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class GenerateRecipeTextJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Dish $dish, protected string $rawRecipe)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('IO_NET_TOKEN'),
            'Content-Type' => 'application/json',
        ])->post(env('IO_NET_API'), [
            'model' => env('IO_NET_AI_MODEL'),
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Перефразируй кулинарный рецепт, сохранив смысл, порядок шагов и ингредиенты. Используй другие слова, но не сокращай и не дополняй текст. Не добавляй вступления, заключений или рекомендаций. Не используй списки, нумерацию, Markdown, заголовки и символы для выделения. Верни только чистый непрерывный текст с шагами рецепта.',
                ],
                [
                    'role' => 'user',
                    'content' => $this->rawRecipe,
                ],
            ],
        ]);

        $content = $response['choices'][0]['message']['content'] ?? '';
        $parts = explode('</think>', $content, 2);
        $cleanedRecipe = preg_replace('/\s+/', ' ', trim($parts[1] ?? ''));

        if ($cleanedRecipe) {
            $this->dish->update([
                'recipe' => $cleanedRecipe,
            ]);
        }
    }
}
