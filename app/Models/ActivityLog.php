<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'ActivityLogs';

    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $fillable = [
        'UserId',
        'Action',
        'EntityType',
        'EntityId',
        'Description',
        'IpAddress',
        'CreatedAt',
        'UpdatedAt',
    ];

    /**
     * Automatically converts database values into the appropriate PHP data types.
     */
    protected function casts(): array
    {
        return [
            'EntityId' => 'integer',
            'CreatedAt' => 'datetime',
            'UpdatedAt' => 'datetime',
        ];
    }

    /**
     * The user who performed this activity.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'UserId', 'Id');
    }
}
