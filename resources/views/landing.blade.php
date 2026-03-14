<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cerebro IA - Panel de Control</title>
    @vite(['resources/css/app.css'])
    <style>
        /* Pequeña animación para que las tarjetas floten al pasar el ratón */
        .hover-float { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .hover-float:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen flex flex-col">

    <nav class="bg-white shadow-sm px-8 py-4 flex items-center justify-between border-b border-slate-200">
        <div class="flex items-center space-x-2">
            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            <h1 class="text-2xl font-bold text-slate-800">Cerebro<span class="text-indigo-600">IA</span></h1>
        </div>
        <div class="text-sm font-medium text-slate-500 bg-slate-100 px-3 py-1 rounded-full flex items-center space-x-2">
            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
            <span>Motor Local Activo</span>
        </div>
    </nav>

    <header class="max-w-7xl mx-auto px-8 py-16 text-center">
        <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight text-slate-900 mb-4">
            Tu centro de mando con <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Inteligencia Artificial</span>
        </h2>
        <p class="text-lg text-slate-500 max-w-2xl mx-auto">
            Procesa documentos, monitoriza tu hardware y chatea con tus datos de forma 100% privada usando tu propia RTX 4060 y Llama 3.1.
        </p>
    </header>

    <main class="max-w-7xl mx-auto px-8 pb-20 flex-1 w-full">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            <a href={{ route('gestorRecursos') }} class="bg-white rounded-2xl p-6 border border-slate-200 hover-float block group cursor-pointer">
                <div class="w-14 h-14 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Monitor de PC</h3>
                <p class="text-slate-500 text-sm mb-4">Visualiza en tiempo real el consumo de CPU, RAM y tu GPU Nvidia con barras de progreso dinámicas.</p>
                <span class="text-blue-600 font-semibold text-sm flex items-center">Abrir Monitor <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></span>
            </a>

            <a href={{ route('resumirPdf') }} class="bg-white rounded-2xl p-6 border border-slate-200 hover-float block group cursor-pointer">
                <div class="w-14 h-14 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center mb-6 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Ingestor de PDFs</h3>
                <p class="text-slate-500 text-sm mb-4">Sube tus documentos para que la IA los procese en segundo plano y extraiga los puntos clave automáticamente.</p>
                <span class="text-purple-600 font-semibold text-sm flex items-center">Subir Documentos <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></span>
            </a>

            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200 block relative overflow-hidden">
                <div class="absolute top-4 right-4 bg-slate-200 text-slate-600 text-xs font-bold px-2 py-1 rounded-md uppercase tracking-wider">Próximamente</div>
                <div class="w-14 h-14 rounded-xl bg-slate-200 text-slate-400 flex items-center justify-center mb-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-400 mb-2">Chat Inteligente</h3>
                <p class="text-slate-400 text-sm mb-4">Haz preguntas en lenguaje natural sobre todos los documentos que has guardado en tu base de datos.</p>
                <span class="text-slate-400 font-semibold text-sm flex items-center">En desarrollo...</span>
            </div>

        </div>
    </main>

    @vite(['resources/js/app.js'])
</body>
</html>