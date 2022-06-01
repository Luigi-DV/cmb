<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DepartmentStatisticsController extends Controller
{
    public function index($department)
    {
        return view('app.department.statistics.index', compact('department'));
    }

    public function resources($department)
    {
        return view('app.department.statistics.resources.resources', compact('department'));
    }

    public function projects($department)
    {
        return view('app.department.statistics.projects.projects', compact('department'));
    }

    public function audition($department)
    {
        return view('app.department.statistics.audition.audition', compact('department'));
    }
}
