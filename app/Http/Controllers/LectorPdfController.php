<?php

namespace App\Http\Controllers;

use App\Jobs\ProcesarResumenPdf;
use App\Models\Document;
use App\Models\DocumentChunk;
use Exception;
use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;

class LectorPdfController extends Controller
{
    public function lectorPdf(Request $request)
    {

        try {

            set_time_limit(0);

            $ruta = $request->file('pdf')->store("pdfs","public");

            $rutaAbsoluta = storage_path("app/public/" . $ruta);

            $nombreDocumeto = $request->file('pdf')->getClientOriginalName();

            $documento = Document::create([
                'nombre' => $nombreDocumeto,
            ]);

            ProcesarResumenPdf::dispatch($documento, $rutaAbsoluta);

            return response()->json([
                'mensaje' => 'Documento recibido. La IA está trabajando en segundo plano.',
                'documento_id' => $documento->id,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'error' => 'Ha ocurrido un error con el pdf',
                'fail' => $e->getMessage(),
            ]);
        }

    }

    public function obtenerRespuesta(string $id){
        $respuestas = DocumentChunk::where("id_documento",$id)->get();

        return response()->json($respuestas);
    }
}
