<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class Permission extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;

    protected $fillable = ['name'];

    public function roles()
    {
        return $this->belongsToMany(Role::class,'roles_permissions');
    }
}
