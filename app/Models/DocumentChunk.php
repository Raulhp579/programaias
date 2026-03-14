<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentChunk extends Model
{
    protected $table = "document_chunk";
    protected $primaryKey = 'id';
    protected $fillable = [
        "id_documento",
        "num_pag",
        "contenido",
        "respuesta"
    ];

    public function document(){
        return $this->belongsTo(Document::class, "id_documento", "id");
    }
}
