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
            $dateCondition = "AND DATE(o.created_at) = DATE('".$request->date."')";
        }

        $data = DB::select("
            SELECT  DATE(o.created_at) AS 'order creation date',
             o.reference_code AS 'order id' ,
             CASE WHEN o.status=20 THEN 'Delivered' ELSE 'Canceled' END AS 'status',
             rt.name AS 'region',
             ct.name AS 'city',
             CASE WHEN o.type=1 THEN 'market' ELSE 'food' END AS 'order type',
             CASE WHEN o.is_delivery_by_tiptop=1 THEN 'tiptop' ELSE 'restaurant' END AS 'delivery type',
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
             ,o.user_id,l.latitude,l.longitude");
        $filename = "restaurant-sales.json";
        $handle = fopen($filename, 'w+');
        fputs($handle, json_encode($data));
        fclose($handle);
        $headers = ['Content-type' => 'application/json'];

        return response()->download($filename, 'restaurant-sales.json', $headers);
    }

    public function branchesJsonImport(Request $request)
    {


        $data = DB::select("
            select b.id as 'ID',date(b.created_at) as 'Create Date',bt.title as 'Branch Name',ct.title as 'Branch Name',regions.name as 'City',cities_trans.name as 'Neighborhood',
            case when b.status = 2 then 'Active' else 'Inactive' end as 'Status' , b.longitude as 'Longitude',b.latitude as 'Latitude', GROUP_CONCAT(cat_trans.title SEPARATOR '  |  ') as 'Food Categories', case when b.has_tip_top_delivery = 1 then 'Yes' else 'No' end as 'Tiptop Delivery',b.management_commission_rate as 'Tiptop Delivery Commission Rate', b.minimum_order as 'Tiptop Delivery Minimum order', b.fixed_delivery_fee as 'Tiptop Delivery Fixed Delivery Fee', b.under_minimum_order_delivery_fee as 'Tiptop Under Minimum Order Delivery Fee', b.free_delivery_threshold as 'Tiptop Free Delivery Threshold', b.extra_delivery_fee_per_km as 'Tiptop Extra Delivery Fee Per KM'
            ,case when b.has_restaurant_delivery = 1 then 'Yes' else 'No' end as 'Restaurant Delivery',b.management_commission_rate as 'Restaurant Delivery Commission Rate', b.restaurant_minimum_order as 'Restaurant Delivery Minimum order', b.restaurant_fixed_delivery_fee as 'Restaurant Delivery Fixed Delivery Fee', b.restaurant_under_minimum_order_delivery_fee as 'Restaurant Under Minimum Order Delivery Fee', b.restaurant_free_delivery_threshold as 'Restaurant Free Delivery Threshold', b.restaurant_extra_delivery_fee_per_km as 'Restaurant Extra Delivery Fee Per KM',
            case when b.has_jet_delivery = 1 then 'Yes' else 'No' end as 'Restaurant Delivery',b.jet_delivery_commission_rate as 'Jet Delivery Commission Rate', b.jet_minimum_order as 'Jet Delivery Minimum order', b.jet_fixed_delivery_fee as 'Jet Delivery Fixed Delivery Fee', b.jet_extra_delivery_fee_per_km as 'Jet Extra Delivery Fee Per KM',

            b.rating_count as 'N.Ratings',b.avg_rating as 'AVG Rating'
            from branches b
            join branch_translations bt on bt.branch_id = b.id  and bt.locale = 'en'
            join chain_translations ct on ct.chain_id = b.chain_id  and ct.locale = 'en'
            join cities  on cities.id = b.city_id
            join city_translations cities_trans on cities_trans.city_id = cities.id  and cities_trans.locale = 'en'
            join region_translations regions on regions.region_id = cities.region_id and regions.locale = 'en'
            join category_branch cb on cb.branch_id = b.id
            join taxonomy_translations cat_trans on cat_trans.taxonomy_id = cb.category_id and cat_trans.locale = 'en'
            group by b.id ,b.created_at ,bt.title ,ct.title ,regions.name ,cities_trans.name ,
             b.status  , b.longitude ,b.latitude ,  b.has_tip_top_delivery ,b.management_commission_rate , b.minimum_order , b.fixed_delivery_fee , b.under_minimum_order_delivery_fee , b.free_delivery_threshold , b.extra_delivery_fee_per_km
            , b.has_restaurant_delivery ,b.management_commission_rate , b.restaurant_minimum_order , b.restaurant_fixed_delivery_fee , b.restaurant_under_minimum_order_delivery_fee , b.restaurant_free_delivery_threshold , b.restaurant_extra_delivery_fee_per_km ,
             b.has_jet_delivery ,b.jet_delivery_commission_rate , b.jet_minimum_order , b.jet_fixed_delivery_fee , b.jet_extra_delivery_fee_per_km ,b.rating_count,b.avg_rating
        ");
        $filename = "branches.json";
        $handle = fopen($filename, 'w+');
        fputs($handle, json_encode($data));
        fclose($handle);
        $headers = ['Content-type' => 'application/json'];

        return response()->download($filename, 'branches.json', $headers);
    }

}
