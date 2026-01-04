<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrow;
use App\Models\Book;
use App\Models\Member;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Borrow::with(['member', 'book']);

        if ($request->has('filter') && $request->filter == 'all') {
            $query->orderBy('borrow_date', 'desc');
        } else {
            $query->whereIn('status', ['Borrowed', 'Overdue'])
                  ->orderBy('borrow_date', 'asc');
        }
        $transactions = $query->get();

        $members = Member::where('status', 'Active')->get();
        $available_books = Book::where('status', 'Available')->orderBy('title')->get();

        return view('transactions.index', compact('transactions', 'members', 'available_books'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required',
            'book_id' => 'required',
            'due_date' => 'required|date|after:today',
        ]);

        $borrow = new Borrow();
        $borrow->member_id = $request->member_id;
        $borrow->book_id = $request->book_id;
        $borrow->staff_id = 1;
        $borrow->borrow_date = Carbon::now();
        $borrow->due_date = $request->due_date;
        $borrow->status = 'Borrowed';
        $borrow->save();

        $book = Book::find($request->book_id);
        $book->status = 'Borrowed';
        $book->save();

        return redirect()->back()->with('success', 'บันทึกการยืมหนังสือเรียบร้อยแล้ว');
    }

    public function update(Request $request, $id)
    {
        $borrow = Borrow::findOrFail($id);

        if ($request->has('action') && $request->action == 'return') {
            $fine = 0;
            if (Carbon::now()->gt($borrow->due_date)) {
                $days_over = Carbon::now()->diffInDays($borrow->due_date);
                $fine = $days_over * 10; 
            }

            $borrow->status = 'Returned';
            $borrow->return_date = Carbon::now();
            $borrow->fine_amount = $fine;
            $borrow->save();

            $borrow->book->update(['status' => 'Available']);

            return redirect()->back()->with('success', 'รับคืนหนังสือเรียบร้อยแล้ว');
        } else {
            $request->validate([
                'due_date' => 'required|date',
            ]);

            $borrow->due_date = $request->due_date;
            $borrow->note = $request->note;
            $borrow->fine_amount = $request->fine_amount ?? 0;
            $borrow->save();

            return redirect()->back()->with('success', 'แก้ไขข้อมูลการยืมเรียบร้อยแล้ว');
        }
    }
}