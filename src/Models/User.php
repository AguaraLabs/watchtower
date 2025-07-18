<?php

namespace Aguaralabs\Watchtower\Models;

use Illuminate\Database\Eloquent\Model;

use Aguaralabs\Watchtower\Models\Role;

class User extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    
    /**
     * The roles that have the permissions.
     */
    public function roles()
    {
        return $this->belongsToMany('Smarch\Watchtower\Models\Role');
    }
}
