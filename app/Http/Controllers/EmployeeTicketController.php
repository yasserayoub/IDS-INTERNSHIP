<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeeTicketController extends Controller
{
    public function show($id){
        return view('employee.tickets', ['ticketId' => $id]);

    }
    public function edit($id){
        return view('employee.editTicket', ['ticketId' => $id]);
    }
}
