<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Rdj\Rajaongkir\Facades\Rajaongkir;

class RajaOngkirController extends Controller
{


    /**
     * get the provinces from RajaOngkir API.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProvinces()
    {
        $getData = Rajaongkir::setEndpoint('province')
            ->setBase(env("RAJAONGKIR_TYPE"))
            ->setQuery([])
            ->get();

        return response()->json($getData['rajaongkir']['results']);
    }
    /**
     * get the cities from RajaOngkir API.
     */
    public function getCities($provinceId)
    {
        $getData = Rajaongkir::setEndpoint('city')
            ->setBase(env("RAJAONGKIR_TYPE"))
            ->setQuery(['province' => $provinceId])
            ->get();
        return response()->json($getData['rajaongkir']['results']);
    }
    /**
     * get the cost from RajaOngkir API.
     */
    public function getCost(Request $request)
    {
        $request->validate([
            'destination' => 'required|integer',
            'weight' => 'required|integer',
            'courier' => 'required|string',
        ]);
        $request->merge([
            'origin' => env('RAJAONGKIR_ORIGIN', 1), // Default origin ID, can be set in .env
        ]);
        // Validate the courier
        $validCouriers = ['jne', 'pos', 'tiki'];
        if (!in_array($request->courier, $validCouriers)) {
            return response()->json([
                'error' => 'Invalid courier specified. Valid options are: ' . implode(', ', $validCouriers),
            ], 400);
        }
        $getData = Rajaongkir::setEndpoint('cost')
            ->setBase(env("RAJAONGKIR_TYPE"))
            ->setBody([
                'origin' => $request->origin,
                'destination' => $request->destination,
                'weight' => $request->weight,
                'courier' => $request->courier,
            ])
            ->post();
        return response()->json($getData['rajaongkir']['results'][0]['costs']);
    }
}
