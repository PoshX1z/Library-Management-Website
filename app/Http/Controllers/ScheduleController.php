<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index()
    {
        $upcoming = Schedule::where('event_date', '>=', Carbon::today())
                            ->orderBy('event_date', 'asc')
                            ->get();

        $past = Schedule::where('event_date', '<', Carbon::today())
                        ->orderBy('event_date', 'desc')
                        ->take(5)
                        ->get();

        return view('schedules.index', compact('upcoming', 'past'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'event_date' => 'required|date',
            'type' => 'required'
        ]);

        Schedule::create($request->all());

        return redirect()->back()->with('success', 'เพิ่มตารางกิจกรรมเรียบร้อยแล้ว');
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'event_date' => 'required|date',
            'type' => 'required'
        ]);

        $schedule = Schedule::findOrFail($id);
        $schedule->update($request->all());

        return redirect()->back()->with('success', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
    }
    public function destroy($id)
    {
        Schedule::destroy($id);
        return redirect()->back()->with('success', 'ลบกิจกรรมเรียบร้อยแล้ว');
    }
}