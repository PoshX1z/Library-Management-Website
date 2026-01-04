<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;
use App\Models\Purchase;
use Carbon\Carbon;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $query = Book::with('category')->where('status', '!=', 'Lost');

        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        $books = $query->orderBy('id', 'desc')->get();

        $history = Purchase::with('book')->orderBy('purchased_at', 'desc')->take(10)->get();

        return view('purchases.index', compact('books', 'categories', 'history'));
    }

    public function store(Request $request)
    {
        $book = Book::findOrFail($request->book_id);

        if ($book->stock_quantity < 1) {
            return response()->json(['error' => 'สินค้าหมด (Out of Stock)'], 400);
        }

        Purchase::create([
            'book_id' => $book->id,
            'buyer_name' => $request->buyer_name ?? 'General Customer',
            'price' => $book->price,
            'purchased_at' => Carbon::now()
        ]);

        $book->decrement('stock_quantity');

        if ($book->stock_quantity == 0) {
            $book->update(['status' => 'Sold Out']);
        }

        return response()->json(['success' => 'ชำระเงินเรียบร้อยแล้ว']);
    }
}