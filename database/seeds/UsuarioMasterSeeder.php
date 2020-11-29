<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuarioMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /** usuario master */
        User::create([
            'name'     => 'SuperAdmin',
            'email'    => 'super@admin.com',
            'password' => Hash::make('123'),
            // 'perfil' => 1,
        ]);
    }
}
