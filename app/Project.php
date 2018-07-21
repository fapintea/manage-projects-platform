<?php

namespace Diploma;

use Illuminate\Database\Eloquent\Model;

/**
 *  @author Fabian Emanuel Pintea
 *  Bachelor's degree project ACS UPB 2018 
 */
class Project extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'teacher_id', 'post_date', 'nr_students'
    ];

    protected $casts = [
        'references' => 'array'
    ];

    public function teacher() {
        return $this->belongsTo('Diploma\User', 'teacher_id', 'id');
    }

    public function students() {
        return $this->hasMany('Diploma\User', 'project_id', 'id');
    }

    public function scopeWithTeachers($query) {
        return $query->join('users', 'projects.teacher_id', '=', 'users.id');
    }
}
