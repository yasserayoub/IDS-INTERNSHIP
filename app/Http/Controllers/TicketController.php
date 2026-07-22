<?php

namespace App\Http\Controllers;
use App\Models\TicketCategory;
use App\Models\TicketPriority;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Validator;



use Illuminate\Http\Request;

class TicketController extends Controller
{

public function store(Request $request)
{
    $request->validate([
        'Title' => 'required|string|max:255',
        'CategoryId' => 'required|exists:TicketCategories,Id',
        'PriorityId' => 'required|exists:TicketPriorities,Id',
        'Description' => 'required|string',
    ]);

    $ticket = new Ticket();

    $ticket->ReferenceNumber = 'TKT-' . strtoupper(Str::random(8));

      $ticket->CreatedByUserId = Auth::user()->Id;

    $ticket->CategoryId = $request->CategoryId;
    $ticket->PriorityId = $request->PriorityId;


    $ticket->StatusId = 1; // ticket is opened

    $ticket->Title = $request->Title;
    $ticket->Description = $request->Description;

    $ticket->CreatedAt = now();
    $ticket->UpdatedAt = now();

    $ticket->save();

    return redirect()
        ->route('CreateTicket')
        ->with('success', 'Ticket created successfully.');
}
}
