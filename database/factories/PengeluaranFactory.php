<?php

namespace Database\Factories;

use App\Models\Pengeluaran;
use Illuminate\Database\Eloquent\Factories\Factory;

class PengeluaranFactory extends Factory
{
    protected $model = Pengeluaran::class;

    public function definition()
    {
        return [
            'indekos_id' => function () {
                return \App\Models\Indekos::factory()->create()->id;
            },
            'nama' => $this->faker->word,
            'jumlah_uang' => $this->faker->numberBetween(10000, 1000000),
            'status' => 'Selesai',
            'tanggal' => now(),
            'jenis' => 'Pengeluaran',
        ];
    }
} 