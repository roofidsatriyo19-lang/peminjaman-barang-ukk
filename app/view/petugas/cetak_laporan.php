<?php
// require_once dengan struktur folder kamu
// Karena file ini ada di app/view/petugas/, kita naik 3 level ke folder utama
require_once '../../config/connection.php';
require_once '../../model/peminjaman.php';

$peminjamanModel = new Peminjaman($pdo);
$data = $peminjamanModel->getAll(); 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman - Pinjemin</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 30px; color: #333; }
        .header { text-align: center; border-bottom: 3px double #333; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { margin: 0; text-transform: uppercase; }
        .header p { margin: 5px 0; color: #666; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #999; padding: 12px; text-align: left; font-size: 14px; }
        th { background-color: #f2f2f2; font-weight: bold; text-align: center; }
        tr:nth-child(even) { background-color: #fafafa; }
        
        .footer { margin-top: 50px; text-align: right; font-size: 14px; }
        .tanda-tangan { margin-top: 80px; margin-right: 50px; border-top: 1px solid #333; display: inline-block; width: 200px; text-align: center; }

        /* Sembunyikan tombol cetak saat proses print */
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; background: #6f42c1; color: white; border: none; border-radius: 5px;">
            🖨️ Cetak Laporan
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; cursor: pointer; background: #6c757d; color: white; border: none; border-radius: 5px; margin-left: 10px;">
            Tutup Tab
        </button>
    </div>

    <div class="header">
        <h1>LAPORAN PEMINJAMAN BARANG</h1>
        <p>Aplikasi Inventaris Barang - Pinjemin</p>
        <p>Dicetak pada: <?= date('d F Y, H:i'); ?> WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Peminjam</th>
                <th>Nama Barang</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($data)): ?>
                <tr>
                    <td colspan="6" style="text-align:center;">Data tidak ditemukan.</td>
                </tr>
            <?php else: ?>
                <?php $no=1; foreach($data as $row): ?>
                <tr>
                    <td style="text-align: center;"><?= $no++; ?></td>
                    <td><?= htmlspecialchars($row['nama_peminjam']); ?></td>
                    <td><?= htmlspecialchars($row['nama_barang']); ?></td>
                    <td><?= date('d/m/Y', strtotime($row['tgl_pinjam'])); ?></td>
                    <td><?= ($row['tgl_kembali']) ? date('d/m/Y', strtotime($row['tgl_kembali'])) : '-'; ?></td>
                    <td style="text-align: center;">
                        <strong><?= strtoupper($row['status_transaksi']); ?></strong>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Ngamprah, <?= date('d F Y'); ?></p>
        <p>Petugas Inventaris,</p>
        <div class="tanda-tangan">
             Asep Dado 
        </div>
    </div>

    <script>
        // Otomatis buka dialog print saat halaman dimuat
        window.onload = function() {
            // window.print(); // Hapus komentar jika ingin langsung print otomatis
        };
    </script>
</body>
</html>