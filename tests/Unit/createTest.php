<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use App\Models\Tagging\Otsus\OpdTagOtsus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class createTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    public function test_example()
    {
        $data = [
            "kode_opd" => "1.01.0.00.0.00.01.0000",
            "kode_tema" => "1",
            "kode_program" => "1.1",
            "kode_keluaran" => "1.1.1",
            "kode_aktifitas" => "1.1.1.1",
            "kode_target_aktifitas" => "1.1.1.1.1",
            "sumberdana" => "Otsus 1,25%",
            "volume" => "32",
            "catatan" => null,
            "tahun" => 2025,
        ];
        // Lakukan operasi create
        $create = OpdTagOtsus::create($data);

        // $this->assertTrue(true, 'Created');

        // Pastikan data berhasil dibuat dan instance yang dikembalikan sesuai
        $this->assertInstanceOf(OpdTagOtsus::class, $create);
        $this->assertEquals('1.01.0.00.0.00.01.0000', $create->kode_opd);
        $this->assertEquals('1', $create->id);

        return $create;
    }
}
