<?php

namespace App\Console\Commands;

use App\Models\SuccessfulEmail;
use App\Services\EmailService;
use Illuminate\Console\Command;

class ParseEmailsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prase email contents from html to text';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $service = app(EmailService::class);

        $emails = SuccessfulEmail::where('raw_text', '=', '')->get();

        foreach ($emails as $email) {
            $result = $service->parseEmailContent($email->email);

            $email->update(['raw_text' => $result]);
        }
    }
}
