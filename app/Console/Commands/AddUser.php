<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class AddUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add-user {name} {login} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Registers new user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::where('email', $this->argument('login'))->first();

        if ($user) {
            $this->alert('Такой логин уже существует');

            return Command::FAILURE;
        }

        $user = User::create([
            'email' => $this->argument('login'),
            'name' => $this->argument('name'),
            'password' => bcrypt($this->argument('password')),
        ]);

        $this->info('Пользователь создан');

        return Command::SUCCESS;
    }
}
