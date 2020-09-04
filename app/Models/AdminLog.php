<?php

namespace App\Models;

use App\Traits\SerializeDate;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminLog
 * @package App\Models
 *
 * @property int user_id
 * @property string path
 * @property string method
 * @property string ip
 * @property string input
 */

class AdminLog extends Model
{
    use SerializeDate;

    protected $fillable = ['user_id', 'path', 'method', 'ip', 'input'];

    public function adminUser()
    {
        return $this->belongsTo(AdminUser::class, 'user_id', 'id');
    }
}
