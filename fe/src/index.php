<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sistem Rumah Sakit & Apotek</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }
        h1 {
            text-align: center;
        }
        .container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin-top: 30px;
        }
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            width: 300px;
            margin: 10px;
            padding: 20px;
        }
        .card h2 {
            margin-top: 0;
        }
        .card a {
            display: block;
            margin: 8px 0;
            color: #3498db;
            text-decoration: none;
        }
        .card a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h1>Selamat Datang di Sistem Informasi</h1>

<div class="container">
    <div class="card">
        <h2>🏥 Hospital Service</h2>
        <a href="hospital/patients.php">Daftar Pasien</a>
        <a href="hospital/create_patient.php">Tambah Pasien</a>
        <a href="hospital/consultations.php">Daftar Konsultasi</a>
        <a href="hospital/create_consultation.php">Tambah Konsultasi</a>
        <a href="hospital/doctors.php">Daftar Dokter</a>
        <a href="hospital/create_doctor.php">Tambah Dokter</a>
        <a href="hospital/obat_from_apotek.php">obat dari apotek(intragasi)</a>
        <a href="hospital/prescriptions.php">pesan obat(intragasi)</a>
    </div>

    <div class="card">
        <h2>💊 Apotek Service</h2>
        <a href="apotek/obat.php">Daftar Obat</a>
        <a href="apotek/create_obat.php">Tambah Obat</a>
        <a href="apotek/suppliers.php">Daftar Supplier</a>
        <a href="apotek/create_supplier.php">Tambah Supplier</a>
        <a href="apotek/orders.php">Daftar Pesanan</a>
        <a href="apotek/create_order.php">Tambah Pesanan</a>
        <a href="apotek/purchase_history.php">Riwayat Pembelian</a>
        <a href="apotek/create_purchase_history.php">Tambah Riwayat(intregrasi)</a>
        <a href="apotek/patients_from_hospital.php">Lihat Data Pasien (Integrasi)</a>
    </div>
</div>

</body>
</html>
