<?php

namespace App\Jobs;

use App\Events\RespuestaFinalizada;
use App\Models\DocumentChunk;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Smalot\PdfParser\Parser;

class ProcesarResumenPdf implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    public $timeOut = 300;
    protected $documento;
    protected $rutaAbsoluta;


    /**
     * Create a new job instance.
     */
    public function __construct($documento, $rutaAbsoluta)
    {
        $this->documento = $documento;
        $this->rutaAbsoluta = $rutaAbsoluta;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $parser = new Parser();

        $pdf = $parser->parseFile($this->rutaAbsoluta);
        $paginas = $pdf->getPages();

        $respuesta = [];

            $numPag =0;
            foreach ($paginas as $pagina) {
                $numPag++;
                
                $chunk = DocumentChunk::create([
                    "id_documento"=>$this->documento->id,
                    "num_pag"=>$numPag,
                    "contenido"=>$pagina->getText(),
                    "respuesta"=>null
                ]);

                $respuestaOllama = Http::timeout(300)->post("http://localhost:11434/api/generate", [
                    "model"=>"llama3.1",
                    "prompt"=>"Responde en español. Extrae la información del siguiente texto y hazme un resumen destacando las partes importantes: ".$pagina->getText(),
                    "stream"=>false,
                ]);

                if($respuestaOllama->successful()){
                    $chunk->update([
                        "respuesta"=>$respuestaOllama->json("response")
                    ]);
                }

                $respuesta[] = $chunk->respuesta;
            }

            event(new RespuestaFinalizada($respuesta));
    }
}
