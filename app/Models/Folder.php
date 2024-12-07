<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'parent_id'];

    // Relación para obtener los subcarpetas
    public function children()
    {
        return $this->hasMany(Folder::class, 'parent_id');
    }

    // Relación para obtener la carpeta padre
    public function parent()
    {
        return $this->belongsTo(Folder::class, 'parent_id');
    }

    // Relación con los archivos en esta carpeta
    public function files()
    {
        return $this->hasMany(File::class, 'folder_id');
    }
}
