<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Phpml\Association\Apriori;
use App\Purchase_Order;
use App\Settings;


class TrainAprioriModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:trainmodel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Use transaction data to train model and store in server location';

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
        // $result = Purchase_Order::all();

        // $samples = array();
        // $subarray = array();
        // $first = true;
        // $purchaseID = '';
        // foreach ($result as $purchaseLog) {
        //     if ($purchaseLog->purchase_id != $purchaseID && $first == false) {
        //         $purchaseID = $purchaseLog->purchase_id;
        //         array_push($samples, $subarray);
        //         $subarray = array();
        //     } else {
        //         $purchaseID = $purchaseLog->purchase_id;
        //         $first = false;
        //     }
        //     array_push($subarray, $purchaseLog->product_id);
        // }

        // $labels  = [];

        // $support = (float) Settings::firstWhere('option','apriori_support')->value('value');
        // $confidence = (float) Settings::firstWhere('option','apriori_confidence')->value('value');

        // $reg = new Apriori($support, $confidence);
        // $reg->train($samples, $labels);
        // $res = $reg->predict([3]);
        //$re = json_encode($res);

        return $this->info('Success');
    }
}
