<?php

namespace Encoda\MultiTenancy\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Encoda\MultiTenancy\Tests\Feature\Models\TenantNotifiable;
use Encoda\MultiTenancy\Tests\TestClasses\User;

class TenantNotifiableFactory extends Factory
{
    protected $model = TenantNotifiable::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'domain' => $this->faker->unique()->domainName,
            'database' => $this->faker->userName,
        ];
    }
}
