<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    private const MEXICAN_FIRST_NAMES = [
        'Alejandra', 'Alejandro', 'Ana', 'Andrés', 'Arturo', 'Beatriz', 'Camila',
        'Carlos', 'Cecilia', 'César', 'Claudia', 'Daniela', 'Diego', 'Elena',
        'Emilio', 'Fernanda', 'Fernando', 'Gabriela', 'Gerardo', 'Héctor',
        'Ignacio', 'Isabel', 'Javier', 'Jimena', 'Jorge', 'José', 'Juan',
        'Laura', 'Leticia', 'Lucía', 'Luis', 'Manuel', 'Marco', 'María',
        'Mariana', 'Martín', 'Miguel', 'Natalia', 'Pablo', 'Patricia',
        'Rafael', 'Ramiro', 'Raquel', 'Raúl', 'Ricardo', 'Roberto', 'Rosa',
        'Sergio', 'Silvia', 'Sofía', 'Tomás', 'Valentina', 'Verónica',
        'Victor', 'Ximena',
    ];

    private const MEXICAN_LAST_NAMES = [
        'Álvarez', 'Castillo', 'Chávez', 'Cruz', 'Díaz', 'Fernández', 'Flores',
        'García', 'González', 'Gutiérrez', 'Hernández', 'Jiménez', 'López',
        'Martínez', 'Medina', 'Mendoza', 'Moreno', 'Muñoz', 'Núñez', 'Ortiz',
        'Pérez', 'Ramírez', 'Ramos', 'Reyes', 'Rivera', 'Rodríguez', 'Romero',
        'Ruiz', 'Sánchez', 'Santiago', 'Silva', 'Soto', 'Torres', 'Valdez',
        'Vargas', 'Vázquez',
    ];

    public function definition(): array
    {
        $first = fake()->randomElement(self::MEXICAN_FIRST_NAMES);
        $last = fake()->randomElement(self::MEXICAN_LAST_NAMES);

        return [
            'name' => "{$first} {$last}",
            // Unique email from the name + a short random suffix to avoid collisions across factory calls
            'email' => strtolower(
                preg_replace('/[^a-z]/', '', strtolower($first))
                . '.'
                . preg_replace('/[^a-z]/', '', strtolower($last))
                . fake()->unique()->numberBetween(100, 9999)
            ) . '@example.com',
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => UserRole::CANDIDATE,
            'client_id' => null,
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function candidate(): static
    {
        return $this->state([
            'role' => UserRole::CANDIDATE,
            'client_id' => null,
        ]);
    }

    public function hrAdmin(Client $client): static
    {
        return $this->state([
            'role' => UserRole::HR_ADMIN,
            'client_id' => $client->id,
        ]);
    }

    public function superAdmin(): static
    {
        return $this->state([
            'role' => UserRole::SUPER_ADMIN,
            'client_id' => null,
        ]);
    }
}
