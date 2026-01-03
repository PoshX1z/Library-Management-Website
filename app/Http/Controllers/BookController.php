<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;
use App\Models\Author;
use Illuminate\Support\Facades\File;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $authors = Author::all();

        $query = Book::with(['category', 'author']);

        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        $books = $query->orderBy('id', 'desc')->get();

        return view('books.index', compact('books', 'categories', 'authors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'isbn' => 'required',
            'price' => 'required|numeric',
            'author_id' => 'required',
            'category_id' => 'required',
        ]);

        $book = new Book();
        $book->title = $request->title;
        $book->isbn = $request->isbn;
        $book->author_id = $request->author_id;
        $book->category_id = $request->category_id;
        $book->price = $request->price;
        $book->stock_quantity = $request->stock_quantity ?? 1;
        $book->location = $request->location;
        $book->description = $request->description;
        
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/books'), $filename);
            $book->image = $filename;
        } else {
            $book->image = 'book1.png'; 
        }

        $book->save();
        return redirect()->back()->with('success', 'เพิ่มหนังสือเรียบร้อยแล้ว');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'isbn' => 'required',
            'price' => 'required|numeric',
            'author_id' => 'required',
            'category_id' => 'required',
        ]);

        $book = Book::findOrFail($id);
        
        $book->title = $request->title;
        $book->isbn = $request->isbn;
        $book->author_id = $request->author_id;
        $book->category_id = $request->category_id;
        $book->price = $request->price;
        $book->stock_quantity = $request->stock_quantity;
        $book->location = $request->location;
        $book->description = $request->description;
        $book->status = $request->status;

        if ($request->hasFile('image')) {
            $oldImagePath = public_path('images/books/' . $book->image);
            
            if ($book->image !== 'book1.png' && File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }

            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension(); 
            $file->move(public_path('images/books'), $filename);
            
            $book->image = $filename;
        }

        $book->save();
        return redirect()->back()->with('success', 'แก้ไขข้อมูลหนังสือเรียบร้อยแล้ว');
    }
    
    public function destroy($id)
    {
        Book::destroy($id);
        return redirect()->back()->with('success', 'ลบหนังสือเรียบร้อยแล้ว');
    }
}