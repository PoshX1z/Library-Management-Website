<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\Purchase;
use App\Models\Contact;
use App\Models\Member;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $total_books = Book::count();
        
        $active_borrows = Borrow::whereIn('status', ['Borrowed', 'Overdue'])->count();
        
        $today_sales = Purchase::whereDate('purchased_at', Carbon::today())->sum('price');
        
        $unread_messages = Contact::where('is_read', false)->count();

        $recent_transactions = Borrow::with(['book', 'member'])
                                     ->orderBy('borrow_date', 'desc')
                                     ->take(5)
                                     ->get();

        $total_members = Member::count();

        return view('dashboard', compact(
            'total_books', 
            'active_borrows', 
            'today_sales', 
            'unread_messages', 
            'recent_transactions',
            'total_members'
        ));
    }
}