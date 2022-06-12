<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\UserPigeonProfile;

class OrderObserver
{
    public function creating(Order $order)
    {
        $order->status = Order::STATUS_INITIAL;
        $pigeon = $order->pigeon->pigeonProfile;
        $pigeon->status = UserPigeonProfile::STATUS_IN_TRANSITS;
        $pigeon->save();
    }
    /**
     * Handle the Order "created" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function created(Order $order)
    {
        //
    }

    /**
     * Handle the Order "updated" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function updated(Order $order)
    {
        if ($order->status == Order::STATUS_FINISHED) {
            $pigeon = $order->pigeon->pigeonProfile;
            $pigeon->status = UserPigeonProfile::STATUS_RESTING;
            $pigeon->available_at = now()->addHours(2);
            $pigeon->save();
        }
    }

    /**
     * Handle the Order "deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function deleted(Order $order)
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function restored(Order $order)
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function forceDeleted(Order $order)
    {
        //
    }
}
