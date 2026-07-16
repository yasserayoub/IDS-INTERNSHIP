<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'Roles';

    protected $primaryKey = 'Id'; //laravel knows id but in our table we used Id as primary key so we need to tell laravel that our primary key is Id

    const CREATED_AT = 'CreatedAt'; //alo same here
    const UPDATED_AT = 'UpdatedAt';

    protected $fillable = [
        'Name',
        'Description'
    ];

    // Relationships

    public function users()
    {
        return $this->hasMany(User::class, 'RoleId', 'Id'); //Role has many users fk us RoleId and pk is Id
    }
}
