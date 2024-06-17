<?php

namespace App\Console\Commands\EmailAccount;

use App\Models\EmailAccount;
use Illuminate\Console\Command;

class AddEmailAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email-account:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add Email Account';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $hostname = $this->ask('Email hostname');
        $username = $this->ask('Email username');
        $password = $this->ask('Email password');

        EmailAccount::create([
            'hostname' => $hostname,
            'username' => $username,
            'password' => $password,
        ]);

        $this->info('Email account added');
    }
}
