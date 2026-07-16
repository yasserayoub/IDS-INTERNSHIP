<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketEscalation extends Model
{
    protected $table = 'TicketEscalations';

    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $fillable = [
        'TicketId',
        'EscalatedByUserId',
        'EscalatedToUserId',
        'Reason',
        'EscalationLevel',
        'EscalatedAt',
        'CreatedAt',
        'UpdatedAt',
    ];

    /**
     * Automatically converts database values into the appropriate PHP data types.
     */
    protected function casts(): array
    {
        return [
            'EscalationLevel' => 'integer',
            'EscalatedAt' => 'datetime',
            'CreatedAt' => 'datetime',
            'UpdatedAt' => 'datetime',
        ];
    }

    /**
     * The ticket that was escalated.
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'TicketId', 'Id');
    }

    /**
     * The user who escalated the ticket.
     */
    public function escalatedBy()
    {
        return $this->belongsTo(User::class, 'EscalatedByUserId', 'Id');
    }

    /**
     * The user to whom the ticket was escalated.
     */
    public function escalatedTo()
    {
        return $this->belongsTo(User::class, 'EscalatedToUserId', 'Id');
    }
}
