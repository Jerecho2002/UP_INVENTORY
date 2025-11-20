<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RoomApiService
{
    public function fetchRooms()
    {
        $apiUrl = env('SYSTEM_B_API_URL') . '/rooms';
        $token = env('SYSTEM_B_API_TOKEN');

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json',
        ])->get($apiUrl);

        return $response->successful()
            ? $response->json()
            : [];
    }
}
