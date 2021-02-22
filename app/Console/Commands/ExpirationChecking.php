<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use App\Coupon;
use App\Product_Promotion;
use Carbon\Carbon;

class ExpirationChecking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:expirecheck';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for expiration in coupon and promotion, then delete them if exceed time limit';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = Carbon::now();
        $expired_coupon = Coupon::where('expire_date','<', $today->format('Y-m-d'));
        $expired_promotion = Product_Promotion::where('expire_date','<', $today->format('Y-m-d'));

        $expired_coupon->delete();
        $expired_promotion->delete();

        return $this->info('Operation Completed');
    }
}
