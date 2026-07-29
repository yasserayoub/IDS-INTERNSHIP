<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    /**
     * Record an activity in the ActivityLogs table.
     *
     * @param string $action
     * @param string $entityType
     * @param int    $entityId
     * @param string $description
     * @return void
     */
    public static function log($action, $entityType, $entityId, $description)
    {
        ActivityLog::create([
            'UserId'      => Auth::id(),
            'Action'      => $action,
            'EntityType'  => $entityType,
            'EntityId'    => $entityId,
            'Description' => $description,
            'IpAddress'   => request()->ip(),
            'CreatedAt'   => now(),
            'UpdatedAt'   => now(),
        ]);
    }

    /**
     * Log a public comment.
     */
    public static function logComment($ticketId)
    {
        self::log(
            'Comment Added',
            'Ticket',
            $ticketId,
            Auth::user()->Name . ' added a comment.'
        );
    }

    /**
     * Log an internal note.
     */
    public static function logInternalNote($ticketId)
    {
        self::log(
            'Internal Note Added',
            'Ticket',
            $ticketId,
            Auth::user()->Name . ' added an internal note.'
        );
    }

    /**
     * Log a ticket status change.
     */
    public static function logStatusChange($ticketId, $oldStatus, $newStatus)
    {
        self::log(
            'Status Changed',
            'Ticket',
            $ticketId,
            Auth::user()->Name .
            " changed the status from {$oldStatus} to {$newStatus}."
        );
    }



    /**
     * Log ticket creation.
     */
    public static function logTicketCreated($ticketId)
    {
        self::log(
            'Ticket Created',
            'Ticket',
            $ticketId,
            Auth::user()->Name . ' created the ticket.'
        );
    }
    public static function logAssignment($ticketId, $assignedTo)
{
    self::log(
        'Ticket Assigned',
        'Ticket',
        $ticketId,
        Auth::user()->Name . " assigned the ticket to {$assignedTo}."
    );
}

public static function logReassignment($ticketId, $oldUser, $newUser)
{
    self::log(
        'Ticket Reassigned',
        'Ticket',
        $ticketId,
        Auth::user()->Name .
        " reassigned the ticket from {$oldUser} to {$newUser}."
    );

}


}
