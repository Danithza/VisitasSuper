document.addEventListener('DOMContentLoaded', async () => {
    const API_URL = "http://localhost/VisitasSuper/backend/index.php";
    const tableBody = document.getElementById("visitasTableBody");
    const aspectoFilter = document.getElementById("aspectoFilter");

    // 🔹 Cargar visitas
    async function loadVisitas() {
        try {
            const res = await fetch(`${API_URL}?controller=visitas&action=index`);
            const data = await res.json();

            tableBody.innerHTML = "";

            if (data.length === 0) {
                tableBody.innerHTML = `<tr><td colspan="12" class="text-center">No hay visitas registradas</td></tr>`;
                return;
            }

            data.forEach(v => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${v.fecha_inicio} - ${v.fecha_fin || "-"}</td>
                    <td>${v.nombre_visita}</td>
                    <td>${v.aspecto || "N/A"}</td>
                    <td>${v.descripcion || "-"}</td>
                    <td>${v.observacion || "-"}</td>
                    <td><span class="badge ${getEstadoClass(v.estado)} badge-status">${v.estado}</span></td>
                    <td>${v.plazo || "-"}</td>
                    <td>${v.recurrente || "-"}</td>
                    <td>${v.actividad || "-"}</td>
                    <td>${v.responsable || "-"}</td>
                    <td>${v.evidencia ? `<img src="${v.evidencia}" class="evidence-preview">` : "<span class='text-muted'>Sin evidencia</span>"}</td>
                    <td class="action-buttons">
                        <button class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i></button>
                        <button class="btn btn-sm evidence-btn"><i class="fas fa-upload"></i></button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        } catch (error) {
            console.error("Error cargando visitas:", error);
            tableBody.innerHTML = `<tr><td colspan="12" class="text-center text-danger">Error cargando datos</td></tr>`;
        }
    }

    // 🔹 Cargar aspectos en el filtro
    async function loadAspectos() {
        try {
            const res = await fetch(`${API_URL}?controller=Aspecto&action=index`);
            const data = await res.json();

            data.forEach(a => {
                const option = document.createElement("option");
                option.value = a.descripcion.toLowerCase();
                option.textContent = a.descripcion;
                aspectoFilter.appendChild(option);
            });
        } catch (error) {
            console.error("Error cargando aspectos:", error);
        }
    }

    // 🔹 Función para asignar clase por estado
    function getEstadoClass(estado) {
        if (!estado) return "bg-secondary";
        switch (estado.toLowerCase()) {
            case "cumple": return "bg-success";
            case "no cumple": return "bg-danger";
            case "parcial": return "bg-warning text-dark";
            default: return "bg-secondary";
        }
    }

    await loadVisitas();
    await loadAspectos();
});
