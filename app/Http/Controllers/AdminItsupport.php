<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class AdminITsupport extends Controller
{
public function AllTickets()
{
    $tickets = Ticket::with([
        'creator',
        'category',
        'priority',
        'status'
    ])
    ->orderBy('CreatedAt', 'desc')
    ->get();

    return view('tickets.index', compact('tickets'));
}
}
