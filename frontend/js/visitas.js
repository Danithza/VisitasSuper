const API_BASE = 'http://localhost/VisitasSuper/backend/index.php';
const UPLOADS_BASE = 'http://localhost/VisitasSuper/backend/uploads/'; // 🔹 base absoluta para imágenes

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
      // Fila separadora con el nombre del aspecto
      const trAspecto = document.createElement('tr');
      trAspecto.innerHTML = `<td colspan="9" style="background:#dbeafe;font-weight:bold;text-align:center;border-top:2px solid #333;">${aspecto}</td>`;
      tbody.appendChild(trAspecto);

      // Filas de visitas bajo ese aspecto
      visitas.forEach(v => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${v.id}</td>
          <td>${v.fecha_inicio ?? ''}</td>
          <td>${v.fecha_fin ?? ''}</td>
          <td>${v.nombre_visita ?? ''}</td>
          <td>${v.actividad ?? ''}</td>
          <td>${v.plazo_fecha ?? ''}</td>
          <td>${v.responsable ?? ''}</td>
          <td>
            ${v.evidencia 
              ? `<img src="${UPLOADS_BASE}${v.evidencia}" width="80" alt="Evidencia">`
              : 'Sin imagen'}
          </td>
          <td>${v.estado ?? ''}</td>
        `;
        tbody.appendChild(tr);
      });
    });
  } catch (err) {
    alert('Error al obtener visitas: ' + err);
  }
}

async function loadAspectos() {
  try {
    const res = await fetch(`${API_BASE}?resource=aspectos`);
    const data = await res.json();
    const sel = document.getElementById('aspectoSelect');
    if (!sel) return;
    sel.innerHTML = '<option value="">-- seleccionar --</option>';
    data.forEach(a => {
      const o = document.createElement('option');
      o.value = a.id;
      o.textContent = a.descripcion;
      sel.appendChild(o);
    });
  } catch (err) {
    console.error(err);
  }
}

async function loadResponsables() {
  try {
    const res = await fetch(`${API_BASE}?resource=responsables`);
    const data = await res.json();
    const sel = document.getElementById('responsableSelect');
    if (!sel) return;
    sel.innerHTML = '<option value="">-- seleccionar --</option>';
    data.forEach(r => {
      const o = document.createElement('option');
      o.value = r.id;
      o.textContent = `${r.nombre} (${r.correo})`;
      sel.appendChild(o);
    });
  } catch (err) {
    console.error(err);
  }
}

async function submitForm(e) {
  e.preventDefault();
  const form = e.target;
  const formData = new FormData(form);

  try {
    const res = await fetch(`${API_BASE}?resource=visitas`, {
      method: 'POST',
      body: formData // 🔹 enviamos todo el form, incluida la imagen
    });
    const data = await res.json();
    if (res.ok) {
      alert(data.message || 'Guardado');
      window.location.href = 'index.html';
    } else {
      alert('Error: ' + JSON.stringify(data));
    }
  } catch (err) {
    alert('Error al guardar: ' + err);
  }
}
