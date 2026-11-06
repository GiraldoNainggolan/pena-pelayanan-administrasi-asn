<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Layanan - {{ $layanan->nomor_layanan }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 30px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 3px double #000;
            margin-bottom: 25px;
            padding-bottom: 10px;
        }
        .header-logo {
            width: 80px;
            text-align: center;
            vertical-align: middle;
        }
        .header-logo img {
            height: 75px;
            width: auto;
        }
        .header-text {
            text-align: center;
            vertical-align: middle;
        }
        .header-text h1 {
            font-size: 16px;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header-text h2 {
            font-size: 18px;
            margin: 3px 0 0 0;
            text-transform: uppercase;
            font-weight: bold;
        }
        .header-text p {
            font-size: 11px;
            margin: 5px 0 0 0;
            color: #555;
        }
        .doc-title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            margin-bottom: 25px;
        }
        .meta-info {
            float: right;
            font-size: 11px;
            text-align: right;
            margin-bottom: 15px;
        }
        .clear {
            clear: both;
        }
        .content-section {
            margin-bottom: 20px;
        }
        .section-title {
            font-weight: bold;
            text-transform: uppercase;
            background-color: #f0f8ff;
            border: 1px solid #cce7ff;
            padding: 5px 10px;
            margin-bottom: 12px;
            font-size: 12px;
            color: #004085;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .info-table td {
            padding: 6px 8px;
            vertical-align: top;
        }
        .info-table td.label {
            width: 250px;
            font-weight: bold;
            color: #555;
        }
        .info-table td.colon {
            width: 10px;
        }
        .text-box {
            border: 1px solid #ddd;
            padding: 10px 12px;
            background-color: #fafafa;
            min-height: 80px;
            white-space: pre-line;
            font-size: 12.5px;
            line-height: 1.6;
        }
        .footer-signatures {
            margin-top: 50px;
            width: 100%;
        }
        .footer-signatures td {
            width: 50%;
            text-align: center;
        }
        .footer-signatures td.officer {
            padding-top: 75px;
        }
        @media print {
            body {
                padding: 10px;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Print Button (Hidden in Print Mode) -->
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print();" style="padding: 8px 16px; background-color: #0284c7; color: white; border: none; font-weight: bold; cursor: pointer;">Cetak Sekarang</button>
        <button onclick="window.close();" style="padding: 8px 16px; background-color: #64748b; color: white; border: none; font-weight: bold; cursor: pointer; margin-left: 8px;">Tutup Halaman</button>
    </div>

    <!-- Kop Surat -->
    <table class="header-table">
        <tr>
            <td class="header-logo">
                <img src="{{ asset('logo.webp') }}" alt="Logo Kabupaten Tuban">
            </td>
            <td class="header-text">
                <h1>Pemerintah Kabupaten Tuban</h1>
                <h2>PENA – Pelayanan dan Administrasi ASN</h2>
                <p>Jl. Kartini No. 4 Tuban, Jawa Timur - Telepon: (0356) 321xxx - Email: bkpsdm@tubankab.go.id</p>
            </td>
        </tr>
    </table>

    <!-- Meta Info / Nomor Surat -->
    <div class="meta-info">
        <strong>No. Layanan:</strong> {{ $layanan->nomor_layanan }}<br>
        <strong>Tanggal Input:</strong> {{ $layanan->tanggal->format('d M Y') }} ({{ $layanan->jam }})
    </div>
    <div class="clear"></div>

    <div class="doc-title">
        Kartu Kendali Asistensi & Konsultasi Kepegawaian
    </div>

    <!-- Section I: Identitas ASN -->
    <div class="content-section">
        <div class="section-title">I. Informasi Identitas Pegawai</div>
        <table class="info-table">
            <tr>
                <td class="label">Nama Lengkap ASN</td>
                <td class="colon">:</td>
                <td><strong>{{ $layanan->nama_asn }}</strong></td>
            </tr>
            <tr>
                <td class="label">NIP (Nomor Induk Pegawai)</td>
                <td class="colon">:</td>
                <td>{{ $layanan->nip }}</td>
            </tr>
            <tr>
                <td class="label">NIK (No. KTP)</td>
                <td class="colon">:</td>
                <td>{{ $layanan->nik ?: '-' }}</td>
            </tr>
            <tr>
                <td class="label">No. Telepon / WhatsApp</td>
                <td class="colon">:</td>
                <td>{{ $layanan->no_hp }}</td>
            </tr>
            <tr>
                <td class="label">Alamat Email</td>
                <td class="colon">:</td>
                <td>{{ $layanan->email }}</td>
            </tr>
        </table>
    </div>

    <!-- Section II: Instansi / Unit Kerja -->
    <div class="content-section">
        <div class="section-title">II. Asal Unit Kerja / Instansi</div>
        <table class="info-table">
            <tr>
                <td class="label">Kecamatan Wilayah</td>
                <td class="colon">:</td>
                <td>{{ $layanan->kecamatan ?: '-' }}</td>
            </tr>
            <tr>
                <td class="label">Jenjang Sekolah</td>
                <td class="colon">:</td>
                <td>{{ $layanan->jenjang ?: '-' }}</td>
            </tr>
            <tr>
                <td class="label">Nama Instansi / Sekolah</td>
                <td class="colon">:</td>
                <td>
                    @if($layanan->nama_sekolah)
                        {{ $layanan->nama_sekolah }}
                    @else
                        {{ $layanan->unit_kerja ?: '-' }}
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <!-- Section III: Klasifikasi Layanan -->
    <div class="content-section">
        <div class="section-title">III. Detail Klasifikasi & Status Layanan</div>
        <table class="info-table">
            <tr>
                <td class="label">Kategori Layanan Kepegawaian</td>
                <td class="colon">:</td>
                <td><strong>{{ $layanan->layanan }}</strong></td>
            </tr>
            <tr>
                <td class="label">Sub Kategori Layanan</td>
                <td class="colon">:</td>
                <td>{{ $layanan->sub_layanan }}</td>
            </tr>
            <tr>
                <td class="label">Status Hasil Akhir</td>
                <td class="colon">:</td>
                <td><span style="font-weight: bold; text-transform: uppercase; border: 1px solid #333; padding: 2px 6px; background-color: #eee;">{{ $layanan->hasil_layanan }}</span></td>
            </tr>
        </table>
    </div>

    <!-- Section IV: Deskripsi Permasalahan -->
    <div class="content-section">
        <div class="section-title">IV. Deskripsi Permasalahan / Konsultasi</div>
        <div class="text-box">
            {{ $layanan->permasalahan }}
        </div>
    </div>

    <!-- Section V: Tindak Lanjut -->
    <div class="content-section">
        <div class="section-title">V. Rekomendasi / Tindak Lanjut Operator</div>
        <div class="text-box">
            @if($layanan->tindak_lanjut)
                {{ $layanan->tindak_lanjut }}
            @else
                <span style="color: #888; font-style: italic;">Belum ada catatan tindak lanjut dari operator.</span>
            @endif
        </div>
    </div>

    <!-- Signatures -->
    <table class="footer-signatures">
        <tr>
            <td>
                ASN Yang Berkonsultasi,
                <br><br><br><br><br>
                ( <strong>{{ $layanan->nama_asn }}</strong> )
            </td>
            <td>
                Tuban, {{ $layanan->tanggal->format('d M Y') }}<br>
                Petugas / Operator PENA,
                <br><br><br><br><br>
                ( <strong>{{ $layanan->creator?->name ?: 'System' }}</strong> )
            </td>
        </tr>
    </table>

    <!-- Auto Print Script -->
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>
