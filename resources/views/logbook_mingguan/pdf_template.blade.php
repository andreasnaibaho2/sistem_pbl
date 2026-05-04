<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Logbook Mingguan</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #1a1a1a; }

        .header { text-align: center; margin-bottom: 16px; border-bottom: 2px solid #1a1a1a; padding-bottom: 10px; }
        .header h2 { font-size: 14px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .header p { font-size: 10px; color: #555; margin-top: 2px; }

        .info-box { margin-bottom: 14px; }
        .info-box table { width: 100%; }
        .info-box td { padding: 2px 6px; font-size: 11px; }
        .info-box td:first-child { width: 140px; font-weight: bold; }
        .info-box td:nth-child(2) { width: 10px; }

        .badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; }
        .badge-diajukan { background: #dbeafe; color: #1d4ed8; }
        .badge-disetujui { background: #dcfce7; color: #15803d; }
        .badge-ditolak { background: #fee2e2; color: #b91c1c; }
        .badge-draft { background: #f3f4f6; color: #374151; }

        table.logbook { width: 100%; border-collapse: collapse; margin-top: 8px; }
        table.logbook th { background: #1e3a5f; color: #fff; padding: 6px 8px; text-align: left; font-size: 10px; }
        table.logbook td { padding: 6px 8px; border-bottom: 1px solid #e5e7eb; vertical-align: top; font-size: 10px; }
        table.logbook tr:nth-child(even) td { background: #f8fafc; }

        .no-data { text-align: center; padding: 20px; color: #9ca3af; font-style: italic; }

        .ttd-section { margin-top: 30px; }
        .ttd-section table { width: 100%; }
        .ttd-section td { text-align: center; padding: 0 10px; font-size: 10px; }
        .ttd-box { height: 60px; border-bottom: 1px solid #1a1a1a; margin: 0 20px 4px; }

        .footer { margin-top: 20px; font-size: 9px; color: #9ca3af; text-align: center; border-top: 1px solid #e5e7eb; padding-top: 6px; }

        .hari-label { font-weight: bold; color: #1e3a5f; }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <h2>Rekap Logbook Harian Mingguan</h2>
        <p>Sistem Informasi PBL — AE Politeknik Manufaktur Bandung</p>
    </div>

    {{-- Info Mahasiswa & Proyek --}}
    <div class="info-box">
        <table>
            <tr>
                <td>Nama Mahasiswa</td><td>:</td>
                <td>{{ $mahasiswa->user->name ?? '-' }}</td>
            </tr>
            <tr>
                <td>Program Studi</td><td>:</td>
                <td>{{ labelProdi($mahasiswa->user->prodi ?? '') }}</td>
            </tr>
            <tr>
                <td>Judul Proyek</td><td>:</td>
                <td>{{ $proyek->judul_proyek ?? '-' }}</td>
            </tr>
            <tr>
                <td>Minggu Ke</td><td>:</td>
                <td>{{ $request->minggu_ke }}</td>
            </tr>
            <tr>
                <td>Tanggal Generate</td><td>:</td>
                <td>{{ now()->translatedFormat('d F Y') }}</td>
            </tr>
        </table>
    </div>

    {{-- Tabel Logbook Harian --}}
    <table class="logbook">
        <thead>
            <tr>
                <th style="width:5%">No</th>
                <th style="width:10%">Hari</th>
                <th style="width:12%">Tanggal</th>
                <th style="width:55%">Aktivitas</th>
                <th style="width:18%">Dokumentasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($harian as $i => $log)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><span class="hari-label">{{ ucfirst($log->hari) }}</span></td>
                <td>{{ \Carbon\Carbon::parse($log->tanggal)->format('d/m/Y') }}</td>
                <td>{{ $log->aktivitas }}</td>
                <td>
                    @if($log->dokumentasi)
                        Ada
                    @else
                        <span style="color:#9ca3af">-</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="no-data">Tidak ada data logbook harian.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Tanda Tangan --}}
    <div class="ttd-section">
        <table>
            <tr>
                <td>
                    <div class="ttd-box"></div>
                    <p>Mahasiswa</p>
                    <p style="margin-top:2px;font-weight:bold;">{{ $mahasiswa->user->name ?? '-' }}</p>
                </td>
                <td>
                    <div class="ttd-box"></div>
                    <p>Manager Proyek</p>
                    <p style="margin-top:2px;font-weight:bold;">{{ $proyek->user->name ?? '-' }}</p>
                </td>
                <td>
                    <div class="ttd-box"></div>
                    <p>Dosen Pengampu</p>
                    <p style="margin-top:2px;font-weight:bold;">........................</p>
                </td>
            </tr>
        </table>
    </div>

    {{-- Footer --}}
    <div class="footer">
        Digenerate otomatis oleh Sistem Informasi PBL — {{ now()->format('d/m/Y H:i') }}
    </div>

</body>
</html>