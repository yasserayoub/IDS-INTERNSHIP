<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Ticket;

class NotificationService
{

    public static function send(
        int $userId,
        ?int $ticketId,
        string $type,
        string $title,
        string $message
    ): void {

        Notification::create([

            'UserId' => $userId,

            'TicketId' => $ticketId,

            'Type' => $type,

            'Title' => $title,

            'Message' => $message,

            'IsRead' => false,

            'CreatedAt' => now(),

            'UpdatedAt' => now(),

        ]);
    }
    public static function notifyTicketCreator(
    Ticket $ticket,
    string $type,
    string $title,
    string $message
): void {

    self::send(
        $ticket->CreatedByUserId,
        $ticket->Id,
        $type,
        $title,
        $message
    );

}
public static function notifyAssignedAgent(
    Ticket $ticket,
    string $type,
    string $title,
    string $message
): void {

    if ($ticket->currentAssignment) {

        self::send(
            $ticket->currentAssignment->AssignedToUserId,
            $ticket->Id,
            $type,
            $title,
            $message
        );

    }

}

}
