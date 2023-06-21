<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateApiToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-api-token {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create api token form user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user');
        $user = User::findOrFail($userId);

        $token = $user->createToken("Personal")->plainTextToken;

        $this->info('API token created successfully!');
        $this->info('Token: '.$token);
    }
}
