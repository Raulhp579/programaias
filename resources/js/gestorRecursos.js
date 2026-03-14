document.addEventListener("DOMContentLoaded", () => {
    actualizarEstadoRecursos();
    obtenerEstado();
});


const actualizarEstadoRecursos = async () => {
    const response = await fetch("/api/obtenerRecursos");
    const data = await response.json();

    // Función ultra-corta para el color
    const color = (p) => p > 85 ? 'bg-rose-500' : (p > 60 ? 'bg-amber-400' : '');

    // 1. CPU
    const cpu = Math.round(data.cpu_porcentaje);
    document.getElementById("val-cpu").innerText = cpu + "%";
    document.getElementById("bar-cpu").style.width = cpu + "%";
    document.getElementById("bar-cpu").className = `h-2 rounded-full transition-all duration-700 bg-blue-500 ${color(cpu)}`;

    // 2. RAM 
    const pRam = Math.round((data.ram_usada_gb / data.ram_total_gb) * 100);
    document.getElementById("val-ram").innerText = data.ram_usada_gb + " GB";
    document.getElementById("bar-ram").style.width = pRam + "%";
    document.getElementById("bar-ram").className = `h-2 rounded-full transition-all duration-700 bg-emerald-500 ${color(pRam)}`;

    // 3. GPU
    const gpu = data.gpu_porcentaje;
    document.getElementById("val-gpu").innerText = gpu + "%";
    document.getElementById("bar-gpu").style.width = gpu + "%";
    document.getElementById("bar-gpu").className = `h-2 rounded-full transition-all duration-700 bg-purple-500 ${color(gpu)}`;

    // 4. VRAM
    const pVram = Math.round((data.vram_usada_gb / data.vram_total_gb) * 100);
    document.getElementById("val-vram").innerText = data.vram_usada_gb + " GB";
    document.getElementById("bar-vram").style.width = pVram + "%";
    document.getElementById("bar-vram").className = `h-2 rounded-full transition-all duration-700 bg-rose-500 ${color(pVram)}`;
};


setInterval(actualizarEstadoRecursos, 2000);


const obtenerEstado = async () => {
    try {
        
        const response = await fetch("/api/estadoPc");
        const data = await response.json();

        const elGpuTemp = document.getElementById("gpuTemp");
        elGpuTemp.innerText = "La temperatura de la GPU es " + data.gpu_temp + "°C";
        elGpuTemp.style.color = data.gpu_temp > 85 ? "red" : (data.gpu_temp > 70 ? "orange" : "green");

        const elLatencia = document.getElementById("latenciaDisco");
        elLatencia.innerText = "La latencia del disco es " + data.latencia_ms + "ms";
        elLatencia.style.color = data.latencia_ms > 20 ? "red" : (data.latencia_ms > 10 ? "orange" : "green");

        const elUptime = document.getElementById("uptime");
        elUptime.innerText = "El PC lleva encendido " + data.uptime;
        
        const dias = parseInt(data.uptime) || 0; 
        
        if (dias >= 7) {
            elUptime.style.color = "red";    
        } else if (dias >= 2) {
            elUptime.style.color = "orange"; 
        } else {
            elUptime.style.color = "green";  
        }

    } catch (error) {
        console.error("Error al obtener los datos del sistema:", error);
    }
};

setInterval(obtenerEstado, 2000);

const btnAnalizar = document.querySelector("#btn-analizar")


btnAnalizar.addEventListener("click", async () => {
    const consola = document.getElementById('ai-console');
    const btn = document.getElementById('btn-analizar');

    const originalContent = btn.innerHTML; 
    btn.disabled = true;
    btn.classList.add('opacity-75', 'cursor-not-allowed');
    btn.innerHTML = `
        <svg class="animate-spin h-5 w-5 text-indigo-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>Analizando...</span>
    `;

    consola.innerHTML = `
        <div class="h-full flex flex-col items-center justify-center text-center space-y-4">
            <div class="relative">
                <div class="w-16 h-16 border-4 border-indigo-100 border-t-indigo-600 rounded-full animate-spin"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
            <div class="space-y-2">
                <p class="text-indigo-600 font-bold animate-pulse">Doctor IA está examinando el sistema</p>
                <p class="text-slate-400 text-xs uppercase tracking-widest">Leyendo 50 procesos activos...</p>
            </div>
        </div>
    `;

    try {
        const response = await fetch("/api/analizarSistema");
        const data = await response.json();
        console.log("Job despachado:", data.success);

        
    } catch (error) {
        console.error("Error al lanzar análisis:", error);
        btn.disabled = false;
        btn.classList.remove('opacity-75', 'cursor-not-allowed');
        btn.innerHTML = originalContent;
        consola.innerHTML = '<p class="text-red-500 font-mono">>>> ERROR: No se pudo conectar con el agente de IA.</p>';
    }
});



