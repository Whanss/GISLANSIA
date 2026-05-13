<?php

namespace Database\Factories;

use App\Models\Lansia;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LansiaFactory extends Factory
{
    protected $model = Lansia::class;

    public function definition(): array
    {
        return [
            'nama'          => fake()->name(),
            'nik'           => fake()->unique()->numerify('################'),
            'tanggal_lahir' => fake()->dateTimeBetween('-90 years', '-60 years')->format('Y-m-d'),
            'umur'          => fake()->numberBetween(60, 90),
            'alamat'        => fake()->streetAddress(),
            'desa'          => fake()->city(),
            'kecamatan'     => 'Praya',
            'kabupaten'     => 'Lombok Tengah',
            'provinsi'      => 'Nusa Tenggara Barat',
            'rt'            => fake()->numerify('00#'),
            'rw'            => fake()->numerify('00#'),
            'status'        => 'pending',
            'note'          => null,
            'latitude'      => null,
            'longitude'     => null,
            'pendata'       => fake()->name(),
            'user_id'       => User::factory(),
        ];
    }
}
