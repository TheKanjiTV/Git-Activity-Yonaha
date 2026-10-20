<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HumanResourceController extends Controller
{
    public function payroll(Request $request)
    {
        //employeeType
        $employeeType = $request->employeeType;
        //positionTier
        $positionTier = $request->positionTier;
        //daysAbsent
        $daysAbsent = $request->daysAbsent;
        //minutesLate
        $minutesLate = $request->minutesLate;
        //overtimeHours
        $overtimeHours = $request->overtimeHours;

        //apply if, else if, and else condition statements.
        //use comparison operators such as ==
        //apply arithmetic operators and standard mathematical operator precedence.
        //calculate gross pay, deductions and net pay.
        //return the result as a json response.
        //test different employee scenario using postman.

        if ($employeeType == "COS" && $positionTier == "None") {
            $baseSalary = 18000;
            $sss = 0;
            $philHealth = 0;
            $pagIbig = 0;
        } else if ($employeeType == "Regular" && $positionTier == "Junior") {
            $baseSalary = 25000;
            $sss = 1125;
            $philHealth = 625;
            $pagIbig = 200;
        } else if ($employeeType == "Regular" && $positionTier == "Senior") {
            $baseSalary = 40000;
            $sss = 1800;
            $philHealth = 1000;
            $pagIbig = 200;
        } else {
            return response()->json([
                "message" => "Invalid employee type or position tier"
            ]);
        }

        // days present 22 - days absent
        $daysPresent = 22 - $daysAbsent;
        // daily rate = monthly base salary / 22
        $dailyRate = $baseSalary / 22;
        //hourly rate = daily rate / 8
        $hourlyRate = $dailyRate / 8;
        //absent deduction = daily * days absent
        $absentDeduction = $dailyRate * $daysAbsent;
        //late deduction = hourly rate / 60 * minutes late
        $lateDeduction = ($hourlyRate / 60) * $minutesLate;
        //overtime pay = overtime hours * (Hourly Rate * 1.25)
        $overtimePay = $overtimeHours * ($hourlyRate * 1.25);
        //gross pay = monthly base salary - late deduction - absent deduction + overtime pay
        $grossPay = $dailyRate - $daysPresent - $lateDeduction + $overtimePay;
        //Total Deductions = SSS + PhilHealth + Pag-IBIG
        $totalDeductions = $sss + $philHealth + $pagIbig;
        //net pay = gross pay - total deductions
        $netPay = $grossPay - $totalDeductions;

        return response()->json([
            "employeeType" => $employeeType,
            "positionTier" => $positionTier,
            "daysAbsent" => $daysAbsent,
            "minutesLate" => $minutesLate,
            "overtimeHours" => $overtimeHours,
            "baseSalary" => round($baseSalary, 2),
            "sss" => round($sss, 2),
            "philHealth" => round($philHealth, 2),
            "pagIbig" => round($pagIbig, 2),
            "daysPresent" => $daysPresent,
            "dailyRate" => round($dailyRate, 2),
            "hourlyRate" => round($hourlyRate, 2),
            "lateDeduction" => round($lateDeduction, 2),
            "absentDeduction" => round($absentDeduction, 2),
            "overtimePay" => round($overtimePay, 2),
            "grossPay" => round($grossPay, 2),
            "totalDeductions" => round($totalDeductions, 2),
            "netPay" => round($netPay, 2)
        ]);
    }
}
