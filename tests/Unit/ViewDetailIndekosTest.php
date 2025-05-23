<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Indekos;
use App\Models\Kamar;
use App\Models\User;


class ViewDetailIndekosTest extends TestCase
{
  

    /** @test */
    public function it_displays_indekos_detail_view()
    {
        // Membuat pengguna admin dan mengautentikasi
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        // Menggunakan data yang sudah ada di database
        $indekos = Indekos::factory()->create([
            'nama' => 'Indekos A',
            'alamat' => 'Jl. Contoh No. 1',
        ]);

        $kamar = Kamar::factory()->create([
            'indekos_id' => $indekos->id,
            'no_kamar' => '101',
            'status' => 'Terisi',
            'harga' => 1000000,
        ]);

        // Mengunjungi route yang menampilkan detail indekos
        $response = $this->get(route('indekos.show', $indekos->id));

        // Memastikan tampilan yang benar ditampilkan
        $response->assertStatus(200);
        $response->assertViewIs('admin.indekos_detail');
        $response->assertSee('Detail Indekos');
        $response->assertSee($indekos->nama);
        $response->assertSee($indekos->alamat);
        $response->assertSee($kamar->no_kamar);
        $response->assertSee($kamar->status);
        $response->assertSee($kamar->harga);
    }

    /** @test */
    //  public function it_displays_no_rooms_message_when_no_rooms_available()
    //  {
    //      // Membuat pengguna admin dan mengautentikasi
    //     $admin = User::factory()->create(['role' => 'admin']);
    //     $this->actingAs($admin);

    //     // Menggunakan data yang sudah ada di database
    //     $indekos = Indekos::factory()->create([
    //         'nama' => 'Indekos B',
    //         'alamat' => 'Jl. Contoh No. 2',
    //     ]);

    //     // Mengunjungi route yang menampilkan detail indekos
    //     $response = $this->get(route('indekos.show', $indekos->id));

    //     // Memastikan tampilan yang benar ditampilkan
    //     $response->assertStatus(200);
    //     $response->assertViewIs('admin.indekos_detail');
    //     $response->assertSee('Data kamar belum tersedia');
    // }
}
