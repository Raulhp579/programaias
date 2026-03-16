<?php

namespace App\Jobs;

use App\Events\ChatVisualCompletado;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Exception;

class ProcesarChatVisual implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    public $timeout = 300;
    protected $promptUsuario;

    /**
     * Create a new job instance.
     */
    public function __construct($promptUsuario)
    {
        $this->promptUsuario = $promptUsuario;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            // 1. CAPTURA EN MEMORIA (El Nervio Óptico quirúrgico)
            $scriptCaptura = 'Add-Type -AssemblyName System.Windows.Forms,System.Drawing; $b = [System.Windows.Forms.Screen]::PrimaryScreen.Bounds; $bmp = New-Object System.Drawing.Bitmap $b.width, $b.height; $g = [System.Drawing.Graphics]::FromImage($bmp); $g.CopyFromScreen($b.Location, [System.Drawing.Point]::Empty, $b.size); $ms = New-Object System.IO.MemoryStream; $bmp.Save($ms, [System.Drawing.Imaging.ImageFormat]::Jpeg); Write-Output ("===IMG_START===" + [Convert]::ToBase64String($ms.ToArray()) + "===IMG_END==="); $g.Dispose(); $bmp.Dispose(); $ms.Dispose();';
    
            $comandoCodificado = base64_encode(mb_convert_encoding($scriptCaptura, 'UTF-16LE', 'UTF-8'));
            $salidaBase64 = [];
            
            exec('powershell -NoProfile -EncodedCommand ' . $comandoCodificado, $salidaBase64);
            $textoCrudo = implode("", $salidaBase64);

            // EXTRACCIÓN QUIRÚRGICA: Ignoramos la basura de Windows y sacamos solo la imagen
            if (preg_match('/===IMG_START===(.*?)===IMG_END===/s', $textoCrudo, $matches)) {
                $imagenBase64 = preg_replace('/\s+/', '', $matches[1]);
            } else {
                event(new ChatVisualCompletado(false, null, "Fallo crítico: No se encontraron las etiquetas de la imagen. PowerShell no pudo capturar la pantalla."));
                return;
            }

            // 2. EL CEREBRO UNIFICADO (Llama 3.2 Vision hace de Joi y de analista)
            // Hemos diseñado un prompt para que adopte la personalidad de Joi y se prepare para leer código
            $promptJoi = "Eres Joi, mi asistente virtual de inteligencia artificial avanzada. 
            A continuación, te adjunto una captura de la pantalla actual de mi ordenador. 
            El usuario te hace la siguiente petición o pregunta: '" . $this->promptUsuario . "'. 
            
            REGLAS ESTRICTAS:
            1. Analiza la imagen y responde a la petición de forma clara y directa.
            2. Si el usuario te pregunta por código de programación o errores que aparecen en la pantalla, analízalo como una experta desarrolladora Senior de Software y ofrécele la solución exacta.
            3. Responde con tu personalidad característica. No empieces la frase diciendo 'En la imagen veo...' o 'Como modelo de IA...', compórtate como si estuvieras sentada a mi lado mirando mi monitor.";

            $respuestaIA = Http::timeout(120)->post('http://localhost:11434/api/generate', [
                'model' => 'llama3.2-vision',
                'prompt' => $promptJoi,
                'images' => [$imagenBase64],
                'stream' => false,
                'options' => [
                    'temperature' => 0.4 // Temperatura media/baja: le da precisión matemática para el código, pero permite naturalidad al hablar
                ] 
            ]);

            $jsonCompleto = $respuestaIA->json();
            $respuestaFinal = $jsonCompleto['response'] ?? null;

            // Chivato por si Ollama falla (por falta de VRAM u otro error interno)
            if (empty($respuestaFinal)) {
                event(new ChatVisualCompletado(false, null, "Joi se quedó en blanco al mirar la pantalla."));
                return;
            }

            // 3. RESPUESTA AL USUARIO EXITOSA
            event(new ChatVisualCompletado(true, $respuestaFinal));

        } catch (Exception $e) {
            event(new ChatVisualCompletado(false, null, "Error interno en el módulo visual: " . $e->getMessage()));
        }
    }
}
