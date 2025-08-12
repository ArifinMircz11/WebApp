<!DOCTYPE html>
<html>
<head>
  <title>Scan Absen</title>
  <script src="https://unpkg.com/html5-qrcode"></script>
</head>
<body>
<h2>Scan QR Siswa</h2>
<div id="reader" style="width:300px;"></div>
<script>
  function onScanSuccess(decodedText) {
    fetch('api/absen.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: 'id=' + encodeURIComponent(decodedText)
    })
    .then(res => res.text())
    .then(alert)
    .catch(console.error);
  }
  new Html5Qrcode("reader").start({ facingMode: "environment" }, { fps: 10, qrbox: 250 }, onScanSuccess);
</script>
</body>
</html>