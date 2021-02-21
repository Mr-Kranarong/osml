<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    //view
    public function index()
    {
        return view('admin.statistic.index',[
            // 'profit' => $this->getSalesProfit(),
            // 'views' => $this->getTopView(),
            // 'wishlists' => $this->getTopWishlist(),
            // 'sales' => $this->getTopSold(),
            // 'categories' => $this->getCategoryCount(),
            // 'stocks' => $this->getLowStock()
        ]);
    }

    //function
    public function getSalesProfit(){
        $result = DB::select(
            DB::raw("
                SELECT DISTINCT(purchase_id) as \"DISTINCT\", CONCAT(MONTHNAME(created_at),' ',YEAR(created_at)) as \"Label\", SUM(final_price) as \"Value\"
                FROM purchase_order
                GROUP BY YEAR(created_at), MONTH(created_at)
            ")
        );

        return $this->objectToJSON($result);
    }
    public function getTopView($limit = 10){
        $result = DB::select(
            DB::raw("
                SELECT name as \"Label\", view_count as \"Value\" FROM product order by \"Value\" desc LIMIT $limit
            ")
        );

        return $this->objectToJSON($result);
    }
    public function getTopWishlist($limit = 10){
        $result = DB::select(
            DB::raw("
                SELECT p.name as \"Label\", COUNT(f.user_id) as \"Value\" FROM favorite f LEFT JOIN product p on f.product_id = p.id GROUP BY p.id ORDER BY \"Value\" desc limit $limit
            ")
        );

        return $this->objectToJSON($result);
    }
    public function getTopSold($limit = 10){
        $result = DB::select(
            DB::raw("
                select p.name as \"Label\",sum(po.amount) as \"Value\"
                from purchase_order po left join product p on po.product_id = p.id
                GROUP BY p.id
                ORDER BY \"Value\" desc
                LIMIT $limit
            ")
        );

        return $this->objectToJSON($result);
    }
    public function getCategoryCount(){
        $result = DB::select(
            DB::raw("
                select pc.name as \"Label\", count(p.id) as \"Value\"
                from product p left join product_category pc on p.category_id = pc.id
                GROUP BY pc.id ORDER BY \"Value\" desc
            ")
        );

        return $this->objectToJSON($result);
    }
    public function getLowStock($limit = 10){
        $result = DB::select(
            DB::raw("
                select name as \"Label\", stock_amount as \"Value\" from product order by stock_amount asc limit $limit
            ")
        );

        return $this->objectToJSON($result);
    }

    private function objectToJSON($result)
    {
        $data = array();

        foreach ($result as $row){
            $data[] = $row;
        }

        return json_encode($data);
    }
}
