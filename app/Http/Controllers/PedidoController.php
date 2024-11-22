<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pedido;
use App\Models\Plato;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pedidos = Pedido::with('platos', 'cliente')->get();
        return response()
            ->json(['pedidos' => $pedidos], 200)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Authorization, Content-Type');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'estado' => 'required|string',
            'precio_total' => 'required|numeric',
            'cliente' => 'required|array',
            'cliente.nombre' => 'required|string',
            'cliente.numero_celular' => 'required|string',
            'cliente.direccion' => 'required|string',
            'platos' => 'required|array',
            'platos.*.id' => 'required|exists:platos,id',
            'platos.*.nombre' => 'required|string',
            'platos.*.precio' => 'required|numeric',
            'platos.*.pivot.cantidad' => 'required|integer',
        ]);

        // Crear o obtener el cliente
        $cliente = Cliente::create([
            'nombre' => $data['cliente']['nombre'],
            'numero_celular' => $data['cliente']['numero_celular'],
            'direccion' => $data['cliente']['direccion']
        ]);

        // Crear el pedido
        $pedido = Pedido::create([
            'estado' => $data['estado'],
            'precio_total' => $data['precio_total'],
            'cliente_id' => $cliente->id
        ]);

        // Asociar platos al pedido
        foreach ($data['platos'] as $plato) {
            $pedido->platos()->attach($plato['id'], ['cantidad' => $plato['pivot']['cantidad']]);
        }

        return response()->json(['success' => true, 'pedido' => $pedido], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pedido $pedido)
    {
        $validated = $request->validate([
            'estado' => 'required|string|in:PENDIENTE,ATENDIDO',
        ]);

        $pedido->estado = $validated['estado'];
        $pedido->save();

        return response()
            ->json([
            'message' => 'Pedido actualizado correctamente.',
            'pedido' => $pedido,
        ], 200)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Authorization, Content-Type');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
