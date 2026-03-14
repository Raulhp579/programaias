<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cerebro IA - Monitor de Sistema</title>
    @vite(['resources/css/app.css'])
    <style>
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 font-sans min-h-screen">

    <nav class="sticky top-0 z-50 glass border-b border-slate-200 px-8 py-4 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <a href="/" class="p-2 hover:bg-slate-100 rounded-lg transition-colors text-slate-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="text-xl font-bold tracking-tight">System<span class="text-indigo-600">Monitor</span></h1>
        </div>
        <div class="flex items-center space-x-4 text-xs font-medium uppercase tracking-widest text-slate-400">
            <span class="flex items-center"><span class="w-2 h-2 bg-indigo-500 rounded-full mr-2 animate-pulse"></span>Live Status</span>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto p-8 space-y-8">
        
        <section>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm relative overflow-hidden group">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 bg-blue-50 rounded-2xl text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                        </div>
                        <span id="val-cpu" class="text-3xl font-black text-slate-800 tracking-tighter">--%</span>
                    </div>
                    <p class="text-sm font-bold text-slate-500 mb-4 uppercase tracking-tighter">Procesador i7</p>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div id="bar-cpu" class="bg-blue-500 h-2 rounded-full transition-all duration-700" style="width: 0%"></div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm group">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 bg-emerald-50 rounded-2xl text-emerald-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                        </div>
                        <span id="val-ram" class="text-3xl font-black text-slate-800 tracking-tighter">-- GB</span>
                    </div>
                    <p class="text-sm font-bold text-slate-500 mb-4 uppercase tracking-tighter">Memoria RAM</p>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div id="bar-ram" class="bg-emerald-500 h-2 rounded-full transition-all duration-700" style="width: 0%"></div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm group">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 bg-purple-50 rounded-2xl text-purple-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <span id="val-gpu" class="text-3xl font-black text-slate-800 tracking-tighter">--%</span>
                    </div>
                    <p class="text-sm font-bold text-slate-500 mb-4 uppercase tracking-tighter">Nvidia RTX 4060</p>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div id="bar-gpu" class="bg-purple-500 h-2 rounded-full transition-all duration-700" style="width: 0%"></div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm group">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 bg-rose-50 rounded-2xl text-rose-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </div>
                        <span id="val-vram" class="text-3xl font-black text-slate-800 tracking-tighter">-- GB</span>
                    </div>
                    <p class="text-sm font-bold text-slate-500 mb-4 uppercase tracking-tighter">Memoria Vídeo</p>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div id="bar-vram" class="bg-rose-500 h-2 rounded-full transition-all duration-700" style="width: 0%"></div>
                    </div>
                </div>

            </div>
        </section>

        <section class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-slate-900 rounded-3xl p-8 text-white shadow-xl shadow-indigo-200/20 relative overflow-hidden">
                    <div class="relative z-10">
                        <h3 class="text-2xl font-bold mb-2">Doctor IA</h3>
                        <p class="text-slate-400 text-sm mb-8 leading-relaxed">
                            Llama 3.1 analizará tus procesos activos para optimizar el rendimiento y detectar tareas innecesarias.
                        </p>
                        
                        <button id="btn-analizar" class="group w-full bg-white text-slate-900 font-bold py-4 rounded-2xl flex items-center justify-center space-x-2 hover:bg-indigo-50 transition-all active:scale-95">
                            <svg class="w-5 h-5 text-indigo-600 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86 .517l-.675.337a4 4 0 01-2.574.344l-3.147-.63a2 2 0 01-1.313-2.628l1.047-3.14a2 2 0 011.313-1.312l3.147-.63a4 4 0 012.574.344l.675.337a6 6 0 003.86.517l2.387-.477a2 2 0 001.022-.547l.633-1.047a2 2 0 012.628-1.313l3.14.63a2 2 0 011.312 1.313l.63 3.14a2 2 0 01-1.313 2.628l-3.14.63a2 2 0 01-2.628-1.313l-.633-1.047z"></path></svg>
                            <span>Escanear Procesos</span>
                        </button>
                    </div>
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-indigo-500/20 rounded-full blur-3xl"></div>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-slate-200">
                    <h4 class="font-bold text-slate-800 mb-4 flex items-center">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                        Estado del sistema
                    </h4>
                    <ul class="space-y-3 text-sm text-slate-500">
                        <li class="flex items-start" id="gpuTemp">
                            <svg class="w-4 h-4 text-slate-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </li>

                        <li class="flex items-start" id="latenciaDisco">
                            <svg class="w-4 h-4 text-slate-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </li>

                        <li class="flex items-start" id="uptime">
                            <svg class="w-4 h-4 text-slate-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </li>
                        
                    </ul>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl border border-slate-200 h-full min-h-[500px] flex flex-col overflow-hidden shadow-sm">
                    <div class="px-8 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <span class="text-sm font-bold text-slate-800">Análisis del Doctor IA</span>
                        <div class="flex space-x-1.5">
                            <div class="w-2.5 h-2.5 rounded-full bg-slate-200"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-slate-200"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-slate-200"></div>
                        </div>
                    </div>
                    <div id="ai-console" class="p-8 flex-1 font-mono text-sm text-slate-600 leading-relaxed overflow-y-auto bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:20px_20px]">
                        <div class="h-full flex flex-col items-center justify-center text-center opacity-40">
                            <svg class="w-12 h-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.675.337a4 4 0 01-2.574.344l-3.147-.63a2 2 0 01-1.313-2.628l1.047-3.14a2 2 0 011.313-1.312l3.147-.63a4 4 0 012.574.344l.675.337a6 6 0 003.86.517l2.387-.477a2 2 0 001.022-.547l.633-1.047a2 2 0 012.628-1.313l3.14.63a2 2 0 011.312 1.313l.63 3.14a2 2 0 01-1.313 2.628l-3.14.63a2 2 0 01-2.628-1.313l-.633-1.047z"></path></svg>
                            <p>Consola lista. Esperando escaneo...</p>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </main>
@vite(['resources/js/app.js', 'resources/js/gestorRecursos.js','resources/js/echo.js'])
</body>
</html>