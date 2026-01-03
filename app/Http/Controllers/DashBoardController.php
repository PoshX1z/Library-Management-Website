<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Member;
use App\Models\Borrow;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Fetch Real Stats from Database
        $stats = [
            'total_books' => Book::count(),
            'total_members' => Member::count(),
            'borrowed' => Borrow::where('status', 'Borrowed')->count(),
            'overdue' => Borrow::where('status', 'Overdue')->count(),
        ];

        // 2. Fetch Recent Transactions (Last 5 items)
        // We use 'with' to eagerly load the Member and Book names to avoid 100 queries
        $recent_transactions = Borrow::with(['member', 'book'])
                                     ->orderBy('borrow_date', 'desc')
                                     ->take(5)
                                     ->get();

        return view('dashboard', compact('stats', 'recent_transactions'));
    }
}