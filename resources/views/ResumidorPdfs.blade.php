<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumidor de PDFs</title>
    <style>
        .loader { border-top-color: #4f46e5; -webkit-animation: spinner 1.5s linear infinite; animation: spinner 1.5s linear infinite; }
        @keyframes spinner { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen">

    <nav class="bg-white shadow-sm px-8 py-4 mb-8 flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
            <h1 class="text-2xl font-bold text-slate-800">Resumidor de PDFs</h1>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-8 grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <div class="md:col-span-1">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h2 class="text-lg font-semibold mb-4">Ingestar Conocimiento</h2>
                <p class="text-sm text-slate-500 mb-6">Sube un PDF para que la IA local (Llama 3.1) lo lea y extraiga sus resúmenes.</p>

                <div id="pdfUploadContainer">
                    <div class="flex items-center justify-center w-full mb-4">
                        <label for="pdf-file-input" class="flex flex-col items-center justify-center w-full h-48 border-2 border-slate-300 border-dashed rounded-xl cursor-pointer bg-slate-50 hover:bg-slate-100 transition-colors">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                                <svg class="w-10 h-10 mb-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                <p class="mb-2 text-sm text-slate-500"><span class="font-semibold">Haz clic para subir</span> o arrastra el archivo</p>
                                <p class="text-xs text-slate-500">Solo archivos PDF</p>
                            </div>
                            <input id="pdf-file-input" type="file" accept=".pdf" class="hidden" />
                        </label>
                    </div>

                    <p id="fileNameDisplay" class="text-sm text-indigo-600 font-medium mb-4 text-center hidden"></p>

                    <button type="button" id="btnProcesar" class="w-full text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 font-medium rounded-xl text-sm px-5 py-3 text-center transition-colors">
                        Procesar Documento
                    </button>
                    <p id="mensajeExito" class="text-sm text-green-600 font-medium mt-4 text-center hidden">pdf añadido correctamente</p>
                </div>
            </div>
        </div>

        <div class="md:col-span-2">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 min-h-[400px] flex flex-col">
                <h2 class="text-lg font-semibold mb-4 border-b border-slate-100 pb-2">Consola de la IA</h2>
                
                <div id="consolaSalida" class="bg-slate-50 p-6 rounded-xl border border-slate-200 flex-1 flex flex-col justify-center items-center text-center overflow-y-auto">
                    <svg class="w-16 h-16 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <p class="text-slate-500">Esperando documentos para analizar...</p>
                </div>
            </div>
        </div>
    </div>

    @vite([ 'resources/js/lectorArchivo.js', 'resources/js/echo.js'])
</body>
</html>