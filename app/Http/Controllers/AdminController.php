<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today();
        
        $totalEmployees = Employee::where('status', 'active')->count();
        $attendancesToday = Attendance::whereDate('created_at', $today)->get();
        
        $presentCount = $attendancesToday->count();
        $absentCount = $totalEmployees - $presentCount;
        $lateCount = $attendancesToday->where('status', 'late')->count();
        
        return view('admin.dashboard', compact('totalEmployees', 'presentCount', 'absentCount', 'lateCount', 'attendancesToday'));
    }
}
