<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'path', 'share_token', 'description', 'tags', 'folder_id'];

    /**
     * Relación con la carpeta que contiene el archivo.
     */
    public function folder()
    {
        return $this->belongsTo(Folder::class, 'folder_id');
    }

    /**
     * Generar un token para compartir el archivo.
     */
    public function generateShareToken()
    {
        $this->share_token = bin2hex(random_bytes(16));
        $this->save();
    }

    /**
     * Obtener los tags como un array.
     */
    public function getTagsAttribute($value)
    {
        return json_decode($value, true);
    }

    /**
     * Establecer los tags como un JSON.
     */
    public function setTagsAttribute($value)
    {
        $this->attributes['tags'] = json_encode($value);
    }
}
