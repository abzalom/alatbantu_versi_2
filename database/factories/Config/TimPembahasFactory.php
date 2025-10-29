<?php

namespace Database\Factories\Config;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model\Config\TimPembahas>
 */
class TimPembahasFactory extends Factory
{
    protected $model = \App\Models\Config\TimPembahas::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama'    => $this->faker->name(),
            // 18 digit, digit pertama 1-9 (tidak 0), unique
            'nip'     => $this->faker->unique()->regexify('[1-9][0-9]{17}'),
            'jabatan' => $this->faker->randomElement([
                // 'Kepala Bidang Pengendalian',
                // 'Kepala Bidang Infrastruktur',
                // 'Kepala Bidang Pemerintahan',
                // 'Kepala Bidang Litbang',
                // 'Kasubid Perencanaan',
                // 'Kasubid Pengendalian',
                // 'Kasubid Data dan Informasi',
                // 'Kasubid Infrastruktur',
                // 'Kasubid Kwilayahan',
                // 'Kasubid Ekonomi',
                // 'Kasubid Sosial dan Budaya',
                // 'Kasubid Litbang',
                'Staf ASN',
                'Staf Khusus',
                'Staf Ahli',
            ]),
            'tahun' => 2026,
        ];
    }
}
