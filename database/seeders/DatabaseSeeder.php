<?php

namespace Database\Seeders;

use App\Models\Estado;
use App\Models\TipoArticulo;
use App\Models\UnidadMedida;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Estado::create(['nombre' => 'Activo']);
        Estado::create(['nombre' => 'Inactivo']);

        $unidades = [
            // Peso
            ['nombre' => 'Kilogramo',    'abreviatura' => 'kg'],
            ['nombre' => 'Gramo',        'abreviatura' => 'g'],
            ['nombre' => 'Miligramo',    'abreviatura' => 'mg'],
            ['nombre' => 'Tonelada',     'abreviatura' => 't'],
            ['nombre' => 'Libra',        'abreviatura' => 'lb'],
            ['nombre' => 'Onza',         'abreviatura' => 'oz'],
            // Volumen
            ['nombre' => 'Litro',        'abreviatura' => 'l'],
            ['nombre' => 'Mililitro',    'abreviatura' => 'ml'],
            ['nombre' => 'Centilitro',   'abreviatura' => 'cl'],
            ['nombre' => 'Metro cúbico', 'abreviatura' => 'm³'],
            // Longitud
            ['nombre' => 'Metro',        'abreviatura' => 'm'],
            ['nombre' => 'Centímetro',   'abreviatura' => 'cm'],
            ['nombre' => 'Milímetro',    'abreviatura' => 'mm'],
            ['nombre' => 'Kilómetro',    'abreviatura' => 'km'],
            // Superficie
            ['nombre' => 'Metro cuadrado',      'abreviatura' => 'm²'],
            ['nombre' => 'Centímetro cuadrado', 'abreviatura' => 'cm²'],
            // Comerciales
            ['nombre' => 'Unidad',   'abreviatura' => 'u'],
            ['nombre' => 'Docena',   'abreviatura' => 'doc'],
            ['nombre' => 'Par',      'abreviatura' => 'par'],
            ['nombre' => 'Caja',     'abreviatura' => 'caja'],
            ['nombre' => 'Paquete',  'abreviatura' => 'paq'],
            ['nombre' => 'Bolsa',    'abreviatura' => 'bolsa'],
            ['nombre' => 'Rollo',    'abreviatura' => 'rollo'],
            ['nombre' => 'Atado',    'abreviatura' => 'atado'],
            ['nombre' => 'Bandeja',  'abreviatura' => 'bandeja'],
        ];

        foreach ($unidades as $unidad) {
            UnidadMedida::create($unidad);
        }

        TipoArticulo::create(['nombre' => 'Compra/Venta']);
        TipoArticulo::create(['nombre' => 'Produccion']);
        TipoArticulo::create(['nombre' => 'Servicio']);

        User::create([
            'nick' => 'admin',
            'password' => 'root',
        ]);

        User::create([
            'nick' => 'invitado',
            'password' => 'invitado',
        ]);
    }
}
