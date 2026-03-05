<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class AttendanceExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Attendance::query()->with(['employee.user', 'location']);
        
        if (!empty($this->filters['start_date']) && !empty($this->filters['end_date'])) {
            $query->whereBetween('created_at', [
                Carbon::parse($this->filters['start_date'])->startOfDay(),
                Carbon::parse($this->filters['end_date'])->endOfDay()
            ]);
        }

        if (!empty($this->filters['employee_id'])) {
            $query->where('employee_id', $this->filters['employee_id']);
        }

        if (!empty($this->filters['location_id'])) {
            $query->where('location_id', $this->filters['location_id']);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Employee Code',
            'Employee Name',
            'Check In',
            'Check Out',
            'Status',
            'Location',
            'Reason'
        ];
    }

    public function map($attendance): array
    {
        return [
            $attendance->id,
            $attendance->employee->employee_code,
            $attendance->employee->user->name,
            $attendance->check_in_time ? Carbon::parse($attendance->check_in_time)->format('Y-m-d H:i:s') : 'N/A',
            $attendance->check_out_time ? Carbon::parse($attendance->check_out_time)->format('Y-m-d H:i:s') : 'N/A',
            ucfirst($attendance->status),
            $attendance->location ? $attendance->location->location_name : 'No Location',
            $attendance->reason ?? 'None'
        ];
    }
}
