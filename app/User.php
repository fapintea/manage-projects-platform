<?php

namespace Diploma;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Config;

/**
 *  @author Fabian Emanuel Pintea
 *  Bachelor's degree project ACS UPB 2018 
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'username', 'role_id', 'masters_name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function project() {
        return $this->hasOne('Diploma\Project', 'id', 'project_id');
    }

    public function projects() {
        return $this->hasMany('Diploma\Project', 'teacher_id', 'id');
    }

    public function role() {
        return $this->hasOne('Diploma\Role', 'id', 'role_id');
    }

    public function isStudent() {
        return $this->role_id == Config::get('constants.BACHELOR_ROLE_ID') ||
            $this->role_id == Config::get('constants.MASTER_ROLE_ID');
    }

    public function isBachelorStudent() {
        return $this->role_id == Config::get('constants.BACHELOR_ROLE_ID');
    }

    public function isMasterStudent() {
        return $this->role_id == Config::get('constants.MASTER_ROLE_ID');
    }

    public function isTeacher() {
        return $this->role_id == Config::get('constants.TEACHER_ROLE_ID') ||
            $this->role_id == Config::get('constants.ADMIN_ROLE_ID') ||
            $this->role_id == Config::get('constants.SUPERADMIN_ROLE_ID');
    }

    public function isAdmin() {
        return $this->role_id == Config::get('constants.ADMIN_ROLE_ID') ||
            $this->role_id == Config::get('constants.SUPERADMIN_ROLE_ID');
    }
    
    public function isSuperAdmin() {
        return $this->role_id == Config::get('constants.SUPERADMIN_ROLE_ID');
    }
}
