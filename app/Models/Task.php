<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = ['task_name', 'note','start','end','status','parent_id', 'projectID','userID','stt'];

    public function projects()
    {
        return $this->belongsTo(Project::class,'projectID');
    }
    public function users()
    {
        return $this->belongsTo(User::class,'userID');
    }

}
