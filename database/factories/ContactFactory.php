<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "first_name" => null,
            "last_name" => null,
            "email" => null,
            "phone" => null,
            "user_id" => null,
            "is_active" => null,
            "deleted_at" => null,
            "created_at" => null,
            "updated_at" => null,
        ];
    }

    public function firstName(int $number, $value = "SAMPLE-FIRST-NAME-",): Factory
    {
        return $this->state(function (array $attributes) use ($value, $number): array {
            return [
                "first_name" =>  "{$value}{$number}",
            ];
        });
    }

    public function lastName(int $number, $value = "SAMPLE-LAST-NAME-",): Factory
    {
        return $this->state(function (array $attributes) use ($value, $number): array {
            return [
                "last_name" =>  "{$value}{$number}",
            ];
        });
    }

    public function email(int $number = 0, $value = "sampleemail", $domain = "sampledomain", $dot = "com"): Factory
    {
        return $this->state(function (array $attributes) use ($domain, $value, $dot, $number): array {
            return [
                "email" =>  "{$value}{$number}@{$domain}.{$dot}",
            ];
        });
    }

    public function phoneIndonesia(int $first4Number = null, int $middle4Number = null, int $last4Number = null): Factory
    {
        return $this->state(function (array $attributes) use ($first4Number, $middle4Number, $last4Number): array {
            
            if ($first4Number == null) {
                $first4Number = random_int(1000, 9999);
            }

            if ($middle4Number == null) {
                $middle4Number = random_int(1000, 9999);
            }

            if ($last4Number == null) {
                $last4Number = random_int(1000, 9999);
            }

            return [
                "phone" =>  "+62-{$first4Number}-{$middle4Number}-{$last4Number}",
            ];
        });
    }

    public function userId(int $number = 0): Factory
    {
        return $this->state(function (array $attributes) use ($number): array {
            return [
                "user_id" =>  $number,
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
}
