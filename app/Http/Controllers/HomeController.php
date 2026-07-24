<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use App\Models\Subject;

class HomeController extends Controller
{
    public function index()
    {
        $subjects = Subject::active()->get();
        $reviews = Review::approved()->with('order.subject')->latest()->take(9)->get();
        $stats = [
            'completed' => Order::where('status', 'selesai')->count(),
            'avg_rating' => round((float) Review::approved()->avg('rating'), 1),
            'review_count' => Review::approved()->count(),
        ];

        return view('home', compact('subjects', 'reviews', 'stats'));
    }
}
