<?php

namespace Database\Factories;

use App\Models\AdminStoreAccount;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminStoreAccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AdminStoreAccount::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'account'  => 'admin',
            'name'     => 'admin',
            'password' => '123qwe'
        ];
    }
}
