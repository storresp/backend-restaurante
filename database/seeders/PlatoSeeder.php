<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plato;
use Illuminate\Support\Facades\Http;

class PlatoSeeder extends Seeder
{
    public function run()
    {
        // URL de la API
        $apiUrl = 'https://script.google.com/macros/s/AKfycbwZ9JIrW-FLEe43WL84XRcbIb0blzf9y0sy2L8kLvK73OXYJqMBRFUDybr_qF_YMy4_UA/exec';

        // Llamada a la API
        $response = Http::get($apiUrl);

        // Verificar que la respuesta sea exitosa
        if ($response->ok()) {
            $data = $response->json(); // Convertir a un array de PHP

            $platos = $data['data'];

            foreach ($platos as $plato) {
                // Insertar cada plato en la base de datos
                $precio = str_replace(',', '', $plato['precio']);
                $precio = str_replace(' COP', '', $precio);

                // Insertar el plato
                Plato::create([
                    'nombre' => $plato['nombre'],
                    'precio' => (float) $precio, // Asegurarse de que el precio sea un número
                    'tipo' => $plato['tipo'],
                    'descripcion' => $plato['descripcion'],
                    'imagen' => $plato['imagen']
                ]);
                } 
        }else {
            $this->command->error('Error al conectar con la API.');
        }
    
    }
}



