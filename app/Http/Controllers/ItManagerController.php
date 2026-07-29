<?php

namespace App\Http\Controllers;
use App\Models\TicketComment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ItManagerController extends Controller
{
    public function storeComment(Request $request, $id)
{
    $request->validate([
        'Content' => 'required|string',
    ]);

    TicketComment::create([
    'TicketId'   => $id,
    'UserId'     => Auth::user()->Id,
    'Content'    => $request->Content,
    'IsInternal' => Auth::user()->role->Name !== 'Employee'
                        ? $request->has('IsInternal')
                        : false,
    'CreatedAt'  => now(),
    'UpdatedAt'  => now(),
]);

    return redirect()->back()->with('success', 'Comment added successfully.');
}
}
