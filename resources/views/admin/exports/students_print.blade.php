<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Siswa</title>
    <style>
        /* Reset & Base */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; background: #fff; margin: 0; padding: 20px; }
        
        /* Header Laporan */
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 24px; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-size: 14px; color: #666; }

        /* Tabel Data */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 12px; }
        th, td { border: 1px solid #ccc; padding: 8px 10px; text-align: left; }
        th { background-color: #f4f4f4; color: #000; font-weight: bold; text-transform: uppercase; }
        tr:nth-child(even) { background-color: #fafafa; }

        /* Tanda Tangan (Opsional) */
        .footer { margin-top: 50px; display: flex; justify-content: flex-end; }
        .signature { text-align: center; width: 200px; }
        .signature p { margin-bottom: 60px; font-weight: bold; }
        .signature span { display: block; border-top: 1px solid #333; padding-top: 5px; }

        /* Tombol Cetak (Hanya tampil di layar) */
        .no-print { 
            background: #6366f1; color: white; padding: 10px 20px; 
            border: none; border-radius: 5px; cursor: pointer; 
            font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            position: fixed; bottom: 30px; right: 30px; z-index: 100;
        }
        .no-print:hover { background: #4f46e5; }

        /* KONFIGURASI CETAK (PENTING) */
        @media print {
            @page { size: A4; margin: 2cm; }
            body { padding: 0; background: white; }
            .no-print { display: none !important; } /* Sembunyikan tombol saat cetak */
            .header { border-bottom: 2px solid #000; }
            th { background-color: #ddd !important; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="no-print">
        🖨️ Cetak / Simpan PDF
    </button>

    <div class="header">
        <h1>Laporan Data Siswa</h1>
        <p>Media Utilwind &bull; Tahun Ajaran {{ date('Y') }}</p>
        <p style="font-size: 10px; margin-top: 5px;">Dicetak pada: {{ date('d F Y, H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 30%;">Nama Lengkap</th>
                <th style="width: 25%;">Email</th>
                <th style="width: 15%; text-align: center;">Kelas</th>
                <th style="width: 25%;">Institusi Asal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $index => $s)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td><strong>{{ $s->name }}</strong></td>
                <td>{{ $s->email }}</td>
                <td style="text-align: center;">{{ $s->class_group ?? '-' }}</td>
                <td>{{ $s->institution ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 20px;">Tidak ada data siswa.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="signature">
            <p>Mengetahui, Admin</p>
            <span>{{ Auth::user()->name ?? 'Administrator' }}</span>
        </div>
    </div>

    <script>
        // Opsional: Otomatis memicu print dialog saat halaman dibuka
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>