<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeoService
{

    public function __construct()
    {
        $this->googleApiKey = config('google.api_key');
    }

    public function getDistance(
        float $originLatitude,
        float $originLongitude,
        float $destinationLatitude,
        float $destinationLongitude
    ): bool|int {
        $distance = rand(100, 1600);
        if ($distance > 1500) {
            return false;
        }
        return $distance;
        //Not tested with google api key yet, work base on their documentation, can implement later
        $directionGoogleAPI = "https://maps.googleapis.com/maps/api/distancematrix/json";
        $response = Http::get($directionGoogleAPI, [
            'origin' => "$originLatitude,$originLongitude",
            'destination' => "$destinationLatitude,$destinationLongitude",
            'units' => 'metric',
            "key" => $this->googleApiKey
        ]);
        if ($response->status()==200) {
            $distance = 0;
            $route = $response->json();
            foreach ($route->routes->legs as $leg) {
                $distance+= $leg->distance->value;
            }
            return $distance;
        }
        return false;
    }
}
