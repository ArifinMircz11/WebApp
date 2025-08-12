<canvas id="grafikPresensi" width="400" height="150"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
var ctx = document.getElementById('grafikPresensi').getContext('2d');
var chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Hadir', 'Izin', 'Sakit', 'Alpa'],
        datasets: [{
            label: 'Presensi Bulan Ini',
            data: [
                <?= $statistik['hadir'] ?? 0 ?>,
                <?= $statistik['izin'] ?? 0 ?>,
                <?= $statistik['sakit'] ?? 0 ?>,
                <?= $statistik['alpa'] ?? 0 ?>
            ],
            borderWidth: 1
        }]
    }
});
</script>