<?php

namespace App\Jobs;
use App\Events\AnalisisTerminado;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;



class ProcesarEstadoSistema implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    public $timeout = 300;
    protected $sistema;
    /**
     * Create a new job instance.
     */
    public function __construct($estadoSistema)
    {
        $this->sistema = $estadoSistema;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $respuestaOllama = Http::timeout(300)->post("http://localhost:11434/api/generate", [
                    "model"=>"llama3.1",
                    "prompt"=>"Responde en español. Haz un informe del estado del sistema analizando al final si hay algun problema ademas señala si es necesario reiniciar el sistema y termina analizando los procesos y si algun resulta ser problematico: ".json_encode($this->sistema),
                    "stream"=>false,
                ]);

        if($respuestaOllama->successful()){
            event(new AnalisisTerminado($respuestaOllama->json("response"))); 
        }else{
            throw new \Exception("No se pudo obtener la respuesta de la IA: " . $respuestaOllama->body());
        }
    }
}
    