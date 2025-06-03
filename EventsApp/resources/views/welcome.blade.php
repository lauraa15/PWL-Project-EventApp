<!DOCTYPE html>
<html>
<head>
    <title>Cek Koneksi</title>
</head>
<body>
    <h1>Halo Laravel</h1>

    <script>
        fetch('http://localhost:3000/api/test')
            .then(res => res.text())
            .then(data => console.log('Hasil dari Node.js:', data))
            .catch(err => console.error('ERROR:', err));
    </script>
</body>
</html>
