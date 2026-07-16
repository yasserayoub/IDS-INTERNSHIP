<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketStatus extends Model
{
    protected $table = 'TicketStatuses';

    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $fillable = [
        'Name',
        'Description',
        'CreatedAt',
        'UpdatedAt',
    ];

    /**
     * A status can belong to many tickets.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'StatusId', 'Id');
    }
}
