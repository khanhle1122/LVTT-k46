<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    protected $fillable = ['documentName','projectID'];

    public function files()
    {
        return $this->hasMany(File::class,'documentID');
    }

    public function projects()
    {
        return $this->belongsTo(Project::class, 'projectID');
    }
}
