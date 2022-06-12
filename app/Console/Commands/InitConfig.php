<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class InitConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'uber-pigeon:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Init app';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user = new User();
        $user->name = "Admin";
        $user->email = "admin@admin.com";
        $user->password = Hash::make("123456");
        $user->created_at = now();
        $user->updated_at = now();
        $adminRole = Role::create(['name' => 'admin']);
        $user->assignRole($adminRole);
        $user->save();
        Role::create(['name' => 'pigeon']);
        Role::create(['name' => 'customer']);
    }
}
