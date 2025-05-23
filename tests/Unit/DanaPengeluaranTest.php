<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Indekos;
use App\Models\Pengeluaran;
use App\Models\User;

class DanaPengeluaranTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        // Menambahkan pengguna admin untuk autentikasi
        $this->actingAs(User::factory()->create(['role' => 'admin']));
    }

    /** @test */
    //  public function can_display_pengeluaran_page()
    // {
    //     $indekos = Indekos::first();
    //     $response = $this->get(route('pengeluaran.index', ['indekosId' => $indekos->id]));

    //     $response->assertStatus(200);
    //     $response->assertViewIs('admin.indekos.pengeluaran');
    //    $response->assertViewHas('indekos', $indekos);
    //  }

    /** @test */
    public function store_pengeluaran()
    {
        $indekos = Indekos::first();

        $data = [
            'nama' => 'Pengeluaran Test',
            'tanggal' => now()->toDateString(),
            'jumlah_uang' => 100000,
        ];

        $response = $this->post(route('pengeluaran.store', $indekos->id), $data);

        $response->assertRedirect(route('pengeluaran.index', $indekos->id));
        $this->assertDatabaseHas('pengeluaran', $data + ['indekos_id' => $indekos->id]);
    }

     /** @test */
    //  public function validates_pengeluaran_data()
    //  {
    //     $indekos = Indekos::first();

    //     $data = [
    //         'nama' => '',
    //         'tanggal' => '',
    //          'jumlah_uang' => '',
    //     ];

    //     $response = $this->post(route('pengeluaran.store', $indekos->id), $data);

    //     $response->assertSessionHasErrors(['nama', 'tanggal', 'jumlah_uang']);
    //  }
}
