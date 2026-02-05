<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>KTI Disaster Monitoring</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

    <style>
        body {
            background-color: #0f172a;
            color: #e5e7eb;
        }
        #map {
            height: 70vh;
            border-radius: 12px;
            border: 1px solid #1e293b;
        }
        .card-status {
            background: #020617;
            border: 1px solid #1e293b;
            border-radius: 12px;
        }
        .status-AMAN { color: #22c55e; }
        .status-WASPADA { color: #facc15; }
        .status-AWAS { color: #ef4444; }
        .alert-banner {
            background: red;
            color: white;
            overflow: hidden;
            white-space: nowrap;
            padding: 10px;
            font-weight: bold;
            margin-bottom: 12px; /* JARAK KE FILTER */
            border-radius: 6px;
        }

        .btn-group {
            margin-top: 8px;
        }

        #alert-text {
            display: inline-block;
            padding-left: 100%;
            animation: scroll-left 20s linear infinite;
        }
        @keyframes scroll-left {
            0% { transform: translateX(0); }
            100% { transform: translateX(-100%); }
        }

        .alert-item {
            border-bottom: 1px solid #1e293b;
            padding: 10px 0;
        }
        .alert-item:last-child {
            border-bottom: none;
        }
        .alert-type {
            font-size: 12px;
            padding: 3px 6px;
            border-radius: 6px;
        }
        .alert-HUJAN { background: #2563eb; }
        .alert-ANGIN { background: #f59e0b; }
        .alert-PETIR { background: #ef4444; }
        .alert-LAINNYA { background: #6b7280; }


    </style>
</head>

<body>

<div class="container-fluid px-4 py-3">

    <!-- HEADER -->
    <div class="mb-3">
        <h4 class="fw-bold">Monitoring Bencana Kawasan Timur Indonesia</h4>
        <small class="text-secondary">
            Sulawesi • Maluku • Papua — Sumber Data: BMKG
        </small>
    </div>

    <!-- STATUS CARDS -->
    <div class="row g-3 mb-3" id="status-cards"></div>

    <!-- <div id="alert-banner" class="alert-banner d-none"
        onclick="openAlertModal('ALL')" style="cursor:pointer">
        <div id="alert-text"></div>
    </div> -->
    <div id="alert-banner" class="alert-banner d-none">
        <span id="alert-text"></span>

        <button class="btn btn-sm btn-light ms-3"
            onclick="hideAlertBanner(event)">
            Hide
        </button>
    </div>



    <div class="btn-group mb-2">
        <button class="btn btn-sm btn-secondary" onclick="openAlertModal('ALL')">Semua</button>
        <button class="btn btn-sm btn-primary" onclick="openAlertModal('HUJAN')">Hujan</button>
        <button class="btn btn-sm btn-warning" onclick="openAlertModal('ANGIN')">Angin</button>
        <button class="btn btn-sm btn-danger" onclick="openAlertModal('PETIR')">Petir</button>
    </div>



    <!-- MAP -->
    <div id="map"></div>

</div>

<!-- ================= MODAL HUJAN BMKG ================= -->
<div class="modal fade" id="rainModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header">
        <h5 class="modal-title">🌧️ Curah Hujan Realtime BMKG (GSMaP)</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <img
          src="https://inderaja.bmkg.go.id/IMAGE/GSMAP/Hourly_Prec.png"
          class="img-fluid rounded"
          alt="Peta Hujan GSMaP BMKG"
        >
        <p class="small text-secondary mt-3">
          Sumber: BMKG • GSMaP • Update per jam<br>
          Catatan: Citra nasional sebagai referensi visual
        </p>
      </div>
    </div>
  </div>
</div>

<!-- MODAL DETAIL NOWCAST -->
<div class="modal fade" id="alertModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header">
        <h5 class="modal-title">⚠️ Riwayat Peringatan Dini BMKG (7 Hari)</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div id="alert-modal-content">
          <p class="text-secondary">Memuat data...</p>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// ========================
// INIT MAP (KTI)
// ========================
const map = L.map('map').setView([-2.0, 132.0], 5);

// Lock bounds ke KTI
const bounds = L.latLngBounds(
    [-11.5, 118.0],
    [5.5, 141.5]
);
map.setMaxBounds(bounds);
map.on('drag', () => map.panInsideBounds(bounds, { animate: false }));

// Base map
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap'
}).addTo(map);

// ========================
// LAYER CONTROL (HUJAN POPUP)
// ========================
const hujanTriggerLayer = L.layerGroup();

const overlayMaps = {
    "🌧️ Curah Hujan (BMKG)": hujanTriggerLayer
};

L.control.layers(null, overlayMaps, { collapsed: false }).addTo(map);

// ========================
// MODAL CONTROL
// ========================
const rainModal = new bootstrap.Modal(
    document.getElementById('rainModal')
);

// Saat checkbox dicentang
map.on('overlayadd', function (e) {
    if (e.layer === hujanTriggerLayer) {
        rainModal.show();
    }
});

// Saat modal ditutup → uncheck checkbox
document.getElementById('rainModal')
    .addEventListener('hidden.bs.modal', function () {

        map.removeLayer(hujanTriggerLayer);

        document
          .querySelectorAll('.leaflet-control-layers-selector')
          .forEach(input => {
              if (input.nextSibling &&
                  input.nextSibling.textContent.includes('Curah Hujan')) {
                  input.checked = false;
              }
          });
    });

// ========================
// LOAD GEMPA DATA
// ========================
fetch('api/gempa_latest.php')
    .then(res => res.json())
    .then(res => {
        if (res.status !== 'ok') return;

        res.data.forEach(g => {
            const marker = L.circleMarker([g.lat, g.lon], {
                radius: g.magnitude >= 6 ? 10 : 6,
                color: g.magnitude >= 6 ? '#ef4444' : '#facc15',
                fillOpacity: 0.8
            }).addTo(map);

            marker.bindPopup(`
                <strong>${g.wilayah}</strong><br>
                Magnitudo: <b>${g.magnitude} SR</b><br>
                Kedalaman: ${g.kedalaman}<br>
                Potensi: ${g.potensi}<br>
                Waktu: ${g.waktu}
            `);
        });
    });

// ========================
// LOAD DASHBOARD STATUS
// ========================
fetch('api/dashboard_status.php')
    .then(res => res.json())
    .then(res => {
        if (res.status !== 'ok') return;

        const s = res.ringkasan;
        const container = document.getElementById('status-cards');

        const items = [
            { label: 'Gempa', value: s.gempa },
            { label: 'Hujan', value: s.hujan },
            { label: 'Kualitas Udara', value: s.udara },
            { label: 'Bendungan', value: s.bendungan }
        ];

        container.innerHTML = '';
        items.forEach(i => {
            container.innerHTML += `
                <div class="col-md-3">
                    <div class="card card-status p-3">
                        <div class="text-secondary">${i.label}</div>
                        <div class="fs-4 fw-bold status-${i.value}">
                            ${i.value}
                        </div>
                    </div>
                </div>
            `;
        });
    });


fetch('api/nowcast_map.php')
  .then(r => r.json())
  .then(res => {
    if (res.status !== 'ok') return;

    res.data.forEach(a => {
        const marker = L.marker([-2, 128]).addTo(map); // pusat KTI
        marker.bindPopup(`
            <b>⚠️ Peringatan Dini BMKG</b><br>
            ${a.title}<br>
            <a href="${a.link}" target="_blank">Detail</a>
        `);
    });
  });


// fetch('api/nowcast_dashboard.php')
//   .then(r=>r.json())
//   .then(res=>{
//     if(res.data.length===0) return;

//     // const text = res.data.map(a=>`⚠️ ${a.title}`).join(' || ');
//     const text = res.data.map(a => {
//     const t = new Date(a.pub_date);
//     const waktu = t.toLocaleString('id-ID', {
//             day: '2-digit',
//             month: 'short',
//             year: 'numeric',
//             hour: '2-digit',
//             minute: '2-digit'
//         });

//         return `⚠️ [${waktu}] ${a.title}`;
//     }).join('  ||  ');

//     document.getElementById('alert-text').innerText = text;
//     document.getElementById('alert-banner').classList.remove('d-none');
// });

fetch('api/nowcast_dashboard.php')
  .then(r => r.json())
  .then(res => {
    if (res.data.length === 0) return;

    const hideUntil = localStorage.getItem('hide_nowcast_until');
    if (hideUntil && Date.now() < hideUntil) return;

    const text = res.data.map(a => {
        const t = new Date(a.pub_date);
        const waktu = t.toLocaleString('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        return `⚠️ [${waktu}] ${a.title}`;
    }).join('  ||  ');

    document.getElementById('alert-text').innerText = text;
    document.getElementById('alert-banner').classList.remove('d-none');
});


function loadAlert(type){
  fetch('api/nowcast_dashboard.php?type='+type)
    .then(r=>r.json())
    .then(res=>{
      console.table(res.data);
      // Bisa ditampilkan ke modal / tabel nanti
    });
}

const alertModal = new bootstrap.Modal(
    document.getElementById('alertModal')
);

function openAlertModal(type = 'ALL') {
    fetch(`api/nowcast_dashboard.php?type=${type}`)
        .then(res => res.json())
        .then(res => {
            if (res.status !== 'ok') return;

            const container = document.getElementById('alert-modal-content');

            if (res.data.length === 0) {
                container.innerHTML = `
                    <p class="text-secondary">Tidak ada alert dalam 7 hari terakhir.</p>
                `;
                return;
            }

            container.innerHTML = '';

            res.data.forEach(a => {
                container.innerHTML += `
                    <div class="alert-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <span class="alert-type alert-${a.alert_type}">
                                ${a.alert_type}
                            </span>
                            <small class="text-secondary">
                                ${new Date(a.pub_date).toLocaleString()}
                            </small>
                        </div>

                        <div class="mt-2">
                            <strong>${a.title}</strong>
                        </div>

                        <div class="mt-1">
                            <a href="${a.link}" target="_blank" class="link-info">
                                Buka Detail BMKG →
                            </a>
                        </div>
                    </div>
                `;
            });

            alertModal.show();
        });
}

function hideAlertBanner(e) {
    e.stopPropagation();

    const until = Date.now() + (2 * 60 * 60 * 1000); // 2 jam
    localStorage.setItem('hide_nowcast_until', until);

    document.getElementById('alert-banner')
        .classList.add('d-none');
}



</script>

</body>
</html>
