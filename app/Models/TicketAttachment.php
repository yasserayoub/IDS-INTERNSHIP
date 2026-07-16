<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketAttachment extends Model
{
    protected $table = 'TicketAttachments';

    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $fillable = [
        'TicketId',
        'UploadedByUserId',
        'OriginalFileName',
        'StoredFileName',
        'FilePath',
        'MimeType',
        'FileSize',
        'CreatedAt',
        'UpdatedAt',
    ];

    /**
     * Automatically converts database values into the appropriate PHP data types.
     */
    protected function casts(): array
    {
        return [
            'FileSize' => 'integer',
            'CreatedAt' => 'datetime',
            'UpdatedAt' => 'datetime',
        ];
    }

    /**
     * The ticket this attachment belongs to.
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'TicketId', 'Id');
    }

    /**
     * The user who uploaded the attachment.
     */
    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'UploadedByUserId', 'Id');
    }
}
