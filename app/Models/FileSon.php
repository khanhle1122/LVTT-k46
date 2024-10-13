<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileSon extends Model
{
    use HasFactory;
    protected $table = 'file_son';

    protected $fillable = ['file_son_name', 'file_son_path', 'document_sonID'];

    public function doson()
    {
        return $this->belongsTo(Doson::class,'document_sonID');
    }
}
