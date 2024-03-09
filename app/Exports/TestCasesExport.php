<?php

namespace App\Exports;

use App\Models\SelectedTestCase;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TestCasesExport implements FromQuery, WithHeadings
{
    use Exportable;

    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function query()
    {
        return SelectedTestCase::query()
            ->where('user_id', $this->userId)
            ->join('test_cases', 'selected_test_cases.test_case_id', '=', 'test_cases.id')
            ->select('test_cases.test_domain', 'test_cases.module_name', 'test_cases.test_description', DB::raw('CASE WHEN test_cases.test_case_type = 1 THEN "Positive" ELSE "Negative" END AS test_case_type'), 'test_cases.test_step', 'test_cases.test_data', 'test_cases.expected_result', 'test_cases.actual_result');
    }

    public function headings(): array
    {
        return [
            'Test Case Domain',
            'Module Name',
            'Test Description',
            'Test Case Type',
            'Test Case Step',
            'Test Data',
            'Expected Result',
            'Actual Result',
        ];
    }
}
