const API_BASE = 'http://localhost/VisitasSuper/backend/index.php?resource=visitas';
const UPLOADS_BASE = 'http://localhost/VisitasSuper/backend/uploads/';

async function fetchAndRender() {
  try {
    const res = await fetch(`${API_BASE}?resource=visitas`);
    const data = await res.json();
    const tbody = document.querySelector('#visitasTable tbody');
    if (!tbody) return;
    tbody.innerHTML = '';

    // Agrupar visitas por aspecto
    const grupos = {};
    data.forEach(v => {
      const aspecto = v.aspecto || 'Sin aspecto';
      if (!grupos[aspecto]) grupos[aspecto] = [];
      grupos[aspecto].push(v);
    });

    // Renderizar cada grupo
    Object.entries(grupos).forEach(([aspecto, visitas]) => {
      const trAspecto = document.createElement('tr');
      trAspecto.className = 'aspecto-header';
      trAspecto.innerHTML = `<td colspan="9">${aspecto}</td>`;
      tbody.appendChild(trAspecto);

      visitas.forEach(v => {
        const tr = document.createElement('tr');
        const estadoClass = v.estado === 'Completado' ? 'estado-completado' : 
                           v.estado === 'Pendiente' ? 'estado-pendiente' : '';

        // Limpia prefijos "uploads/"
        const fileName = v.evidencia ? v.evidencia.replace(/^uploads[\\/]/, '') : null;

        tr.innerHTML = `
          <td>${v.id}</td>
          <td>${v.fecha_inicio ?? ''}</td>
          <td>${v.fecha_fin ?? ''}</td>
          <td>${v.nombre_visita ?? ''}</td>
          <td>${v.actividad ?? ''}</td>
          <td>${v.plazo_fecha ?? ''}</td>
          <td>${v.responsable ?? ''}</td>
          <td>
            ${fileName 
              ? `<img src="${UPLOADS_BASE}${fileName}" width="80" height="60" alt="Evidencia">`
              : '<span class="sin-imagen">Sin imagen</span>'}
          </td>
          <td class="${estadoClass}">${v.estado ?? ''}</td>
        `;
        tbody.appendChild(tr);
      });
    });
  } catch (err) {
    alert('Error al obtener visitas: ' + err);
  }
}

document.addEventListener('DOMContentLoaded', function() {
  fetchAndRender();
  document.getElementById('btnRefresh').addEventListener('click', fetchAndRender);
});
