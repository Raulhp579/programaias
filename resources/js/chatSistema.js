document.addEventListener("DOMContentLoaded", () => {
    const chatInput = document.getElementById("chat-input");
    const btnSend = document.getElementById("btn-send");
    const chatMessages = document.getElementById("chat-messages-container");
    const modeSelector = document.getElementById("mode-selector"); // A switch or dropdown
    
    // We can export a global function so echo.js can call it when the Visual Job finishes
    window.appendJoiMessage = function(mensaje, isHtml = false) {
        // Find if there's a loading message to remove
        const loadingMsg = document.getElementById("joi-loading");
        if (loadingMsg) {
            loadingMsg.remove();
        }

        const msgDiv = document.createElement("div");
        msgDiv.className = "flex items-start space-x-4 animate-fade-in";
        msgDiv.innerHTML = `
            <div class="w-10 h-10 rounded-full bg-indigo-600 flex-shrink-0 flex items-center justify-center text-white shadow-md border-2 border-white mt-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <div class="flex-1">
                <div class="text-sm font-bold text-slate-800 mb-1">Joi</div>
                <div class="prose prose-slate prose-sm max-w-none text-slate-700 leading-relaxed bg-white p-5 rounded-2xl rounded-tl-none shadow-sm border border-slate-200">
                    ${isHtml ? mensaje : `<p>${mensaje.replace(/\\n/g, '<br>')}</p>`}
                </div>
            </div>
        `;
        chatMessages.appendChild(msgDiv);
        scrollToBottom();
    };

    window.appendJoiLoading = function() {
        const msgDiv = document.createElement("div");
        msgDiv.id = "joi-loading";
        msgDiv.className = "flex items-start space-x-4 animate-fade-in";
        msgDiv.innerHTML = `
            <div class="w-10 h-10 rounded-full bg-indigo-600 flex-shrink-0 flex items-center justify-center text-white shadow-md border-2 border-white mt-1">
                <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <div class="flex-1">
                <div class="text-sm font-bold text-slate-800 mb-1">Joi</div>
                <div class="prose prose-slate prose-sm max-w-none text-slate-700 leading-relaxed bg-white p-5 rounded-2xl rounded-tl-none shadow-sm border border-slate-200">
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-indigo-400 rounded-full animate-bounce" style="animation-delay: 0s"></div>
                        <div class="w-2 h-2 bg-indigo-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        <div class="w-2 h-2 bg-indigo-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                    </div>
                </div>
            </div>
        `;
        chatMessages.appendChild(msgDiv);
        scrollToBottom();
    }

    function appendUserMessage(text) {
        const msgDiv = document.createElement("div");
        msgDiv.className = "flex items-start space-x-4 animate-fade-in";
        msgDiv.innerHTML = `
            <div class="w-10 h-10 rounded-full bg-slate-200 flex-shrink-0 flex items-center justify-center text-slate-600 mt-1 border border-slate-300">
                <span class="font-bold text-sm">Tú</span>
            </div>
            <div class="flex-1">
                <div class="text-sm font-bold text-slate-800 mb-1">Tú</div>
                <div class="prose prose-slate prose-sm max-w-none text-slate-700 leading-relaxed bg-slate-100 p-5 rounded-2xl rounded-tl-none border border-slate-200">
                    <p>${text.replace(/\\n/g, '<br>')}</p>
                </div>
            </div>
        `;
        chatMessages.appendChild(msgDiv);
        scrollToBottom();
    }

    function scrollToBottom() {
        const container = document.getElementById("chat-messages");
        container.scrollTop = container.scrollHeight;
    }

    async function sendMessage() {
        const text = chatInput.value.trim();
        if (!text) return;

        appendUserMessage(text);
        chatInput.value = "";
        btnSend.disabled = true;

        // Get the selected mode
        const mode = document.querySelector('input[name="chatMode"]:checked').value;
        const endpoint = mode === "visual" ? "/api/chatVisual" : "/api/chatSistema";

        appendJoiLoading();

        try {
            const response = await fetch(`${endpoint}?prompt=${encodeURIComponent(text)}`);
            const data = await response.json();

            if (mode === "sistema") {
                // chatSistema resolves synchronously (or at least returns the final output)
                if (data.success) {
                    let formattedResponse = `
                    <div class="bg-slate-900 rounded-xl p-4 my-2 font-mono text-sm text-emerald-400 border border-slate-800 shadow-lg overflow-x-auto">
                        <div class="flex items-center text-slate-500 mb-2 border-b border-slate-700 pb-2 text-xs uppercase tracking-widest">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M4 18h16a2 2 0 002-2V6a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span>Ejecución del Sistema</span>
                        </div>
                        <pre class="whitespace-pre-wrap text-slate-300">${data.mensaje}</pre>
                    </div>`;
                    window.appendJoiMessage(formattedResponse, true);
                } else {
                    window.appendJoiMessage(`<span class="text-red-500">Error: ${data.mensaje}</span>`, true);
                }
                btnSend.disabled = false;
            } else {
                // chatVisual returns a pending message ("Procesando en segundo plano...")
                // The actual response will come via echo.js
                // So we leave the loading indicator and wait for Echo.
                // Re-enable send button? We can leave it disabled until we get the response, or enable it and handle overlapping jobs. Let's enable it.
                btnSend.disabled = false;
            }

        } catch (error) {
            console.error("Error sending message", error);
            window.appendJoiMessage(`<span class="text-red-500">Fallo de red al contactar con Joi.</span>`, true);
            btnSend.disabled = false;
        }
    }

    btnSend.addEventListener("click", sendMessage);

    chatInput.addEventListener("keypress", (e) => {
        if (e.key === "Enter" && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });
});
