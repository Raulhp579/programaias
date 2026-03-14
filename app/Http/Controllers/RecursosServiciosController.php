<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecursosServiciosController extends Controller
{
    public function obtenerRecursos(){
       $comando = 'powershell -NoProfile -Command "$os = Get-CimInstance Win32_OperatingSystem; $cpu = [math]::Round((Get-CimInstance Win32_Processor | Measure-Object -Property LoadPercentage -Average).Average); $gpuInfo = nvidia-smi --query-gpu=utilization.gpu,memory.used,memory.total --format=csv,noheader,nounits 2>$null; if($gpuInfo -and $gpuInfo.Trim()){$g = $gpuInfo.Split(\',\').Trim()}else{$g = @(0,0,0)}; @{cpu_porcentaje=[int]$cpu; ram_total_gb=[math]::Round($os.TotalVisibleMemorySize/1048576, 2); ram_usada_gb=[math]::Round(($os.TotalVisibleMemorySize-$os.FreePhysicalMemory)/1048576, 2); gpu_porcentaje=[int]$g[0]; vram_usada_gb=[math]::Round([float]$g[1]/1024, 2); vram_total_gb=[math]::Round([float]$g[2]/1024, 2)} | ConvertTo-Json"';

       $resultado = shell_exec($comando);

       $datos = json_decode($resultado, true);

       return response()->json($datos);
    }
}
