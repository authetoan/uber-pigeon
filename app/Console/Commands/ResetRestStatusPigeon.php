<?php

namespace App\Console\Commands;

use App\Models\UserPigeonProfile;
use Illuminate\Console\Command;

class ResetRestStatusPigeon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'uber-pigeon:reset-rest-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset Pigeon Rest Status';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        UserPigeonProfile::where('status', UserPigeonProfile::STATUS_RESTING)
            ->where('available_at', '<', now())
            ->update(['status' => UserPigeonProfile::STATUS_AVAILABLE]);
    }
}
