<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Indekos;
use App\Models\User;
use App\Models\Kamar;


class ViewDataPenyewaTest extends TestCase
{
 

    public function setUp(): void
    {
        parent::setUp();
        // Mengambil data indekos yang sudah ada
        $this->indekos = Indekos::first(); // Pastikan ada data indekos di database
        
        // Mengambil penyewa dan kamar yang terkait
        $this->penyewa = User::where('role', 'user')->first(); // Pastikan ada penyewa di database
        $this->kamar = Kamar::where('indekos_id', $this->indekos->id)->first(); // Pastikan ada kamar di database
        
        // Mengasosiasikan penyewa dengan kamar jika belum
        if ($this->penyewa && $this->kamar) {
            $this->penyewa->kamar()->associate($this->kamar);
            $this->penyewa->save();
        }
    }

    public function test_view_data_penyewa()
    {
        // Mengautentikasi pengguna sebagai admin
        $this->actingAs(User::where('role', 'admin')->first()); // Mengambil admin yang sudah ada

        // Mengakses rute data penyewa
        $response = $this->get(route('penyewa.index', ['indekosId' => $this->indekos->id]));

        // Memastikan respons berhasil
        $response->assertStatus(200);

        // Memastikan tampilan yang benar digunakan
        $response->assertViewIs('admin.indekos.datapenyewa');

        // Memastikan data penyewa ada di tampilan
        $response->assertSee('Data Penyewa Indekos - ' . $this->indekos->nama);
        $response->assertSee($this->penyewa->name);
        $response->assertSee($this->kamar->no_kamar); // Menggunakan no_kamar dari data yang ada
    }

}
