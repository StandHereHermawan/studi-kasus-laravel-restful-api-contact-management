<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Ramsey\Uuid\Rfc4122\UuidV7;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "username" =>   null,
            "password" =>   null,
            "name" =>       null,
            "token" =>      null,
            "is_active" =>  null,
            "deleted_at" => null,
            "created_at" => null,
            "updated_at" => null,
        ];
    }
    public function name(int $number, $value = "SAMPLE-NAME-",): Factory
    {
        return $this->state(function (array $attributes) use ($value, $number): array {
            return [
                "name" =>  "{$value}{$number}",
            ];
        });
    }

    public function username(int $number, $value = "SAMPLE-USERNAME-",): Factory
    {
        return $this->state(function (array $attributes) use ($value, $number): array {
            return [
                "username" =>  "{$value}{$number}",
            ];
        });
    }

    public function password($password = "rahasia"): Factory
    {
        return $this->state(function (array $attributes) use ($password): array {
            return [
                "password" => bcrypt($password),
            ];
        });
    }

    public function isActive(): Factory
    {
        return $this->state(function (array $attributes): array {
            return [
                "is_active" => true,
            ];
        });
    }

    public function newRecord(): Factory
    {
        return $this->state(function (array $attributes): array {
            return [
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ];
        });
    }

    public function softDeleted(): Factory
    {
        return $this->state(function (array $attributes): array {
            return [
                "deleted_at" => Carbon::now(),
            ];
        });
    }

    public function activeToken(): Factory
    {
        return $this->state(function (array $attributes): array {
            return [
                "token" => UuidV7::uuid7(Carbon::now()),
            ];
        });
    }
}
