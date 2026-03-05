<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;

class AttendanceController extends Controller
{
    // Employee Functionalities

    public function clockIn(Request $request)
    {
        $employee = auth()->user()->employee;

        // Check assigned location validation
        $location = $employee->location;
        if (!$location) {
            return back()->with('error', 'You do not have an assigned location.');
        }

        // Validate check in location
        $lat = $request->input('latitude');
        $lon = $request->input('longitude');
        $reason = $request->input('reason');
        
        if (!$lat || !$lon) {
            return back()->with('error', 'GPS Location not provided.');
        }

        $distance = $this->calculateDistance($location->latitude, $location->longitude, $lat, $lon);

        if ($distance > $location->radius) {
            return back()->with('error', 'You are out of the allowed location radius.');
        }

        // Check if already checked in today
        $todayAuth = Attendance::where('employee_id', $employee->id)
                               ->whereDate('created_at', Carbon::today())
                               ->first();

        if ($todayAuth) {
            return back()->with('error', 'You have already checked in today.');
        }

        // Validate time
        $nowTime = Carbon::now()->format('H:i:s');
        $officeStartTime = $location->in_time ?? '09:00:00';
        $earlyBuffer = $location->early_buffer ?? 30;
        $lateBuffer = $location->late_buffer ?? 20;

        $startWindow = Carbon::createFromFormat('H:i:s', $officeStartTime)->subMinutes($earlyBuffer)->format('H:i:s');
        $endWindow = Carbon::createFromFormat('H:i:s', $officeStartTime)->addMinutes($lateBuffer)->format('H:i:s');
        
        $status = 'on_time';
        $requiresReason = false;

        if ($nowTime < $officeStartTime) {
            $status = 'early';
        } elseif ($nowTime > $officeStartTime) {
            $status = 'late';
        }

        if ($nowTime < $startWindow || $nowTime > $endWindow) {
            $requiresReason = true;
        }

        if ($requiresReason && empty($reason)) {
            return back()->with('error', 'You are outside the standard check-in window. A reason is mandatory.')->withInput();
        }

        Attendance::create([
            'employee_id' => $employee->id,
            'check_in_time' => Carbon::now(),
            'status' => $status,
            'reason' => $reason,
            'location_id' => $location->id,
        ]);

        return back()->with('success', 'Checked in successfully.');
    }

    public function clockOut(Request $request)
    {
        $employee = auth()->user()->employee;
        $attendance = Attendance::where('employee_id', $employee->id)
                                ->whereDate('created_at', Carbon::today())
                                ->whereNull('check_out_time')
                                ->first();

        if (!$attendance) {
            return back()->with('error', 'No active check-in found for today, or already checked out.');
        }

        $attendance->update([
            'check_out_time' => Carbon::now(),
        ]);

        return back()->with('success', 'Checked out successfully.');
    }

    public function history(Request $request)
    {
        $employee = auth()->user()->employee;
        $query = Attendance::where('employee_id', $employee->id);

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        $attendances = $query->orderBy('created_at', 'desc')->get();
        return view('employee.history', compact('attendances'));
    }

    // Admin Functionalities
    
    public function index(Request $request)
    {
        $query = Attendance::with(['employee.user', 'location']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        $attendances = $query->orderBy('created_at', 'desc')->paginate(20);
        $employees = Employee::with('user')->get();
        $locations = Location::all();

        return view('admin.attendances.index', compact('attendances', 'employees', 'locations'));
    }

    public function export(Request $request)
    {
        return Excel::download(new AttendanceExport($request->all()), 'attendance_report.xlsx');
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2) {
        $earthRadius = 6371000; // in meters
        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }
}
