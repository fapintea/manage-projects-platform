<?php

namespace Diploma;

use Illuminate\Database\Eloquent\Model;

/**
 *  @author Fabian Emanuel Pintea
 *  Bachelor's degree project ACS UPB 2018 
 */
class Comment extends Model
{
    protected $fillable = [
        'user_id', 'content', 'project_id', 'comment_datetime'
    ];

    protected $table = 'comments';

    public function user() {
        return $this->belongsTo('Diploma\User', 'user_id', 'id');
    }

    public function project() {
        return $this->belongsTo('Diploma\Project', 'project_id', 'id');
    }
}
