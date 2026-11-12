<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateSuperAdmin extends Command
{
    protected $signature = 'create:superadmin';
    protected $description = 'Crea el único usuario SUPER_ADMIN de Harmony Spa';

    public function handle()
    {
        if (User::where('role', 'SUPER_ADMIN')->exists()) {
            $this->error('Ya existe un SUPER_ADMIN en el sistema.');
            return;
        }

        $firstName = $this->ask('Nombres');
        $lastName = $this->ask('Apellidos');
        $email = strtolower($this->ask('Correo electrónico'));
        $password = $this->secret('Contraseña (mínimo 12 caracteres)');
        $confirmPassword = $this->secret('Confirma la contraseña');

        $validator = Validator::make([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'password' => $password,
        ], [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email|max:254',
            'password' => 'required|min:12',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return;
        }

        if ($password !== $confirmPassword) {
            $this->error('Las contraseñas no coinciden.');
            return;
        }

        try {
            $superAdmin = User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'SUPER_ADMIN',
                'is_active' => true,
            ]);

            $this->info('¡SUPER_ADMIN creado correctamente!');
            $this->info("ID: {$superAdmin->id} | Correo: {$superAdmin->email}");
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}