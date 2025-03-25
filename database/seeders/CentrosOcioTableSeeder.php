<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CentroOcio;

class CentrosOcioTableSeeder extends Seeder
{
    public function run(): void
    {
        $centros = [
            [
                'name' => 'Ilusiona Magic World',
                'location' => 'Madrid',
                'address' => 'Calle Gran Vía 32, 28013',
                'email' => 'info@ilusianamagic.es',
                'phone_number' => '911234567'
            ],
            [
                'name' => 'Adventure Park Barcelona',
                'location' => 'Barcelona',
                'address' => 'Avinguda Diagonal 3, 08019',
                'email' => 'contacto@adventurepark.com',
                'phone_number' => '934567890'
            ],
            [
                'name' => 'Diversión Valencia',
                'location' => 'Valencia',
                'address' => 'Calle de Colón 15, 46004',
                'email' => 'info@diversionvalencia.es',
                'phone_number' => '962345678'
            ],
            [
                'name' => 'Fun Zone Sevilla',
                'location' => 'Sevilla',
                'address' => 'Avenida de la Constitución 20, 41004',
                'email' => 'contacto@funzonesevilla.es',
                'phone_number' => '955678901'
            ],
            [
                'name' => 'Mundo Aventura Bilbao',
                'location' => 'Bilbao',
                'address' => 'Gran Vía de Don Diego López de Haro 12, 48001',
                'email' => 'info@mundoaventura.es',
                'phone_number' => '944789012'
            ],
            [
                'name' => 'Family Fun Málaga',
                'location' => 'Málaga',
                'address' => 'Paseo del Parque 4, 29015',
                'email' => 'info@familyfun.es',
                'phone_number' => '952890123'
            ],
            [
                'name' => 'Zona Kids Zaragoza',
                'location' => 'Zaragoza',
                'address' => 'Paseo de la Independencia 24, 50004',
                'email' => 'contacto@zonakids.es',
                'phone_number' => '976901234'
            ],
            [
                'name' => 'Happy Land Alicante',
                'location' => 'Alicante',
                'address' => 'Avenida de Maisonnave 28, 03003',
                'email' => 'info@happyland.es',
                'phone_number' => '965012345'
            ],
            [
                'name' => 'Parque Mágico Murcia',
                'location' => 'Murcia',
                'address' => 'Gran Vía Escultor Salzillo 32, 30004',
                'email' => 'contacto@parquemagico.es',
                'phone_number' => '968123456'
            ],
            [
                'name' => 'Aventura Park Vigo',
                'location' => 'Vigo',
                'address' => 'Calle Príncipe 44, 36202',
                'email' => 'info@aventurapark.es',
                'phone_number' => '986234567'
            ]
        ];

        foreach ($centros as $centro) {
            CentroOcio::create($centro);
        }
    }
}