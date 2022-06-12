<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\UserPigeonProfile;
use App\Services\PigeonService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PigeonController extends Controller
{
    public function updatePigeonStatus(Request $request): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:'.UserPigeonProfile::STATUS_AVAILABLE.','.UserPigeonProfile::STATUS_OFFLINE,
        ]);
        $pigeonProfile = $request->user()->pigeonProfile()->first();
        $pigeonProfile->status = $request->status;
        $pigeonProfile->save();
        return response()->json(['Message' => 'Success'], 200);
    }

    public function updateCurrentLocation(Request $request): JsonResponse
    {
        $request->validate([
            'latitude' => 'required|float',
            'longitude' => 'required|float'
        ]);
        $pigeonProfile = $request->user()->pigeonProfile()->first();
        $pigeonProfile->status = $request->status;
        $pigeonProfile->save();
        return response()->json(['Message' => 'Success']);
    }

    public function order(Request $request): JsonResponse
    {
        $request->validate([
            'destination_latitude' => 'numeric',
            'destination_longitude' => 'numeric',
            'deadline' => 'date'
        ]);
        //Ill assume the system will detect with pigeon suitable for customer,
        //then the customer need to go to pigeon location to send the package ;
        $pigeonService = new PigeonService();
        $pigeon = $pigeonService->findSuitablePigeon(
            deadline: Carbon::create($request->deadline),
            destinationLatitude: $request->destination_latitude,
            destinationLongitude: $request->destination_longitude,
        );
        if (!$pigeon) {
            return response()->json(['message' => 'No pigeon available'], 500);
        }
        $order = $pigeonService->order(
            customer: $request->user(),
            pigeon: $pigeon,
            destination_latitude: $request->destination_latitude,
            destination_longitude: $request->destination_longitude
        );
        return response()->json(['message' => 'Success','order' => $order->load('pigeon', 'customer')]);
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:'.Order::STATUS_IN_PROGRESS.','.Order::STATUS_FINISHED,
        ]);
        if ($request->user()->id != $order->pigeon->id || $order->status == Order::STATUS_FINISHED) {
            return response()->json(['message' => 'Invalid request'], 400);
        }
        $order->status = $request->status;
        $order->save();
        return response()->json(['message' => 'Success','order' => $order->load('pigeon', 'customer')]);
    }
}
