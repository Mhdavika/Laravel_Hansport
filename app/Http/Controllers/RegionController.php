<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegionController extends Controller
{
    /**
     * GET /ajax/provinces
     * Hanya provinsi Pulau Jawa
     */
    public function getProvinces()
    {
        $javaProvinces = [
            'DKI JAKARTA',
            'JAWA BARAT',
            'JAWA TENGAH',
            'DAERAH ISTIMEWA YOGYAKARTA',
            'JAWA TIMUR',
            'BANTEN',
        ];

        // Ambil semua provinsi dari Laravolt, lalu filter
        $all = \Indonesia::allProvinces();

        $provinces = $all
            ->whereIn('name', $javaProvinces)
            ->map(function ($prov) {
                return [
                    'id'   => $prov->id,    // ini yang dipakai di frontend
                    'name' => $prov->name,
                ];
            })
            ->values();

        return response()->json($provinces);
    }

    /**
     * GET /ajax/cities?province_id=XX
     */
    public function getCities(Request $request)
    {
        $provinceId = $request->get('province_id');

        if (!$provinceId) {
            return response()->json([]);
        }

        // Ambil provinsi berdasarkan ID + relasi cities dari Laravolt
        $province = \Indonesia::findProvince($provinceId, ['cities']);

        if (!$province) {
            return response()->json([]);
        }

        $cities = $province->cities
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
