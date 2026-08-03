<?php

namespace App\Services;

use App\Models\TicketHistory;
use Illuminate\Support\Facades\Auth;

class TicketHistoryLogger
{
    public static function log($ticketId, $fieldName, $oldValue, $newValue)
    {
        TicketHistory::create([
            'TicketId'        => $ticketId,
            'ChangedByUserId' => Auth::id(),
            'FieldName'       => $fieldName,
            'OldValue'        => $oldValue,
            'NewValue'        => $newValue,
            'ChangedAt'       => now(),
            'CreatedAt'       => now(),
            'UpdatedAt'       => now(),
        ]);
    }
}
