document.addEventListener("DOMContentLoaded", () => {
  loadDashboardData();
});

async function loadDashboardData() {
  try {
    const res = await fetch("../backend/controllers/visitasController.php?action=dashboard");
    const data = await res.json();

    renderCards(data.resumen);
    renderTable("#tableVencer", data.vencer);
    renderTable("#tableVisitas", data.recientes);
    renderCharts(data.chartEstados, data.chartResponsables);

    document.getElementById("notifCount").textContent = data.notificaciones || 0;
  } catch (err) {
    console.error("Error cargando dashboard:", err);
  }
}

function renderCards(resumen) {
  const container = document.getElementById("cardsResumen");
  container.innerHTML = `
    <div class="col-md-3">
      <div class="card-custom stat-card bg-primary text-white text-center p-3">
        <i class="fas fa-clipboard-list"></i>
        <h3>${resumen.totalVisitas}</h3>
        <h6>Visitas</h6>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card-custom stat-card bg-warning text-white text-center p-3">
        <i class="fas fa-exclamation-triangle"></i>
        <h3>${resumen.observacionesPendientes}</h3>
        <h6>Observaciones Pendientes</h6>
      </div>
    </div>
  `;
}

function renderTable(selector, rows) {
  const tbody = document.querySelector(selector);
  tbody.innerHTML = rows.map(r => `
    <tr>
      <td>${r.id || r.fecha}</td>
      <td>${r.descripcion || r.nombre_visita}</td>
      <td>${r.fecha_limite || r.aspecto}</td>
      <td>${r.responsable}</td>
      <td><span class="badge bg-${getEstadoColor(r.estado)}">${r.estado}</span></td>
    </tr>
  `).join("");
}

function renderCharts(chartEstados, chartResponsables) {
  new Chart(document.getElementById("statusChart"), {
    type: "bar",
    data: {
      labels: Object.keys(chartEstados),
      datasets: [{
        data: Object.values(chartEstados),
        backgroundColor: ["#27ae60","#e74c3c","#f39c12","#3498db"]
      }]
    }
  });

  new Chart(document.getElementById("responsibleChart"), {
    type: "doughnut",
    data: {
      labels: Object.keys(chartResponsables),
      datasets: [{
        data: Object.values(chartResponsables),
        backgroundColor: ["#3498db","#2ecc71","#e74c3c","#f39c12","#9b59b6"]
      }]
    }
  });
}

function getEstadoColor(estado) {
  switch (estado) {
    case "Cumple": return "success";
    case "Parcial": return "warning";
    case "No Cumple": return "danger";
    default: return "secondary";
  }
}
