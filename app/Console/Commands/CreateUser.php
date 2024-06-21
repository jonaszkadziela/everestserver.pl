<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\AccountCreatedViaCommand;
use Illuminate\Auth\Events\Registered;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'everestserver:create-user {username?} {email?} {isAdmin?} {isEnabled?} {language?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create user on EverestServer';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $data = [
            'username' => $this->argument('username') ?? $this->ask('What is the username?'),
            'email' => $this->argument('email') ?? $this->ask('What is the email address?'),
            'password' => $this->secret('What is the password?'),
            'isAdmin' => $this->argument('isAdmin') ?? $this->choice('Should the user be admin?', ['No', 'Yes'], 0),
            'isEnabled' => $this->argument('isEnabled') ?? $this->choice('Should the user be enabled?', ['No', 'Yes'], 1),
            'language' => $this->argument('language') ?? $this->choice('What is the preferred language?', ['en', 'pl'], 0),
        ];

        if ($data['password'] === null) {
            $data['password'] = Str::password(16);

            $this->warn('The password was not provided, so a secure password was generated automatically');
            $this->newLine();
        }

        $data['isAdmin'] = filter_var($data['isAdmin'], FILTER_VALIDATE_BOOLEAN);
        $data['isEnabled'] = filter_var($data['isEnabled'], FILTER_VALIDATE_BOOLEAN);

        try {
            $data = Validator::validate($data, [
                'username' => 'required|string|lowercase|alpha_dash:ascii|min:3|max:20|unique:' . User::class,
                'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
                'password' => 'required',
                'isAdmin' => 'required|boolean',
                'isEnabled' => 'required|boolean',
                'language' => 'required|in:' . join(',', config('app.languages')),
            ]);
        } catch (ValidationException $e) {
            $this->error($e->getMessage());

            return;
        }

        $user = new User([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        $user->is_admin = $data['isAdmin'];
        $user->is_enabled = $data['isEnabled'];

        $user->save();

        Lang::setLocale($data['language']);

        $user->notify(new AccountCreatedViaCommand($user->email, $data['password']));

        event(new Registered($user));

        $this->info('The "' . $data['username'] . '" user has been created successfully!');
    }
}
