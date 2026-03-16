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
            $promptUsuario = $request->input('prompt');

            // Dispatch job to the queue
            \App\Jobs\ProcesarChatVisual::dispatch($promptUsuario);

            // Return early
            return response()->json([
                "success" => true,
                "mensaje" => "Procesando la visión en segundo plano..."
            ]);

        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "mensaje" => "Error al iniciar el módulo visual: " . $e->getMessage()
            ]);
        }
    }
}
