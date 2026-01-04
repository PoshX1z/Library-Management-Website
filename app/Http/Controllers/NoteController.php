<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use Carbon\Carbon;

class NoteController extends Controller
{
    public function index()
    {
        $active_notes = Note::where('is_completed', false)
                            ->orderByRaw("FIELD(priority, 'High', 'Medium', 'Low')")
                            ->orderBy('created_at', 'desc')
                            ->get();

        $completed_notes = Note::where('is_completed', true)
                               ->orderBy('created_at', 'desc')
                               ->take(10)
                               ->get();

        return view('notes.index', compact('active_notes', 'completed_notes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'priority' => 'required'
        ]);

        $note = new Note();
        $note->staff_id = 1; 
        $note->title = $request->title;
        $note->content = $request->content;
        $note->priority = $request->priority;
        $note->created_at = Carbon::now();
        $note->save();

        return redirect()->back()->with('success', 'บันทึกโน้ตใหม่เรียบร้อยแล้ว');
    }

    public function update(Request $request, $id)
    {
        $note = Note::findOrFail($id);
        
        if ($request->has('toggle_status')) {
            $note->is_completed = !$note->is_completed;
            $note->save();
            return redirect()->back()->with('success', 'อัปเดตสถานะเรียบร้อยแล้ว');
        }

        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'priority' => 'required'
        ]);

        $note->update($request->except('toggle_status'));

        return redirect()->back()->with('success', 'แก้ไขโน้ตเรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        Note::destroy($id);
        return redirect()->back()->with('success', 'ลบโน้ตเรียบร้อยแล้ว');
    }
}