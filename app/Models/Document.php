<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = "document";
    protected $primaryKey = 'id';
    protected $fillable = [
        "nombre"
    ];

    public function chunks(){
        return $this->hasMany(DocumentChunk::class, "id_documento", "id");
    }
}
