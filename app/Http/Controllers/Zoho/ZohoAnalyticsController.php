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
            $dateCondition = "AND o.created_at >= STR_TO_DATE('".$request->date."','%Y-%m-%d %H:%i:%s')";
        }

        $data = DB::select("
          SELECT  DATE_FORMAT(o.created_at,'%Y-%m-%d %H:%i:%s') AS 'order creation date',
             TIME_FORMAT(o.created_at, '%H:%i') AS 'order creation time',
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
             TIMESTAMPDIFF(MINUTE, new.created_at, preparing.created_at) as 'new => preparing',
             TIMESTAMPDIFF(MINUTE, preparing.created_at, courier_waiting.created_at) as 'preparing => courier waiting',
             TIMESTAMPDIFF(MINUTE, courier_waiting.created_at, delivering.created_at) as 'courier waiting => on the way',
             TIMESTAMPDIFF(MINUTE, delivering.created_at, arrived.created_at) as 'on the way => at the address',
             TIMESTAMPDIFF(MINUTE, arrived.created_at, delivered.created_at) as 'at the address => delivered',
             TIMESTAMPDIFF(MINUTE, new.created_at, delivered.created_at) as 'total delivery time',
             GROUP_CONCAT(ogn.message SEPARATOR '  |  ') AS notes,
             tt.title AS 'cancellation reason'
            FROM orders o
            LEFT JOIN branches b ON o.branch_id = b.id
            LEFT JOIN branch_translations bt ON bt.branch_id = b.id AND bt.locale='en'
            LEFT JOIN users u ON u.id = o.driver_id
            LEFT JOIN cities c ON c.id = o.city_id
            LEFT JOIN city_translations ct ON ct.city_id = c.id AND ct.locale = 'en'
            LEFT JOIN region_translations rt ON rt.region_id = c.region_id AND rt.locale = 'en'
            LEFT JOIN order_agent_notes ogn ON ogn.order_id = o.id
            LEFT JOIN locations l ON l.id = o.address_id
            LEFT JOIN taxonomies t ON t.id = o.cancellation_reason_id
            LEFT JOIN taxonomy_translations tt ON tt.taxonomy_id = t.id and tt.locale = 'en'
            LEFT JOIN activities new on new.subject_id = o.id and new.type='created_order'
            LEFT JOIN activities preparing on preparing.subject_id = o.id and preparing.type='updated_order' and JSON_EXTRACT(preparing.differences, '$.status') = '10'
            LEFT JOIN activities courier_waiting on courier_waiting.subject_id = o.id and courier_waiting.type='updated_order' and JSON_EXTRACT(courier_waiting.differences, '$.status') = '12'
            LEFT JOIN activities delivering on delivering.subject_id = o.id and delivering.type='updated_order' and JSON_EXTRACT(delivering.differences, '$.status') = 16
            LEFT JOIN activities arrived on arrived.subject_id = o.id and arrived.type='updated_order' and JSON_EXTRACT(arrived.differences, '$.status') = 18
            LEFT JOIN activities delivered on delivered.subject_id = o.id and delivered.type='updated_order' and JSON_EXTRACT(delivered.differences, '$.status') = 20
            WHERE (o.customer_notes NOT LIKE '%test%' OR o.customer_notes IS NULL)  ".$dateCondition."
            GROUP BY o.created_at, o.reference_code,bt.title,o.status,o.coupon_discount_amount,total_before,final_price,o.delivery_fee,driver,rt.name,ct.name,o.type,'delivery type',b.latitude,b.longitude
             ,o.user_id,l.latitude,l.longitude,bt.branch_id,new.created_at,preparing.created_at,courier_waiting.created_at,delivering.created_at,arrived.created_at,delivered.created_at
             ");
        $filename = "restaurant-sales.json";
        $handle = fopen($filename, 'w+');
        fputs($handle, json_encode($data));
        fclose($handle);
        $headers = ['Content-type' => 'application/json'];

        return response()->download($filename, 'restaurant-sales.json', $headers)->deleteFileAfterSend(true);
    }

    public function branchesJsonImport(Request $request)
    {


        $data = DB::select("
            select b.id as 'ID',date(b.created_at) as 'Create Date',bt.title as 'Branch Name',ct.title as 'Chain Name',regions.name as 'City',cities_trans.name as 'Neighborhood',
            case when b.status = 2 then 'Active' else 'Inactive' end as 'Status' , b.longitude as 'Longitude',b.latitude as 'Latitude', GROUP_CONCAT(cat_trans.title SEPARATOR '  |  ') as 'Food Categories', case when b.has_tip_top_delivery = 1 then 'Yes' else 'No' end as 'Tiptop Delivery',b.management_commission_tiptop_rate as 'Tiptop Delivery Commission Rate', b.minimum_order as 'Tiptop Delivery Minimum order', b.fixed_delivery_fee as 'Tiptop Delivery Fixed Delivery Fee', b.under_minimum_order_delivery_fee as 'Tiptop Under Minimum Order Delivery Fee', b.free_delivery_threshold as 'Tiptop Free Delivery Threshold', b.extra_delivery_fee_per_km as 'Tiptop Extra Delivery Fee Per KM'
            ,case when b.has_restaurant_delivery = 1 then 'Yes' else 'No' end as 'Restaurant Delivery',b.management_commission_tiptop_rate as 'Restaurant Delivery Commission Rate', b.restaurant_minimum_order as 'Restaurant Delivery Minimum order', b.restaurant_fixed_delivery_fee as 'Restaurant Delivery Fixed Delivery Fee', b.restaurant_under_minimum_order_delivery_fee as 'Restaurant Under Minimum Order Delivery Fee', b.restaurant_free_delivery_threshold as 'Restaurant Free Delivery Threshold', b.restaurant_extra_delivery_fee_per_km as 'Restaurant Extra Delivery Fee Per KM',
            case when b.has_jet_delivery = 1 then 'Yes' else 'No' end as 'Jet Delivery',b.jet_delivery_commission_rate as 'Jet Delivery Commission Rate', b.jet_minimum_order as 'Jet Delivery Minimum order', b.jet_fixed_delivery_fee as 'Jet Delivery Fixed Delivery Fee', b.jet_extra_delivery_fee_per_km as 'Jet Extra Delivery Fee Per KM',

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
             b.status  , b.longitude ,b.latitude ,  b.has_tip_top_delivery ,b.management_commission_tiptop_rate , b.minimum_order , b.fixed_delivery_fee , b.under_minimum_order_delivery_fee , b.free_delivery_threshold , b.extra_delivery_fee_per_km
            , b.has_restaurant_delivery ,b.management_commission_tiptop_rate , b.restaurant_minimum_order , b.restaurant_fixed_delivery_fee , b.restaurant_under_minimum_order_delivery_fee , b.restaurant_free_delivery_threshold , b.restaurant_extra_delivery_fee_per_km ,
             b.has_jet_delivery ,b.jet_delivery_commission_rate , b.jet_minimum_order , b.jet_fixed_delivery_fee , b.jet_extra_delivery_fee_per_km ,b.rating_count,b.avg_rating
        ");
        $filename = "branches.json";
        $handle = fopen($filename, 'w+');
        fputs($handle, json_encode($data));
        fclose($handle);
        $headers = ['Content-type' => 'application/json'];

        return response()->download($filename, 'branches.json', $headers)->deleteFileAfterSend(true);
    }

    public function searchTermsJsonImport(Request $request)
    {


        $data = DB::select("
        select s.term as 'term',count, locale,branch_id
        from searches s
        ");
        $filename = "search_terms.json";
        $handle = fopen($filename, 'w+');
        fputs($handle, json_encode($data));
        fclose($handle);
        $headers = ['Content-type' => 'application/json'];

        return response()->download($filename, 'search_terms.json', $headers)->deleteFileAfterSend(true);
    }

    public function detailedOrdersJsonImport(Request $request)
    {

        $data = DB::select("
        select o.reference_code, DATE_FORMAT(o.created_at, '%Y-%m-%d %H:%i') AS o_date,case when o.`type` = 1 then 'Grocery' else 'Restaurant' end
        ,o.branch_id,bt.title,pt.product_id,pt.title,p.price,JSON_EXTRACT(cp.product_object,'$.discounted_price'),cp.quantity,(JSON_EXTRACT(cp.product_object,'$.discounted_price') + cp.options_price) * cp.quantity
        from orders o
        left join cart_product cp on cp.cart_id = o.cart_id
        left join products p on p.id = cp.product_id
        left join product_translations pt on pt.product_id = p.id and pt.locale = 'en'
        left join branch_translations bt on bt.branch_id = o.branch_id and bt.locale = 'en'
        where date(o.created_at) >= date('2021-05-23') and o.deleted_at is null and (o.customer_notes NOT LIKE '%test%'
            OR o.customer_notes IS NULL) and o.status = 20
       ");
        $filename = "orders_products_details.json";
        $handle = fopen($filename, 'w+');
        fputs($handle, json_encode($data));
        fclose($handle);
        $headers = ['Content-type' => 'application/json'];

        return response()->download($filename, 'orders_products_details.json', $headers)->deleteFileAfterSend(true);
    }

    public function ordersRatesImport(Request $request)
    {

        $data = DB::select("
          select o.reference_code,o.branch_rating_value
          from orders o
          where o.status = 20 and o.deleted_at is null and (o.customer_notes NOT LIKE '%test%'
              OR o.customer_notes IS NULL) and o.branch_rating_value is not null
       ");
        $filename = "orders_ratings.json";
        $handle = fopen($filename, 'w+');
        fputs($handle, json_encode($data));
        fclose($handle);
        $headers = ['Content-type' => 'application/json'];

        return response()->download($filename, 'orders_ratings.json', $headers)->deleteFileAfterSend(true);
    }

}
