<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketPriority extends Model
{
    protected $table = 'TicketPriorities';

    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $fillable = [
        'Name',
        'Description',
        'CreatedAt',
        'UpdatedAt',
    ];

    /**
     * A priority can be assigned to many tickets.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'PriorityId', 'Id');
    }
}
