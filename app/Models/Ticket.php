<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'Tickets';

    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $fillable = [
        'ReferenceNumber',
        'CreatedByUserId',
        'CategoryId',
        'PriorityId',
        'StatusId',
        'Title',
        'Description',
        'ResolvedAt',
        'ClosedAt',
        'CreatedAt',
        'UpdatedAt',
    ];

    protected function casts(): array //Automatically converts these database columns into PHP datetime (Carbon) objects.
    {
        return [
            'ResolvedAt' => 'datetime', //$ticket->CreatedAt;it will return "2026-07-15 14:30:00"   // String
            'ClosedAt' => 'datetime',   //so we use $ticket->CreatedAt->format('d/m/Y'); like in this case we change the string to "15/07/2026"   // Carbon object so we can use Carbon methods like $ticket->CreatedAt->addDays(7); to add 7 days to the date
            'CreatedAt' => 'datetime',
            'UpdatedAt' => 'datetime',
        ];
    }

    /**
     * The employee who created the ticket.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'CreatedByUserId', 'Id'); // one to one relationship, a ticket belongs to one user (creator), and a user can create many tickets.  
    }

    /**
     * The ticket category.
     */
    public function category()
    {
        return $this->belongsTo(TicketCategory::class, 'CategoryId', 'Id');
    }

    /**
     * The ticket priority.
     */
    public function priority()
    {
        return $this->belongsTo(TicketPriority::class, 'PriorityId', 'Id');
    }

    /**
     * The ticket status.
     */
    public function status()
    {
        return $this->belongsTo(TicketStatus::class, 'StatusId', 'Id');
    }

    /**
     * Users assigned to this ticket.
     */
    public function assignments()
    {
        return $this->hasMany(TicketAssignment::class, 'TicketId', 'Id');
    }

    /**
     * Comments on this ticket.
     */
    public function comments()
    {
        return $this->hasMany(TicketComment::class, 'TicketId', 'Id');
    }

    /**
     * Attachments uploaded for this ticket.
     */
    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class, 'TicketId', 'Id');
    }

    /**
     * Escalation history.
     */
    public function escalations()
    {
        return $this->hasMany(TicketEscalation::class, 'TicketId', 'Id');
    }

    /**
     * Ticket history.
     */
    public function histories()
    {
        return $this->hasMany(TicketHistory::class, 'TicketId', 'Id');
    }
}
