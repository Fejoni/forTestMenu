<?php

namespace App\Console\Commands;

use App\Jobs\GenerateImageFromTextJob;
use App\Jobs\GenerateRecipeTextJob;
use App\Mail\UserRegisteredMail;
use App\Models\Dish\Dish;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 't';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Mail::to('egor3k3@mail.ru')->send(new UserRegisteredMail('egor3k3@mail.ru'));
    }
}
