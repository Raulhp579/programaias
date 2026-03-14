document.addEventListener("DOMContentLoaded", () => {
    actualizarEstadoRecursos();
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
