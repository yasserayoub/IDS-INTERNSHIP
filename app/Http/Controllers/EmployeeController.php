<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;


class EmployeeController extends Controller
{
    public function dashboard()
    {
        $tickets = Ticket::where('CreatedByUserId', Auth::user()->Id)->get();
        return view('employee.dashboard', compact('tickets'));
    }

}
