<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Carbon\Carbon;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::orderBy('is_read', 'asc')
                           ->orderBy('created_at', 'desc')
                           ->get();
                           
        return view('contacts.index', compact('contacts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required'
        ]);

        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'created_at' => Carbon::now()
        ]);

        return redirect()->back()->with('success', 'ส่งข้อความเรียบร้อยแล้ว');
    }

    public function update(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);

        $request->validate([
            'subject' => 'required',
            'message' => 'required'
        ]);

        $contact->update([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'is_read' => $request->has('is_read') ? 1 : 0
        ]);

        return redirect()->back()->with('success', 'แก้ไขข้อความเรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        Contact::destroy($id);
        return redirect()->back()->with('success', 'ลบข้อความเรียบร้อยแล้ว');
    }
}