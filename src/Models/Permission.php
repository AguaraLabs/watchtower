<?php 

namespace Aguaralabs\Watchtower\Models;

use Illuminate\Database\Eloquent\Model;

use Aguaralabs\Watchtower\Models\Role;

class Permission extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'permissions';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'description'];
    
    /**
     * The roles that have the permissions.
     */
    public function roles()
    {
        return $this->belongsToMany('Smarch\Watchtower\Models\Role');
    }

}
