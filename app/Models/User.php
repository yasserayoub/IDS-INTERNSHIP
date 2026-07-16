<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'Users';

    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $fillable = [
        'RoleId',
        'Name',
        'Email',
        'Password',
        'Phone',
        'Department',
        'IsActive',
        'RememberToken',
        'CreatedAt',
        'UpdatedAt',
    ];
    public function getAuthPassword()
{
    return $this->Password;
}

    protected $hidden = [
        'Password',
        'RememberToken',
    ];

    protected function casts(): array
    {
        return [
            'Password' => 'hashed',
            'IsActive' => 'boolean',
            'CreatedAt' => 'datetime',
            'UpdatedAt' => 'datetime',
        ];
    }

    /**
     * A user belongs to one role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'RoleId', 'Id');
    }

    /**
     * A user can create many tickets.
     */
    public function createdTickets()
    {
        return $this->hasMany(Ticket::class, 'CreatedByUserId', 'Id');
    }

    /**
     * A user can have many ticket assignments.
     */
    public function ticketAssignments()
    {
        return $this->hasMany(TicketAssignment::class, 'UserId', 'Id');
    }

    /**
     * A user can write many ticket comments.
     */
    public function ticketComments()
    {
        return $this->hasMany(TicketComment::class, 'UserId', 'Id');
    }

    /**
     * A user can receive many notifications.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'UserId', 'Id');
    }

    /**
     * A user can have many activity logs.
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'UserId', 'Id');
    }

    /**
     * A user can be mentioned in many comment mentions.
     */
    public function commentMentions()
    {
        return $this->hasMany(CommentMention::class, 'MentionedUserId', 'Id');
    }
}

