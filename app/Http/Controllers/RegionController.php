<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegionController extends Controller
{
    /**
     * GET /ajax/provinces
     * ✅ SEKARANG: semua provinsi se-Indonesia
     */
    public function getProvinces()
    {
        // Ambil semua provinsi dari Laravolt Indonesia
        $all = \Indonesia::allProvinces();

        $provinces = $all
            ->sortBy('name') // urutkan berdasarkan nama biar rapi
            ->map(function ($prov) {
                return [
                    'id'   => $prov->id,   // ini yang dipakai di frontend (value option)
                    'name' => $prov->name,
                ];
            })
            ->values(); // reset index

        return response()->json($provinces);
    }

    /**
     * GET /ajax/cities?province_id=XX
     * Sudah otomatis se-Indonesia (ikut provinsi yang dipilih)
     */
    public function getCities(Request $request)
    {
        $provinceId = $request->get('province_id');

        if (!$provinceId) {
            return response()->json([]);
        }

        // Ambil provinsi + relasi cities dari Laravolt
        $province = \Indonesia::findProvince($provinceId, ['cities']);

        if (!$province) {
            return response()->json([]);
        }

        $cities = $province->cities
            ->sortBy('name')
            ->map(function ($city) {
                return [
                    'id'   => $city->id,
                    'name' => $city->name,
                ];
            })
            ->values();

        return response()->json($cities);
    }

    /**
     * GET /ajax/districts?city_id=YY
     * Juga sudah otomatis se-Indonesia (ikut kota yang dipilih)
     */
    public function getDistricts(Request $request)
    {
        $cityId = $request->get('city_id');

        if (!$cityId) {
            return response()->json([]);
        }

        // Ambil city + relasi districts dari Laravolt
        $city = \Indonesia::findCity($cityId, ['districts']);

        if (!$city) {
            return response()->json([]);
        }

        $districts = $city->districts
            ->sortBy('name')
            ->map(function ($district) {
                return [
                    'id'   => $district->id,
                    'name' => $district->name,
                ];
            })
            ->values();

        return response()->json($districts);
    }
}
