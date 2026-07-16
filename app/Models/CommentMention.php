<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentMention extends Model
{
    protected $table = 'CommentMentions';

    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $fillable = [
        'CommentId',
        'MentionedUserId',
        'CreatedAt',
        'UpdatedAt',
    ];

    /**
     * Automatically converts database values into the appropriate PHP data types.
     */
    protected function casts(): array
    {
        return [
            'CreatedAt' => 'datetime',
            'UpdatedAt' => 'datetime',
        ];
    }

    /**
     * The comment that contains this mention.
     */
    public function comment()
    {
        return $this->belongsTo(TicketComment::class, 'CommentId', 'Id');
    }

    /**
     * The user who was mentioned in the comment.
     */
    public function mentionedUser()
    {
        return $this->belongsTo(User::class, 'MentionedUserId', 'Id');
    }
}
