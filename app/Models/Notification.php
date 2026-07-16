<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'Notifications';

    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $fillable = [
        'UserId',
        'TicketId',
        'Type',
        'Title',
        'Message',
        'IsRead',
        'ReadAt',
        'CreatedAt',
        'UpdatedAt',
    ];

    /**
     * Automatically converts database values into the appropriate PHP data types.
     */
    protected function casts(): array
    {
        return [
            'IsRead' => 'boolean',
            'ReadAt' => 'datetime',
            'CreatedAt' => 'datetime',
            'UpdatedAt' => 'datetime',
        ];
    }

    /**
     * The user who receives this notification.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'UserId', 'Id');
    }

    /**
     * The ticket related to this notification.
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'TicketId', 'Id');
    }
}
