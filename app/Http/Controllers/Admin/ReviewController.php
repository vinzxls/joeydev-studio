<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        return view('admin.reviews.index', [
            'reviews' => Review::with('order.subject')->latest()->paginate(20),
        ]);
    }

    /** Toggle tampil/sembunyikan. Isi review tidak pernah diedit demi kredibilitas. */
    public function toggle(Review $review)
    {
        $review->update(['is_approved' => ! $review->is_approved]);

        return back()->with('status', $review->is_approved
            ? 'Review ditampilkan di halaman publik.'
            : 'Review disembunyikan dari halaman publik.');
    }
}
