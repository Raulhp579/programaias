<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Joi - Agente de Sistema</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Scrollbar elegante para el chat */
        #chat-messages::-webkit-scrollbar {
            width: 8px;
        }
        #chat-messages::-webkit-scrollbar-track {
            background: transparent;
        }
        #chat-messages::-webkit-scrollbar-thumb {
            background-color: #e2e8f0;
            border-radius: 20px;
        }
        #chat-messages::-webkit-scrollbar-thumb:hover {
            background-color: #cbd5e1;
        }
    </style>
</head>
<body class="bg-white h-screen flex overflow-hidden font-sans text-slate-900">

    <main class="flex-1 flex flex-col h-full bg-slate-50/50 relative">
        
        <header class="h-16 border-b border-slate-200 bg-white/80 backdrop-blur-md flex items-center justify-between px-8 flex-shrink-0 sticky top-0 z-10">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center border border-indigo-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                </div>
                <h1 class="text-xl font-bold text-slate-800 tracking-tight">J.O.I. <span class="text-slate-400 font-medium text-sm ml-2 hidden sm:inline-block">System Intelligence</span></h1>
            </div>
            
            <div class="flex items-center space-x-2 text-xs font-bold uppercase tracking-widest text-indigo-600 bg-indigo-50 px-4 py-2 rounded-full border border-indigo-100 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                <span>En línea</span>
            </div>
        </header>

        <div id="chat-messages" class="flex-1 overflow-y-auto px-4 py-8 w-full">
            <div class="max-w-3xl mx-auto space-y-10"> <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 rounded-full bg-indigo-600 flex-shrink-0 flex items-center justify-center text-white shadow-md border-2 border-white mt-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div class="flex-1">
                        <div class="text-sm font-bold text-slate-800 mb-1">Joi</div>
                        <div class="prose prose-slate prose-sm max-w-none text-slate-700 leading-relaxed bg-white p-5 rounded-2xl rounded-tl-none shadow-sm border border-slate-200">
                            <p>Hola. He inicializado los protocolos de diagnóstico. Tu sistema lleva encendido <strong>1d 4h 12m</strong> y todos los componentes operan dentro de los parámetros normales.</p>
                            <p>Tengo acceso a la consola de PowerShell. ¿Qué necesitas que analice o ejecute por ti hoy?</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 rounded-full bg-slate-200 flex-shrink-0 flex items-center justify-center text-slate-600 mt-1 border border-slate-300">
                        <span class="font-bold text-sm">Tú</span>
                    </div>
                    <div class="flex-1">
                        <div class="text-sm font-bold text-slate-800 mb-1">Tú</div>
                        <div class="prose prose-slate prose-sm max-w-none text-slate-700 leading-relaxed bg-slate-100 p-5 rounded-2xl rounded-tl-none border border-slate-200">
                            <p>Joi, abre Steam y comprueba si hay algún proceso consumiendo mucha memoria RAM.</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 rounded-full bg-indigo-600 flex-shrink-0 flex items-center justify-center text-white shadow-md border-2 border-white mt-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div class="flex-1">
                        <div class="text-sm font-bold text-slate-800 mb-1">Joi</div>
                        <div class="prose prose-slate prose-sm max-w-none text-slate-700 leading-relaxed bg-white p-5 rounded-2xl rounded-tl-none shadow-sm border border-slate-200">
                            <p>Enseguida. Ejecutando la orden en el sistema host:</p>
                            
                            <div class="bg-slate-900 rounded-xl p-4 my-4 font-mono text-sm text-emerald-400 border border-slate-800 shadow-lg">
                                <div class="flex items-center text-slate-500 mb-2 border-b border-slate-700 pb-2 text-xs uppercase tracking-widest">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M4 18h16a2 2 0 002-2V6a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    <span>PowerShell Engine</span>
                                </div>
                                <p class="text-slate-300">> Start-Process 'C:\Program Files (x86)\Steam\steam.exe'</p>
                                <p class="text-slate-300">> Get-Process | Sort-Object WorkingSet -Descending | Select-Object -First 3</p>
                            </div>

                            <p><strong>Hecho.</strong> Steam se está abriendo. En cuanto a la RAM, el proceso más pesado ahora mismo es <code>vmmem</code> consumiendo 4.2 GB. Tienes 58 GB libres, así que tu i7 tiene recursos de sobra para jugar.</p>
                        </div>
                    </div>
                </div>

                <div class="h-6"></div>
            </div>
        </div>

        <div class="flex-shrink-0 bg-gradient-to-t from-slate-50 via-slate-50 to-transparent pt-8 pb-6 px-4">
            <div class="max-w-3xl mx-auto relative">
                
                <div class="relative flex items-end shadow-lg rounded-2xl bg-white border border-slate-300 overflow-hidden focus-within:ring-2 focus-within:ring-indigo-500 transition-all focus-within:border-indigo-500">
                    
                    <button class="p-4 text-slate-400 hover:text-indigo-600 transition-colors h-14 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                    </button>

                    <textarea id="chat-input" rows="1" 
                        class="w-full max-h-48 py-4 px-2 bg-transparent border-none focus:ring-0 text-slate-800 placeholder-slate-400 resize-none outline-none leading-relaxed" 
                        placeholder="Pídele a Joi que analice algo o ejecute un comando..."></textarea>

                    <div class="p-2 h-14 flex items-center justify-center">
                        <button id="btn-send" class="bg-indigo-600 hover:bg-indigo-700 text-white p-2.5 rounded-xl transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </button>
                    </div>
                </div>

                <div class="text-center mt-3 text-xs text-slate-400 font-medium">
                    Joi procesa la información localmente mediante Llama 3.1. Revisa los comandos antes de ejecutarlos.
                </div>
            </div>
        </div>
    </main>

</body>
</html>