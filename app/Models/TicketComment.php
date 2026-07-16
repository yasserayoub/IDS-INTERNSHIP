<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketComment extends Model
{
    protected $table = 'TicketComments';

    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $fillable = [
        'TicketId',
        'UserId',
        'Content',
        'IsInternal',
        'CreatedAt',
        'UpdatedAt',
    ];

    /**
     * Automatically converts database values into the appropriate PHP data types.
     */
    protected function casts(): array
    {
        return [
            'IsInternal' => 'boolean',
            'CreatedAt' => 'datetime',
            'UpdatedAt' => 'datetime',
        ];
    }

    /**
     * This comment belongs to one ticket.
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'TicketId', 'Id');
    }

    /**
     * The user who wrote the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'UserId', 'Id');
    }

    /**
     * A comment can contain many user mentions.
     */
    public function mentions()
    {
        return $this->hasMany(CommentMention::class, 'CommentId', 'Id');
    }
}
