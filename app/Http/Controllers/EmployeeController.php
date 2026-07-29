<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketComment;
use Illuminate\Support\Facades\Auth;


class EmployeeController extends Controller
{
    public function dashboard()
    {
        $tickets = Ticket::where('CreatedByUserId', Auth::user()->Id)->get();
        return view('employee.dashboard', compact('tickets'));
    }
    public function storeComment(Request $request, $id)
{
    $request->validate([
        'Content' => 'required|string|max:5000',
    ]);

    $ticket = Ticket::where('Id', $id)
        ->where('CreatedByUserId', Auth::user()->Id)
        ->firstOrFail();

    TicketComment::create([
        'TicketId'   => $ticket->Id,
        'UserId'     => Auth::user()->Id,
        'Content'    => $request->Content,
        'IsInternal' => false,
        'CreatedAt'  => now(),
        'UpdatedAt'  => now(),
    ]);

    return redirect()
        ->route('employee.tickets.show', $ticket->Id)
        ->with('success', 'Comment added successfully.');
}

}
