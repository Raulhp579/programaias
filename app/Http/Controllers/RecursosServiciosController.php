<?php

namespace App\Http\Controllers;

use App\Jobs\ProcesarEstadoSistema;
use Illuminate\Http\Request;

class RecursosServiciosController extends Controller
{
    public function obtenerRecursos(){
       $comando = 'powershell -NoProfile -Command "$os = Get-CimInstance Win32_OperatingSystem; $cpu = [math]::Round((Get-CimInstance Win32_Processor | Measure-Object -Property LoadPercentage -Average).Average); $gpuInfo = nvidia-smi --query-gpu=utilization.gpu,memory.used,memory.total --format=csv,noheader,nounits 2>$null; if($gpuInfo -and $gpuInfo.Trim()){$g = $gpuInfo.Split(\',\').Trim()}else{$g = @(0,0,0)}; @{cpu_porcentaje=[int]$cpu; ram_total_gb=[math]::Round($os.TotalVisibleMemorySize/1048576, 2); ram_usada_gb=[math]::Round(($os.TotalVisibleMemorySize-$os.FreePhysicalMemory)/1048576, 2); gpu_porcentaje=[int]$g[0]; vram_usada_gb=[math]::Round([float]$g[1]/1024, 2); vram_total_gb=[math]::Round([float]$g[2]/1024, 2)} | ConvertTo-Json"';

       $resultado = shell_exec($comando);

       $datos = json_decode($resultado, true);

       return response()->json($datos);
    }


    public function obtenerEstado(){
        $comando = 'powershell -NoProfile -Command "' .
        '$os = Get-CimInstance Win32_OperatingSystem; ' .
        '$uptime = (Get-Date) - $os.LastBootUpTime; ' .
        '$disk = Get-Counter \'\PhysicalDisk(_Total)\Avg. Disk sec/Transfer\' -ErrorAction SilentlyContinue; ' .
        '$latencia = if($disk){ [math]::Round($disk.CounterSamples[0].CookedValue * 1000, 2) }else{ 0 }; ' .
        '$gpuTemp = nvidia-smi --query-gpu=temperature.gpu --format=csv,noheader,nounits 2>$null; ' .
        '@{ ' .
            'gpu_temp = [int]$gpuTemp; ' .
            'latencia_ms = $latencia; ' .
            'uptime = \'{0}d {1}h {2}m\' -f $uptime.Days, $uptime.Hours, $uptime.Minutes ' .
        '} | ConvertTo-Json"';

        $resultado = shell_exec($comando);
        $datos = json_decode($resultado, true);
    
        return response()->json($datos);
    }


    public function analizarSistema(){
        $comando = 'powershell -NoProfile -Command "' .
        '$os = Get-CimInstance Win32_OperatingSystem; ' .
        '$uptime = (Get-Date) - $os.LastBootUpTime; ' .
        '$disk = Get-Counter \'\PhysicalDisk(_Total)\Avg. Disk sec/Transfer\' -ErrorAction SilentlyContinue; ' .
        '$gpu = nvidia-smi --query-gpu=temperature.gpu,utilization.gpu,memory.used,memory.total --format=csv,noheader,nounits 2>$null; ' .
        '$g = if($gpu){$gpu.Split(\',\').Trim()}else{@(0,0,0,0)}; ' .
        '$procesos = Get-Process | Sort-Object CPU -Descending | Select-Object -First 50 -Property Name, @{Name=\'CPU_s\';Expression={[math]::Round($_.CPU,2)}}, @{Name=\'RAM_MB\';Expression={[math]::Round($_.WorkingSet / 1MB,2)}}; ' .
        '@{ ' .
            'hardware = @{ ' .
                'cpu_uso = [math]::Round((Get-CimInstance Win32_Processor | Measure-Object -Property LoadPercentage -Average).Average); ' .
                'ram_usada = [math]::Round(($os.TotalVisibleMemorySize - $os.FreePhysicalMemory)/1048576, 2); ' .
                'ram_total = [math]::Round($os.TotalVisibleMemorySize/1048576, 2); ' .
                'gpu_temp = [int]$g[0]; ' .
                'gpu_uso = [int]$g[1]; ' .
                'vram_usada = [math]::Round([float]$g[2]/1024, 2); ' .
                'disco_ms = if($disk){[math]::Round($disk.CounterSamples[0].CookedValue * 1000, 2)}else{0}; ' .
                'uptime = \'{0}d {1}h {2}m\' -f $uptime.Days, $uptime.Hours, $uptime.Minutes ' .
            '}; ' .
            'top_procesos = $procesos ' .
        '} | ConvertTo-Json"';

        $resultado = shell_exec($comando);
        $datos = json_decode($resultado, true);

        ProcesarEstadoSistema::dispatch($datos);    
        
        return response()->json([
            "success"=>"el sistema esta siendo analizado por el agente de IA"
        ]);
    }
}
