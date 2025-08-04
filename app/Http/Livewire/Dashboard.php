<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Ticket;
use App\Models\User;
use App\Models\OrderProduct;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public function render()
    {
        // Get current month and previous month for calculations
        $currentMonth = Carbon::now()->startOfMonth();
        $previousMonth = Carbon::now()->subMonth()->startOfMonth();
        
        // 1. Sales Value - Since there's no price column, we'll use order count as a metric
        // You can add a price column to products table later if needed
        $currentMonthOrdersCount = Order::whereMonth('created_at', $currentMonth->month)
            ->whereYear('created_at', $currentMonth->year)
            ->count();
            
        $previousMonthOrdersCount = Order::whereMonth('created_at', $previousMonth->month)
            ->whereYear('created_at', $previousMonth->year)
            ->count();
        
        // For now, we'll use a placeholder value or show order count
        // You can implement actual pricing logic later
        $currentMonthOrdersValue = $currentMonthOrdersCount * 10000; // Placeholder: $10,000 per order
        $previousMonthOrdersValue = $previousMonthOrdersCount * 10000;
        
        $salesGrowth = $previousMonthOrdersValue > 0 ? 
            (($currentMonthOrdersValue - $previousMonthOrdersValue) / $previousMonthOrdersValue) * 100 : 0;
        
        // 2. Customers - Total customers and growth
        $totalCustomers = Customer::count();
        $currentMonthCustomers = Customer::whereMonth('created_at', $currentMonth->month)
            ->whereYear('created_at', $currentMonth->year)
            ->count();
        $previousMonthCustomers = Customer::whereMonth('created_at', $previousMonth->month)
            ->whereYear('created_at', $previousMonth->year)
            ->count();
        
        $customersGrowth = $previousMonthCustomers > 0 ? 
            (($currentMonthCustomers - $previousMonthCustomers) / $previousMonthCustomers) * 100 : 0;
        
        // 3. Revenue (Orders) - Total orders and growth
        $totalOrders = Order::count();
        $currentMonthOrders = Order::whereMonth('created_at', $currentMonth->month)
            ->whereYear('created_at', $currentMonth->year)
            ->count();
        $previousMonthOrders = Order::whereMonth('created_at', $previousMonth->month)
            ->whereYear('created_at', $previousMonth->year)
            ->count();
        
        $ordersGrowth = $previousMonthOrders > 0 ? 
            (($currentMonthOrders - $previousMonthOrders) / $previousMonthOrders) * 100 : 0;
        
        // 4. Bounce Rate (Tickets) - Total tickets and growth
        $totalTickets = Ticket::count();
        $currentMonthTickets = Ticket::whereMonth('created_at', $currentMonth->month)
            ->whereYear('created_at', $currentMonth->year)
            ->count();
        $previousMonthTickets = Ticket::whereMonth('created_at', $previousMonth->month)
            ->whereYear('created_at', $previousMonth->year)
            ->count();
        
        $ticketsGrowth = $previousMonthTickets > 0 ? 
            (($currentMonthTickets - $previousMonthTickets) / $previousMonthTickets) * 100 : 0;
        
        // 5. Recent Tickets (Page visits)
        $recentTickets = Ticket::with(['customer', 'assignedTo', 'orderProduct.product'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // 6. Recent Orders (Total orders section)
        $recentOrders = Order::with(['customer', 'orderProducts.product'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // 7. Team Members
        $teamMembers = User::with('roles')
            ->where('isActive', true)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();
        
        // 8. Order details for Sales Value chart (simplified without price)
        $ordersByMonth = Order::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('created_at', $currentMonth->year)
            ->groupBy('month', 'year')
            ->orderBy('month')
            ->get();
        
        return view('dashboard', compact(
            'currentMonthOrdersValue',
            'salesGrowth',
            'totalCustomers',
            'customersGrowth',
            'totalOrders',
            'ordersGrowth',
            'totalTickets',
            'ticketsGrowth',
            'recentTickets',
            'recentOrders',
            'teamMembers',
            'ordersByMonth'
        ));
    }
}
