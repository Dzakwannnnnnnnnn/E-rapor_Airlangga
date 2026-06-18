<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktivasi Akun e-Rapor</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #F4F6F9;
            color: #334155;
            -webkit-font-smoothing: antialiased;
            line-height: 1.6;
        }
        .wrapper {
            max-width: 600px;
            margin: 40px auto;
            padding: 0 16px;
        }
        .card {
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 51, 153, 0.05);
            border: 1px solid #E2E8F0;
        }
        .header {
            background: #003399;
            padding: 40px 40px 48px;
            position: relative;
            text-align: center;
        }
        /* Dekorasi Geometris Header */
        .header-bg-circle {
            position: absolute;
            top: -50px;
            left: -50px;
            width: 150px;
            height: 150px;
            border: 4px dashed rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            pointer-events: none;
        }
        .logo-container {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            padding: 10px;
            border-radius: 14px;
            margin-bottom: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-bottom: 3px solid #FFB800;
        }
        .logo-img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }
        .badge {
            display: inline-block;
            background: #FFB800;
            color: #003399;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: 999px;
            margin-bottom: 12px;
        }
        .header h1 {
            color: #ffffff;
            font-size: 20px;
            font-weight: 900;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .header p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header-slanted {
            height: 12px;
            background: #FFB800;
        }
        .body {
            padding: 40px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 800;
            color: #0F172A;
            margin-bottom: 12px;
        }
        .text {
            font-size: 13px;
            color: #475569;
            margin-bottom: 16px;
        }
        /* Info Box */
        .info-box {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-left: 4px solid #003399;
            border-radius: 12px;
            padding: 16px 20px;
            margin: 24px 0;
        }
        .info-title {
            color: #003399;
            display: block;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 10px;
        }
        .info-item {
            font-size: 13px;
            color: #475569;
            margin-bottom: 6px;
        }
        .info-item:last-child {
            margin-bottom: 0;
        }
        /* Button */
        .btn-wrap {
            text-align: center;
            margin: 32px 0;
        }
        .btn {
            display: inline-block;
            background: #003399;
            color: #ffffff !important;
            text-decoration: none;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 16px 36px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 51, 153, 0.2);
            border-bottom: 3px solid rgba(0, 0, 0, 0.2);
        }
        /* Fallback URL */
        .fallback-text {
            text-align: center;
            font-size: 11px;
            color: #94A3B8;
            margin-bottom: 8px;
        }
        .url-fallback {
            background: #F1F5F9;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            padding: 12px;
            word-break: break-all;
            font-size: 11px;
            color: #64748B;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            margin-bottom: 28px;
        }
        /* Warning Box */
        .warning-box {
            background: #FFF9E6;
            border: 1px solid #FFEAA8;
            border-left: 4px solid #FFB800;
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 12px;
            color: #7A5500;
            margin-bottom: 24px;
        }
        .warning-title {
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
            margin-bottom: 4px;
        }
        .divider {
            border: none;
            border-top: 1px solid #E2E8F0;
            margin: 28px 0;
        }
        /* Footer */
        .footer {
            text-align: center;
            padding: 28px 40px;
            background: #F8FAFC;
            border-top: 1px solid #E2E8F0;
        }
        .footer p {
            font-size: 11px;
            color: #94A3B8;
            line-height: 1.8;
        }
        .footer strong {
            color: #475569;
            font-weight: 700;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="card">

        <div class="header">
            <div class="header-bg-circle"></div>
            <div class="logo-container">
                <img src="{{ $message->embed(public_path('SMKTI Airlangga Samarinda Icon.png')) }}" alt="Logo SMK TI" class="logo-img">
            </div>
            <div>
                <span class="badge">Aktivasi Akun</span>
            </div>
            <h1>e-Rapor</h1>
            <p>SMK TI Airlangga Samarinda</p>
        </div>

        <div class="header-slanted"></div>

        <div class="body">
            <p class="greeting">Halo, {{ $user->name }}</p>
            <p class="text">
                Selamat datang di sistem layanan informasi akademik <strong>e-Rapor SMK TI Airlangga</strong>. Akun akses portal Anda telah berhasil didaftarkan oleh tim administrator sekolah. Untuk memulai aktivasi dan mengonfigurasi kata sandi akun Anda, silakan gunakan tombol akses di bawah ini:
            </p>

            <div class="info-box">
                <span class="info-title">Kredensial Otorisasi Pengguna</span>
                <div class="info-item"><b>Nama Lengkap:</b> {{ $user->name }}</div>
                <div class="info-item"><b>Alamat Email:</b> {{ $user->email }}</div>
                <div class="info-item"><b>Hak Akses:</b> {{ $user->role === 'teacher' ? 'Guru / Tenaga Pendidik' : 'Wali Murid' }}</div>
            </div>

            <div class="btn-wrap">
                <a href="{{ $activationUrl }}" class="btn">
                    Konfirmasi & Atur Kata Sandi
                </a>
            </div>

            <p class="fallback-text">
                Jika tombol di atas tidak berfungsi, salin dan tempel tautan URL di bawah ini pada peramban web (*browser*) Anda:
            </p>
            <div class="url-fallback">{{ $activationUrl }}</div>

            <div class="warning-box">
                <span class="warning-title">Masa Berlaku Token Terbatas</span>
                Tautan aktivasi ini dilindungi oleh token enkripsi keamanan sistem yang akan kedaluwarsa dalam waktu <strong>24 jam</strong> sejak email ini diterbitkan. Jika masa berlaku terlampaui, Anda diwajibkan menghubungi unit kerja kurikulum/administrator sekolah untuk penerbitan token baru.
            </div>

            <hr class="divider">

            <p class="text" style="font-size:11px; color:#94A3B8; text-align: justify;">
                *Pesan Keamanan Otomatis:* Jika Anda tidak merasa melakukan permintaan pendaftaran akun ini, mohon abaikan notifikasi ini secara aman. Enkripsi akun tidak akan aktif sebelum proses otentikasi kata sandi diselesaikan melalui tautan di atas.
            </p>
        </div>

        <div class="footer">
            <p>
                Email dikirim secara otomatis oleh modul server otentikasi.<br>
                <strong>Unit IT e-Rapor SMK TI Airlangga</strong> &bull; Portal Akademik Terintegrasi<br>
                &copy; {{ date('Y') }} Hak Cipta Dilindungi Undang-Undang.
            </p>
        </div>

    </div>
</div>
</body>
</html>
