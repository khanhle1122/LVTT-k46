<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doson extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = 'document_son';

    protected $fillable = ['documentID','do_son_name'];

    public function file_son()
    {
        return $this->belongsTo(FileSon::class,'do_sonID');
    }

    public function document()
    {
        return $this->hasMany(Document::class, 'documentID');
    }
}
