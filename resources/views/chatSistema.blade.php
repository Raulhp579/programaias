<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Joi - Agente de Sistema</title>
    @vite(['resources/css/app.css'])
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
            <div id="chat-messages-container" class="max-w-3xl mx-auto space-y-10">




                <div class="h-6"></div>
            </div>
        </div>

        <div class="flex-shrink-0 bg-gradient-to-t from-slate-50 via-slate-50 to-transparent pt-8 pb-6 px-4">
            <div class="max-w-3xl mx-auto relative">
                
                <div id="mode-selector" class="flex gap-4 mb-3 justify-center">
                    <label class="flex items-center space-x-2 cursor-pointer text-sm text-slate-600 hover:text-indigo-600 transition-colors bg-white px-4 py-2 rounded-full border border-slate-200 shadow-sm">
                        <input type="radio" name="chatMode" value="sistema" class="text-indigo-600 focus:ring-indigo-500 accent-indigo-600" checked>
                        <span class="font-medium">💻 Modo Sistema (Comandos)</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer text-sm text-slate-600 hover:text-indigo-600 transition-colors bg-white px-4 py-2 rounded-full border border-slate-200 shadow-sm">
                        <input type="radio" name="chatMode" value="visual" class="text-indigo-600 focus:ring-indigo-500 accent-indigo-600">
                        <span class="font-medium">👁️ Modo Visual (Ver Pantalla)</span>
                    </label>
                </div>

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
        </div>
    </main>

    @vite(['resources/js/app.js', 'resources/js/echo.js', 'resources/js/chatSistema.js'])
</body>
</html>