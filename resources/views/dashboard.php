<div class="dashboard">
    <h1>Dashboard Monitoring</h1>
    
    <div class="grid">
        <section class="card">
            <h2>Gempa Terbaru</h2>
            <div id="gempa-list">
                <?php if (!empty($gempa)): ?>
                    <?php foreach ($gempa as $g): ?>
                    <div class="gempa-item">
                        <strong>M <?= $g['magnitude'] ?></strong>
                        <p><?= $g['wilayah'] ?></p>
                        <small><?= $g['tanggal'] ?> <?= $g['jam'] ?></small>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Tidak ada data gempa.</p>
                <?php endif; ?>
            </div>
        </section>
        
        <section class="card">
            <h2>Cuaca Hari Ini</h2>
            <div id="cuaca-list">
                <?php if (!empty($cuaca)): ?>
                    <?php foreach (array_slice($cuaca, 0, 5) as $c): ?>
                    <div class="cuaca-item">
                        <strong><?= $c['wilayah'] ?></strong>
                        <p><?= $c['cuaca'] ?> - <?= $c['suhu'] ?>°C</p>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Tidak ada data cuaca.</p>
                <?php endif; ?>
            </div>
        </section>
        
        <section class="card">
            <h2>Kualitas Udara</h2>
            <div id="udara-list">
                <?php if (!empty($udara)): ?>
                    <?php foreach ($udara as $u): ?>
                    <div class="udara-item">
                        <strong><?= $u['lokasi'] ?></strong>
                        <p>AQI: <?= $u['aqi'] ?></p>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Tidak ada data kualitas udara.</p>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>
