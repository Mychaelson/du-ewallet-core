<?php

namespace App\Repositories\Payroll;

use App\Models\Payroll\Employee;

class EmployeeRepository
{
    public function __construct(private Employee $employee)
    {
    }

    public function updatePdfPassword($userId, $password)
    {
        $updated = $this->employee->query()
            ->where('user_id', $userId)
            ->update([
                'pass_pdf' => \base64_encode($password),
            ]);

        return $updated;
    }

    public function getEmployeeByUserId($userId)
    {
        $employee = $this->employee->query()
            ->where('user_id', $userId)
            ->latest()
            ->first();

        return $employee;
    }
}
