document.addEventListener("DOMContentLoaded", async () => {
    const form = document.getElementById("form-visita");
    const selectNomVisita = document.getElementById("nombre_visita");
    const selectAspectos = document.getElementById("aspectos");
    const btnAgregarAspecto = document.getElementById("btn-agregar-aspecto");
    const listaAspectos = document.getElementById("lista-aspectos");
    let aspectosSeleccionados = [];

    // ============================
    // Cargar visitas desde backend
    // ============================
    async function cargarNomVisita() {
        if (!selectNomVisita) return console.error("No se encontró el select nombre_visita");
        try {
            const res = await fetch("http://localhost/VisitasSuper/backend/index.php?controller=NomVisita&action=index");
            const data = await res.json();
            selectNomVisita.innerHTML = "<option value=''>Seleccione una visita</option>";

            data.forEach(v => {
                const option = document.createElement("option");
                option.value = v.nombre;  // 👈 usar el nombre directamente
                option.textContent = v.nombre;
                selectNomVisita.appendChild(option);
            });
        } catch (error) {
            console.error("Error cargando visitas:", error);
        }
    }

    // ============================
    // Cargar aspectos desde backend
    // ============================
    async function cargarAspectos() {
        if (!selectAspectos) return console.error("No se encontró el select aspectos");
        try {
            const res = await fetch("http://localhost/VisitasSuper/backend/index.php?controller=Aspecto&action=index");
            const data = await res.json();
            selectAspectos.innerHTML = "<option value=''>Seleccione un aspecto</option>";

            data.forEach(a => {
                const option = document.createElement("option");
                option.value = a.id;
                option.textContent = a.descripcion;
                selectAspectos.appendChild(option);
            });
        } catch (error) {
            console.error("Error cargando aspectos:", error);
        }
    }

    // ============================
    // Agregar aspecto a la lista
    // ============================
    if (btnAgregarAspecto) {
        btnAgregarAspecto.addEventListener("click", () => {
            const selectedId = selectAspectos.value;
            const selectedText = selectAspectos.options[selectAspectos.selectedIndex]?.text;

            if (!selectedId) return;

            if (!aspectosSeleccionados.find(a => a.id === selectedId)) {
                aspectosSeleccionados.push({ id: selectedId, descripcion: selectedText });

                const li = document.createElement("li");
                li.textContent = selectedText;
                listaAspectos.appendChild(li);
            }
        });
    }

    // ============================
    // Guardar visita
    // ============================
    if (form) {
        form.addEventListener("submit", async (e) => {
            e.preventDefault();

            const nomvisita = selectNomVisita.value; // 👈 ahora solo nombre
            const fecha_inicio = document.getElementById("fecha_inicio").value;
            const fecha_fin = document.getElementById("fecha_fin").value;
            const obs_adicionales = document.getElementById("obs_adicionales").value;

            if (!nomvisita) {
                alert("Seleccione una visita antes de continuar");
                return;
            }

            try {
                // 1️⃣ Crear la visita
                const visitaResponse = await fetch("http://localhost/VisitasSuper/backend/index.php?controller=NomVisita&action=store", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({
                        nombre: nomvisita, // 👈 enviar nombre directamente
                        fecha_inicio,
                        fecha_fin,
                        obs_adicionales
                    })
                });

                const visitaData = await visitaResponse.json();
                console.log("Visita creada:", visitaData);

                if (visitaData.nombre) { // 👈 ya no hay id
                    // 2️⃣ Guardar aspectos asociados
                    for (let asp of aspectosSeleccionados) {
                        await fetch("http://localhost/VisitasSuper/backend/index.php?controller=Aspecto&action=store", {
                            method: "POST",
                            headers: { "Content-Type": "application/json" },
                            body: JSON.stringify({
                                visita_nombre: visitaData.nombre, // usar nombre para relacionar
                                aspecto_id: asp.id
                            })
                        });
                    }

                    alert("Visita registrada con éxito ✅");
                    form.reset();
                    listaAspectos.innerHTML = "";
                    aspectosSeleccionados = [];
                } else {
                    alert("Error al guardar la visita ❌");
                }
            } catch (error) {
                console.error("Error guardando visita:", error);
                alert("Ocurrió un error al guardar la visita");
            }
        });
    }

    // ============================
    // Inicializar selects
    // ============================
    await cargarNomVisita();
    await cargarAspectos();
});
