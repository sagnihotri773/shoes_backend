<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;


class AdminDashboardController extends Controller
{
    public function index(){

        $totalOrders = rand(1000, 5000);
        $pendingOrders = rand(50, 500);
        $deliveredOrders = rand(800, 4000);
        $canceledOrders = $totalOrders - $pendingOrders - $deliveredOrders;
        $thisMonthSale = rand(10000, 50000);
        $thisYearProductSale = rand(5000, 50000);
        $totalEarning = rand(100000, 500000);
        $todayPendingEarning = rand(1000, 5000);
        $thisMonthEarning = rand(10000, 50000);
        $thisYearEarning = rand(50000, 200000);
        $totalProducts = rand(50, 200);
        $totalCustomers = rand(500, 2000);

        $response = array(
            "total_orders" => $totalOrders,
            "pending_orders" => $pendingOrders,
            "delivered_orders" => $deliveredOrders,
            "canceled_orders" => $canceledOrders,
            "this_month_sale" => $thisMonthSale,
            "this_year_product_sale" => $thisYearProductSale,
            "total_earning" => $totalEarning,
            "today_pending_earning" => $todayPendingEarning,
            "this_month_earning" => $thisMonthEarning,
            "this_year_earning" => $thisYearEarning,
            "total_products" => $totalProducts,
            "total_customers" => $totalCustomers
        );

        return $response;
    }
}
