const inputArchivo = document.getElementById("pdf-file-input");
const mensajeExito = document.getElementById("mensajeExito");

const btnProcesar = document.getElementById("btnProcesar");
const consolaSalida = document.getElementById("consolaSalida");

let archivo = null;

inputArchivo.addEventListener("change", (e) => {
    archivo = e.target.files[0];
    
    if (archivo && archivo.type === "application/pdf") {
        mensajeExito.classList.remove("hidden");
    } else {
        mensajeExito.classList.add("hidden");
    }
});

btnProcesar.addEventListener("click", ()=>{
    
    if (!archivo) {
        alert("Porfavor introduzca un archivo")
        return;
    }

    consolaSalida.innerHTML = `
    <div class="relative flex justify-center items-center mb-6 mt-4">
        <div class="absolute animate-ping inline-flex h-16 w-16 rounded-full bg-indigo-400 opacity-20"></div>
        <div class="relative inline-flex rounded-full h-16 w-16 bg-indigo-50 flex items-center justify-center border-2 border-indigo-200 text-indigo-600 shadow-sm">
            <svg class="w-8 h-8 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>
    <h3 class="text-xl font-bold text-slate-800 mb-2">La IA está leyendo...</h3>
    <p class="text-slate-500 text-sm max-w-sm leading-relaxed">
        Tu documento está en la cola. <span class="font-semibold text-indigo-600">Llama 3.1</span> está extrayendo y resumiendo la información en segundo plano.
    </p>
`;

    const formData = new FormData();
    formData.append("pdf", archivo);

    fetch("/api/enviarPdf", {
        method: "POST",
        body: formData,
    })
    .then((response) => response.json())
    .then((data) => {
        console.log(data);
    })
    .catch((error) => {
        console.error("Error:", error);
    });
})

