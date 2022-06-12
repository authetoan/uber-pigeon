<?php
namespace App\Services;

use App\Models\Order;
use App\Models\UserPigeonProfile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class PigeonService
{

    private GeoService $geoService;

    public function __construct()
    {
        $this->geoService = new GeoService();
    }

    public function calculateFee(
        UserPigeonProfile $pigeonProfile,
        int $distance
    ): bool|int {
        if ($distance > $pigeonProfile ->range) {
            return false;
        }
        return $pigeonProfile->cost * $distance;
    }

    public function findSuitablePigeon(
        Carbon $deadline,
        float $destinationLatitude = null,
        float $destinationLongitude = null
    ): UserPigeonProfile|bool|null {
        //Can be improve, also need to check with `rest` / `available_at` factor later
        $pigeons = UserPigeonProfile::where(['status' => UserPigeonProfile::STATUS_AVAILABLE])->get();
        $availablePigeons= collect();

        foreach ($pigeons as $pigeon) {
                $distance = $this->geoService->getDistance(
                    originLatitude: $pigeon->latitude,
                    originLongitude: $pigeon->longitude,
                    destinationLatitude: $destinationLatitude,
                    destinationLongitude: $destinationLongitude
                );
            if ($distance) {
                $deliverTime = $distance / $pigeon->speed;
                if ($deliverTime < now()->diffInHours($deadline) && $distance<$pigeon->range) {
                    $pigeon->deliverTime = $deliverTime;
                    $pigeon->distance = $distance;
                    $pigeon->fee = $this->calculateFee(pigeonProfile: $pigeon, distance: $distance);
                    $availablePigeons->push($pigeon);
                }
            }
        }
        return $availablePigeons->sortBy('deliverTime')->first();
    }

    public function order($customer, $pigeon, $destination_latitude, $destination_longitude): Order
    {
        $order = new Order();
        $order->distance = $pigeon->distance;
        $order->fee = $pigeon->fee;
        $order->destination_latitude = $destination_latitude;
        $order->destination_longitude = $destination_longitude;
        $order->user_customer_id = $customer->id;
        $order->user_pigeon_id = $pigeon->user->id;
        $order->save();
        return $order;
    }
}
