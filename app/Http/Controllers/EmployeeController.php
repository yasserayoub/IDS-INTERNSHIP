<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\User;

use App\Services\NotificationService;

class EmployeeController extends Controller
{
    public function dashboard()
    {
        $tickets = Ticket::where(
            'CreatedByUserId',
            Auth::user()->Id
        )->get();

        return view(
            'employee.dashboard',
            compact('tickets')
        );
    }

    public function storeComment(Request $request, $id)
    {
        $request->validate([
            'Content' => 'required|string|max:5000',
        ]);

        $ticket = Ticket::with('currentAssignment')
            ->where('Id', $id)
            ->where('CreatedByUserId', Auth::user()->Id)
            ->firstOrFail();

        // -----------------------------------------------------
        // SAVE COMMENT
        // -----------------------------------------------------

        TicketComment::create([

            'TicketId'   => $ticket->Id,

            'UserId'     => Auth::user()->Id,

            'Content'    => $request->Content,

            'IsInternal' => false,

            'CreatedAt'  => now(),

            'UpdatedAt'  => now(),

        ]);

       //send notification to assigned agent if there is one

        $message = Auth::user()->Name .
            ' commented on ticket ' .
            $ticket->ReferenceNumber . '.';

        // Notify Assigned IT Support
        if ($ticket->currentAssignment) {

            NotificationService::send(

                $ticket->currentAssignment->AssignedToUserId,

                $ticket->Id,

                'comment_added',

                'New Comment',

                $message

            );

        }

        // Notify Administrators
        $administrators = User::whereHas('role', function ($query) {

            $query->where('Name', 'Administrator');

        })->get();

        foreach ($administrators as $admin) {

            NotificationService::send(

                $admin->Id,

                $ticket->Id,

                'comment_added',

                'New Comment',

                $message

            );

        }

        // Notify IT Managers
        $managers = User::whereHas('role', function ($query) {

            $query->where('Name', 'IT Manager');

        })->get();

        foreach ($managers as $manager) {

            NotificationService::send(

                $manager->Id,

                $ticket->Id,

                'comment_added',

                'New Comment',

                $message

            );

        }

        return redirect()
            ->route(
                'employee.tickets.show',
                $ticket->Id
            )
            ->with(
                'success',
                'Comment added successfully.'
            );
    }
}
