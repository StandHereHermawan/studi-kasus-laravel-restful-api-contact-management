<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "street" => null,
            "city" => null,
            "province" => null,
            "country" => null,
            "postal_code" => null,
            "contact_id" => null,
            "is_active" => null,
            "deleted_at" => null,
            "created_at" => null,
            "updated_at" => null,
        ];
    }

    public function street(int $number, $value = "SAMPLE-STREET-",): Factory
    {
        return $this->state(function (array $attributes) use ($value, $number): array {
            return [
                "street" =>  "{$value}{$number}",
            ];
        });
    }

    public function city(int $number, $value = "SAMPLE-CITY-",): Factory
    {
        return $this->state(function (array $attributes) use ($value, $number): array {
            return [
                "city" =>  "{$value}{$number}",
            ];
        });
    }

    public function province(int $number, $value = "SAMPLE-PROVINCE-",): Factory
    {
        return $this->state(function (array $attributes) use ($value, $number): array {
            return [
                "province" =>  "{$value}{$number}",
            ];
        });
    }

    public function country(int $number, $value = "SAMPLE-COUNTRY-",): Factory
    {
        return $this->state(function (array $attributes) use ($value, $number): array {
            return [
                "country" =>  "{$value}{$number}",
            ];
        });
    }

    public function postalCode(int $number = null, $value = null,): Factory
    {
        return $this->state(function (array $attributes) use ($value, $number): array {

            if ($value == null) {
                $value = random_int(100, 999);
            }

            if ($number == null) {
                $number = random_int(0, 9);
            }

            return [
                "postal_code" =>  "4{$number}{$value}",
            ];
        });
    }

    public function contactId(int $number = 0): Factory
    {
        return $this->state(function (array $attributes) use ($number): array {
            return [
                "contact_id" =>  $number,
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

    public function isNotActive(): Factory
    {
        return $this->state(function (array $attributes): array {
            return [
                "is_active" => false,
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
}
