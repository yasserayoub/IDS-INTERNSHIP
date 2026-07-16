<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketAssignment extends Model
{
    protected $table = 'TicketAssignments';

    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $fillable = [
        'TicketId',
        'AssignedToUserId',
        'AssignedByUserId',
        'AssignmentReason',
        'AssignedAt',
        'UnassignedAt',
        'IsCurrent',
        'CreatedAt',
        'UpdatedAt',
    ];

    /**
     * Automatically converts database values into the appropriate PHP data types.
     */
    protected function casts(): array
    {
        return [
            'AssignedAt' => 'datetime',
            'UnassignedAt' => 'datetime',
            'CreatedAt' => 'datetime',
            'UpdatedAt' => 'datetime',
            'IsCurrent' => 'boolean',
        ];
    }

    /**
     * This assignment belongs to one ticket.
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'TicketId', 'Id');
    }

    /**
     * The IT Support user assigned to the ticket.
     */
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'AssignedToUserId', 'Id');
    }

    /**
     * The user who made the assignment.
     */
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'AssignedByUserId', 'Id');
    }
}
