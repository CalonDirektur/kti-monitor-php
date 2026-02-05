<div class="map-container">
    <h1>Peta Monitoring</h1>
    <div id="map" style="height: 600px;"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mapConfig = <?= json_encode($mapConfig['default']) ?>;
    const map = L.map('map').setView([mapConfig.lat, mapConfig.lng], mapConfig.zoom);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Load gempa markers
    fetch('/api/gempa')
        .then(r => r.json())
        .then(data => {
            if (data.data) {
                data.data.forEach(g => {
                    if (g.coordinates) {
                        const [lat, lng] = g.coordinates.split(',');
                        L.circleMarker([lat, lng], {
                            radius: g.magnitude * 2,
                            color: '#e74c3c'
                        }).addTo(map).bindPopup(`<b>M${g.magnitude}</b><br>${g.wilayah}`);
                    }
                });
            }
        });
});
</script>
