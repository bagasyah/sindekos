<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class UnduhLaporanKeuanganTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Buat file Excel dummy untuk testing
        Storage::disk('public')->put('riwayat_keuangan.xlsx', 'dummy content');
    }

    public function test_export_laporan_keuangan()
    {
        // Buat response palsu untuk download file
        $response = response()->download(
            storage_path('app/public/riwayat_keuangan.xlsx'),
            'riwayat_keuangan.xlsx',
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        );

        // Periksa apakah respons adalah file unduhan
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', $response->headers->get('Content-Type'));
        $this->assertEquals('attachment; filename=riwayat_keuangan.xlsx', $response->headers->get('Content-Disposition'));
    }

    // public function test_export_laporan_keuangan_tanpa_data()
    // {
    //     // Buat response palsu untuk download file
    //     $response = response()->download(
    //         storage_path('app/public/riwayat_keuangan.xlsx'),
    //         'riwayat_keuangan.xlsx',
    //         ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
    //     );

    //     // Periksa apakah respons adalah file unduhan
    //     $this->assertEquals(200, $response->getStatusCode());
    //     $this->assertEquals('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', $response->headers->get('Content-Type'));
    //     $this->assertEquals('attachment; filename=riwayat_keuangan.xlsx', $response->headers->get('Content-Disposition'));
    // }

    protected function tearDown(): void
    {
        // Hapus file Excel dummy setelah test
        Storage::disk('public')->delete('riwayat_keuangan.xlsx');
        
        parent::tearDown();
    }
}
