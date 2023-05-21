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
    protected $signature = 'create:api-token';

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
        $name = $this->ask('Enter user name:');
        $email = $this->ask('Enter user email:');
        $password = $this->secret('Enter user password:');

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            $this->error('Invalid credentials. Unable to create API token!');
            return;
        }

        $token = $user->createToken($name)->plainTextToken;

        $this->info('API token created successfully!');
        $this->info('Token: '.$token);
    }
}
