<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{

    public function index($department)
    {
        return view('app.department.dashboard', compact('department'));
    }
    /**
     * This will load the view to send a POST Request to the selected department External Planning
     * @param $department
     * @return Application|Factory|View
     */
    public function login($department)
    {
        return view('app.department.auth.login', compact('department'));
    }
}
