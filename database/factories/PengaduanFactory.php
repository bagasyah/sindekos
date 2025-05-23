<?php

namespace Database\Factories;

use App\Models\Pengaduan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pengaduan>
 */
class PengaduanFactory extends Factory
{
    protected $model = Pengaduan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'tanggal_pelaporan' => $this->faker->date(),
            'masalah' => $this->faker->sentence(),
            'status' => 'Pending',
            'foto' => $this->faker->image('storage/app/public/pengaduan', 640, 480, null, false),
            'foto_akhir' => null,
        ];
    }
}
