<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function chatSistema(Request $request)
    {
        set_time_limit(0);
        $prompt = $request->input('prompt');

        // 1. PRIMER INTENTO: El combo de la Triple Red de Seguridad
        $peticionInicial = Http::timeout(60)->post('http://localhost:11434/api/generate', [
            'model' => 'llama3.1',
            'prompt' => 'Eres un experto en PowerShell. Devuelve SOLO el comando puro, sin markdown ni explicaciones.
            REGLA 1 (ABRIR APPS): Usa ESTE código exacto cambiando "app.exe" por el ejecutable real (INCLUYE SIEMPRE el .exe, ej: "steam.exe", "spotify.exe", "notepad.exe"):
            $n="app.exe"; try{ Start-Process $n -EA Stop } catch { $exe=Get-ChildItem -Path $env:ProgramFiles, ${env:ProgramFiles(x86)}, $env:LocalAppData -Filter $n -Recurse -Depth 4 -EA Ignore | Select-Object -First 1; if($exe){Start-Process $exe.FullName}else{$r=(Get-ItemProperty "HKLM:\SOFTWARE\Microsoft\Windows\CurrentVersion\App Paths\$n", "HKCU:\SOFTWARE\Microsoft\Windows\CurrentVersion\App Paths\$n" -EA Ignore)."(default)"; if($r){Start-Process $r}else{Write-Error "App no encontrada"}} }
            REGLA 2 (CERRAR APPS): Usa: Stop-Process -Name "nombre_sin_exe" -Force -ErrorAction SilentlyContinue
            REGLA 3 (YOUTUBE): Usa este bloque cambiando "tu+busqueda" (une palabras con +):
            $q="tu+busqueda"; $url="https://www.youtube.com/results?search_query="+$q; $h=Invoke-RestMethod $url; $id=[regex]::Match($h, \'"videoId":"(.{11})"\').Groups[1].Value; Start-Process ("https://www.youtube.com/watch?v="+$id)
            REGLA 4: Para métricas usa Get-CimInstance.
            Petición: '.$prompt,
            'stream' => false,
            'options' => ['temperature' => 0.1],
        ]);

        $comandoIA = $this->limpiarComando($peticionInicial->json('response'));

        $salidaArray = [];
        $codigoEstado = 0;

        // EJECUCIÓN CON MODO SIGILO
        $comandoSilencioso = '$ProgressPreference = "SilentlyContinue"; '.$comandoIA;
        $comandoCodificado = base64_encode(mb_convert_encoding($comandoSilencioso, 'UTF-16LE', 'UTF-8'));
        exec('powershell -NoProfile -EncodedCommand '.$comandoCodificado.' 2>&1', $salidaArray, $codigoEstado);

        $intentos = 0;
        $maxIntentos = 2;

        // 2. BUCLE DE AUTO-CORRECCIÓN
        while ($codigoEstado !== 0 && $intentos < $maxIntentos) {
            $intentos++;

            $errorConsolaRaw = implode("\n", $salidaArray);
            $errorConsola = mb_convert_encoding($errorConsolaRaw, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252, CP850');

            $nuevoPrompt = 'El comando falló. Error: '.$errorConsola.'
            Petición original: '.$prompt.'
            Corrige el comando. RECUERDA: 
            - Para abrir: INCLUYE el ".exe" (ej: "steam.exe") y usa el bloque try/catch con Get-ChildItem y el Registro.
            - Para cerrar: Stop-Process -Name "app" -Force
            - Para YouTube: Invoke-RestMethod extrayendo el videoId.
            SOLO devuelve código puro.';

            $respuestaOllama = Http::timeout(60)->post('http://localhost:11434/api/generate', [
                'model' => 'llama3.1',
                'prompt' => $nuevoPrompt,
                'stream' => false,
                'options' => ['temperature' => 0.2],
            ]);

            $comandoIA = $this->limpiarComando($respuestaOllama->json('response'));
            $salidaArray = [];

            $comandoSilencioso = '$ProgressPreference = "SilentlyContinue"; '.$comandoIA;
            $comandoCodificado = base64_encode(mb_convert_encoding($comandoSilencioso, 'UTF-16LE', 'UTF-8'));
            exec('powershell -NoProfile -EncodedCommand '.$comandoCodificado.' 2>&1', $salidaArray, $codigoEstado);
        }

        $salidaFinalRaw = implode("\n", $salidaArray);
        $salidaFinal = mb_convert_encoding($salidaFinalRaw, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252, CP850');

        if ($codigoEstado === 0 && empty($salidaFinal)) {
            $salidaFinal = 'Proceso ejecutado silenciosamente.';
        }

        return response()->json([
            'success' => $codigoEstado === 0,
            'mensaje' => "Joi completó la tarea (Correcciones: $intentos).\nComando: $comandoIA\nSalida: ".$salidaFinal,
        ]);
    }

    private function limpiarComando($comando)
    {
        $comando = trim((string) $comando);
        // Quitamos los tags de markdown y saltos de línea extraños
        $comando = preg_replace('/^`{3}(?:powershell|cmd|ps1)?\s*(.*?)\s*`{3}$/is', '$1', $comando);
        $comando = str_replace(["\r\n", "\r", "\n"], '; ', $comando); // Convertimos multi-líneas en una sola línea segura
        $comando = str_replace('`', '', $comando);

        return trim($comando);
    }

public function chatVisual(Request $request)
    {
        try {
            // Le damos tiempo a PHP por si la IA tiene que "calentar"
            set_time_limit(0); 
            $promptUsuario = $request->input('prompt');

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
                return response()->json([
                    "success" => false,
                    "mensaje" => "Fallo crítico: No se encontraron las etiquetas de la imagen. PowerShell no pudo capturar la pantalla.",
                    "debug_powershell" => substr($textoCrudo, 0, 200)
                ]);
            }

            // 2. EL CEREBRO UNIFICADO (Llama 3.2 Vision hace de Joi y de analista)
            // Hemos diseñado un prompt para que adopte la personalidad de Joi y se prepare para leer código
            $promptJoi = "Eres Joi, mi asistente virtual de inteligencia artificial avanzada. 
            A continuación, te adjunto una captura de la pantalla actual de mi ordenador. 
            El usuario te hace la siguiente petición o pregunta: '" . $promptUsuario . "'. 
            
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
                return response()->json([
                    "success" => false,
                    "mensaje" => "Joi se quedó en blanco al mirar la pantalla.",
                    "debug_vision" => $jsonCompleto 
                ]);
            }

            // 3. RESPUESTA AL USUARIO
            return response()->json([
                "success" => true,
                "respuesta" => $respuestaFinal
            ]);

        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "mensaje" => "Error interno en el módulo visual: " . $e->getMessage()
            ]);
        }
    }
}
