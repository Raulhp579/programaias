import Echo from 'laravel-echo';

import Pusher from 'pusher-js'
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});


window.Pusher.logToConsole = true;

window.Echo.channel("respuestaIA")
    .listen(".respuest-finalizada", (data) => {
        console.log("Datos recibidos desde Laravel:", data);

        consolaSalida.className = "bg-white p-6 rounded-xl border border-slate-200 flex-1 overflow-y-auto text-left shadow-inner";
        const resumenIA = data.respuesta || "El resumen ha llegado vacío.";

        consolaSalida.innerHTML = `
            <div class="flex items-center space-x-3 mb-5 border-b border-slate-100 pb-3">
                <div class="bg-green-100 text-green-600 p-2 rounded-lg shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Resumen Generado</h3>
                    <p class="text-xs text-slate-500">Procesado por Llama 3.1</p>
                </div>
            </div>
            
            <div class="text-slate-700 text-sm leading-relaxed whitespace-pre-wrap font-sans">
                ${resumenIA}
            </div>
        `;
    });
