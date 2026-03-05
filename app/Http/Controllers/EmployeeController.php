<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Location;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    // ADMIN FUNCTIONS

    public function index()
    {
        $employees = Employee::with('user', 'location')->get();
        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        $locations = Location::all();
        
        $lastEmployee = Employee::orderBy('id', 'desc')->first();
        if ($lastEmployee && preg_match('/EMP-(\d+)/', $lastEmployee->employee_code, $matches)) {
            $number = (int) $matches[1];
            $nextCode = 'EMP-' . str_pad($number + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextCode = 'EMP-001';
        }

        return view('admin.employees.create', compact('locations', 'nextCode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'employee_code' => 'required|string|unique:employees,employee_code',
            'designation' => 'required|string|max:255',
            'location_id' => 'required|exists:locations,id',
            'status' => 'required|in:active,inactive',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'employee'
        ]);

        Employee::create([
            'user_id' => $user->id,
            'employee_code' => $validated['employee_code'],
            'designation' => $validated['designation'],
            'location_id' => $validated['location_id'],
            'status' => $validated['status']
        ]);

        return redirect()->route('admin.employees.index')->with('success', 'Employee created successfully.');
    }

    public function edit(Employee $employee)
    {
        $locations = Location::all();
        return view('admin.employees.edit', compact('employee', 'locations'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$employee->user_id,
            'employee_code' => 'required|string|unique:employees,employee_code,'.$employee->id,
            'designation' => 'required|string|max:255',
            'location_id' => 'required|exists:locations,id',
            'password' => 'nullable|string|min:6', // optional password
            'status' => 'required|in:active,inactive',
        ]);

        $employee->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (!empty($validated['password'])) {
            $employee->user->update(['password' => Hash::make($validated['password'])]);
        }

        $employee->update([
            'employee_code' => $validated['employee_code'],
            'designation' => $validated['designation'],
            'location_id' => $validated['location_id'],
            'status' => $validated['status']
        ]);

        return redirect()->route('admin.employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->user->delete(); // automatically deletes employee because of cascade
        return redirect()->route('admin.employees.index')->with('success', 'Employee deleted successfully.');
    }

    // EMPLOYEE FUNCTIONS

    public function dashboard()
    {
        $user = auth()->user();
        if (!$user || $user->role !== 'employee' || !$user->employee) {
            abort(403);
        }

        $employee = $user->employee;
        $todayAuth = Attendance::where('employee_id', $employee->id)
                               ->whereDate('created_at', Carbon::today())
                               ->first();

        return view('employee.dashboard', compact('employee', 'todayAuth'));
    }
}
