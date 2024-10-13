<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'projectName', 'projectCode', 'description', 'startDate', 'endDate',
        'level', 'budget', 'status', 'clientName', 'userID', 'documentId','progress'
    ];

    public function users()
    {
        return $this->belongsTo(User::class,'userID');
    }
    public function document()
    {
        return $this->hasOne(Document::class, 'projectID');
    }
    public function tasks()
    {
        return $this->hasMany(Task::class, 'projectID');
    }

}

