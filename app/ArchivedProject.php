<?php

namespace Diploma;

use Illuminate\Database\Eloquent\Model;

/**
 *  @author Fabian Emanuel Pintea
 *  Bachelor's degree project ACS UPB 2018 
 */
class ArchivedProject extends Model
{
    protected $fillable = [
        'title', 'teacher_name', 'post_date', 'students'
    ];

    protected $casts = [
        'students' => 'array'
    ];
}
