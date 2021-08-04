<?php

namespace App\Codes\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    protected $table = 'email_logs';
    protected $primaryKey = 'id';
    protected $fillable = [
        'email',
        'content',
        'subject',
        'status',
        'resent_at',
        'error_message',
    ];

    public static function fail($data)
    {
        $data['status'] = 0;
        $log            = new EmailLog($data);
        $log->save();
    }

}
