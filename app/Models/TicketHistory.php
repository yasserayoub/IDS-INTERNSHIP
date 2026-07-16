<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketHistory extends Model
{
    protected $table = 'TicketHistories';

    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $fillable = [
        'TicketId',
        'ChangedByUserId',
        'FieldName',
        'OldValue',
        'NewValue',
        'ChangedAt',
        'CreatedAt',
        'UpdatedAt',
    ];

    /**
     * Automatically converts database values into the appropriate PHP data types.
     */
    protected function casts(): array
    {
        return [
            'ChangedAt' => 'datetime',
            'CreatedAt' => 'datetime',
            'UpdatedAt' => 'datetime',
        ];
    }

    /**
     * The ticket whose history is being recorded.
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'TicketId', 'Id');
    }

    /**
     * The user who made the change.
     */
    public function changedBy()
    {
        return $this->belongsTo(User::class, 'ChangedByUserId', 'Id');
    }
}
