<?php

namespace App\Http\Controllers\Zoho;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ZohoAnalyticsController extends Controller
{

    public function dailyOrdersJsonImport(Request $request)
    {

        $dateCondition = ' ';
        if ( ! empty($request->date)) {
            $dateCondition = "AND DATE(o.created_at) = DATE('". $request->date ."')";
        }

        $data = DB::select("
            SELECT  DATE(o.created_at) AS 'order creation date',
             o.reference_code AS 'order id' ,
             CASE WHEN o.status=20 THEN 'Delivered' ELSE 'Canceled' END AS 'status',
             rt.name AS 'region',
             ct.name AS 'city',
             CASE WHEN o.type=1 THEN 'market' ELSE 'food' END AS 'order type',
             CASE WHEN o.is_delivery_by_tiptop=1 THEN 'tiptop' ELSE 'restaurant' END AS 'delivery type',
             bt.branch_id AS 'branch ID',
             bt.title AS 'branch name',
             b.latitude AS 'branch latitude',
             b.longitude AS 'branch longitude',
             o.user_id AS 'customer id',
             l.latitude AS 'customer latitude',
             l.longitude AS 'customer longitude',
             o.coupon_discount_amount,
             CASE WHEN o.grand_total_before_agent_manipulation = 0 THEN o.grand_total ELSE o.grand_total_before_agent_manipulation END AS 'total_before',
             o.grand_total AS 'final_price',
             o.delivery_fee, concat_ws(' ',u.first,u.last) AS 'driver',
             GROUP_CONCAT(ogn.message SEPARATOR '  |  ') AS notes
            FROM orders o
            LEFT JOIN branches b ON o.branch_id = b.id
            LEFT JOIN branch_translations bt ON bt.branch_id = b.id AND bt.locale='en'
            LEFT JOIN users u ON u.id = o.driver_id
            LEFT JOIN cities c ON c.id = o.city_id
            LEFT JOIN city_translations ct ON ct.city_id = c.id AND ct.locale = 'en'
            LEFT JOIN region_translations rt ON rt.region_id = c.region_id AND rt.locale = 'en'
            LEFT JOIN order_agent_notes ogn ON ogn.order_id = o.id
            LEFT JOIN locations l ON l.id = o.address_id
            WHERE (o.customer_notes NOT LIKE '%test%' OR o.customer_notes IS NULL) ".$dateCondition."
            GROUP BY DATE(o.created_at), o.reference_code,bt.title,o.status,o.coupon_discount_amount,total_before,final_price,o.delivery_fee,driver,rt.name,ct.name,o.type,'delivery type',b.latitude,b.longitude
             ,o.user_id,l.latitude,l.longitude,bt.branch_id");
        $filename = "restaurant-sales.json";
        $handle = fopen($filename, 'w+');
        fputs($handle, json_encode($data));
        fclose($handle);
        $headers = ['Content-type' => 'application/json'];

        return response()->download($filename, 'restaurant-sales.json', $headers);
    }

}
