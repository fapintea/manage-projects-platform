<?php

namespace Diploma;

use Illuminate\Database\Eloquent\Model;

/**
 *  @author Fabian Emanuel Pintea
 *  Bachelor's degree project ACS UPB 2018 
 */
class UserRequest extends Model
{
    protected $fillable = [
        'student_id', 'project_id'
    ];

    protected $table = 'requests';

    public function project() {
        return $this->belongsTo('Diploma\Project', 'project_id', 'id');
    }

    public function student() {
        return $this->belongsTo('Diploma\User', 'student_id', 'id');
    }
}
