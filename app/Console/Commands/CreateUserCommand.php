<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user['name'] = $this->ask('Name of new user');
        $user['email'] = $this->ask('Email of new user');
        $user['password'] = $this->secret('Password of new user');

        $userRole = $this->choice('Role of new user', ['admin', 'editor'], 1);
        $role = Role::where('name', $userRole)->first();
        if (!$role) {
            $this->error('Role not found');

            // generally artisan commands are configured to return 0 as success code so any other number is considered as failed code automatically.
            return -1;
        }

        // validate data, since we don't have access to the global request class, we can't use form requests here
        $validator = Validator::make($user, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'email', 'unique:'.User::class],
            'password' => ['required', Password::defaults()]
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return -1;
        }

        // We use db transaction here which allows that each operation with their dependent operations all goes through or fails together.
        DB::transaction(function () use ($user, $role) {
            $user['password'] = Hash::make($user['password']);
            $newUser = User::create($user);
            // attach the role using many to many relationship
            $newUser->roles()->attach($role->id);
        });

        $this->info('User '.$user['email'].' created successfully');

        // return error message at index 0
        return 0;
    }
}
