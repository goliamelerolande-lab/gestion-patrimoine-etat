<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

#[Signature('admin:create')]
#[Description('Create an administrator account interactively')]
class CreateAdminUser extends Command
{
    public function handle(): int
    {
        $name = trim($this->ask('Nom complet de l’administrateur', 'Administrateur'));
        $email = Str::lower(trim($this->ask('Adresse email')));

        if ($name === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Le nom et une adresse email valide sont obligatoires.');

            return self::FAILURE;
        }

        if (User::query()->where('email', $email)->exists()) {
            $this->error('Cette adresse email est déjà utilisée. Aucun compte n’a été modifié.');

            return self::FAILURE;
        }

        $password = (string) $this->secret('Mot de passe (12 caractères minimum)');
        $confirmation = (string) $this->secret('Confirmer le mot de passe');

        if (Str::length($password) < 12 || ! hash_equals($password, $confirmation)) {
            $this->error('Le mot de passe doit contenir au moins 12 caractères et les deux saisies doivent correspondre.');

            return self::FAILURE;
        }

        User::query()->create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'admin',
        ]);

        $this->info("Compte administrateur créé pour {$email}.");

        return self::SUCCESS;
    }
}
