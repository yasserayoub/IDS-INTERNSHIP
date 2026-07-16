<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketCategory extends Model
{
    protected $table = 'TicketCategories';

    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $fillable = [
        'Name',
        'Description',
        'CreatedAt',
        'UpdatedAt',
    ];

    /**
     * A category can have many tickets.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'CategoryId', 'Id');
    }
}
