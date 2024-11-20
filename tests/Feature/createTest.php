<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Tagging\Otsus\OpdTagOtsus;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class createTest extends TestCase
{

    use RefreshDatabase;

    public function test_create_opd_tag_otsus(): void
    {
        $this->withoutExceptionHandling();
        $data = [
            "kode_opd" => "1.01.0.00.0.00.01.0000",
            "kode_tema" => "1",
            "kode_program" => "1.1",
            "kode_keluaran" => "1.1.1",
            "kode_aktifitas" => "1.1.1.1",
            "kode_target_aktifitas" => "1.1.1.1.1",
            "sumberdana" => "Otsus 1,25%",
            "volume" => null,
            "catatan" => null,
            "tahun" => 2025,
        ];
        $response = $this->post('/api/rap/opd', $data);

        $response->assertStatus(302);
    }
}
