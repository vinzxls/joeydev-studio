<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_orders' => Order::count(),
            'active_orders' => Order::whereIn('status', ['menunggu_konfirmasi', 'dikerjakan', 'review_revisi'])->count(),
            'completed_orders' => Order::where('status', 'selesai')->count(),
            'month_orders' => Order::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'month_revenue' => (int) Order::where('payment_status', 'lunas')
                ->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)
                ->sum('final_price'),
            'total_revenue' => (int) Order::where('payment_status', 'lunas')->sum('final_price'),
            'avg_rating' => round((float) Review::approved()->avg('rating'), 2),
            'pending_reviews' => Review::where('is_approved', false)->count(),
        ];

        $recentOrders = Order::with('subject')->latest()->take(8)->get();

        $topSubjects = Order::select('subject_id', DB::raw('COUNT(*) as total'))
            ->groupBy('subject_id')
            ->orderByDesc('total')
            ->with('subject')
            ->take(5)
            ->get();

        // Dihitung di PHP agar netral driver (jalan di MySQL maupun SQLite)
        $monthly = Order::where('created_at', '>=', now()->subMonths(6)->startOfMonth())
            ->pluck('created_at')
            ->groupBy(fn ($date) => $date->format('Y-m'))
            ->map->count()
            ->sortKeys();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'topSubjects', 'monthly'));
    }
}
