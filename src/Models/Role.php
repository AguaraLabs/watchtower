<?php 

namespace Aguaralabs\Watchtower\Models;

use Illuminate\Database\Eloquent\Model;

use Aguaralabs\Watchtower\Models\User;
use Aguaralabs\Watchtower\Models\Permission;

class Role extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'description', 'special'];

    /**
     * The users that belong to the role.
     */
    public function users()
    {
        return $this->belongsToMany('Aguaralabs\Watchtower\Models\User');
    }
    
    /**
     * The users that belong to the role.
     */
    public function permissions()
    {
        return $this->belongsToMany('Smarch\Watchtower\Models\Permission');
    }
    
}
