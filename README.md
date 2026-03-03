# Project Status: Selesai

Aplikasi Pengaduan Sarana Sekolah telah berhasil dibuat.

## Informasi Akses
- **URL**: `http://localhost/fahmi/`
- **Database**: `pengaduan_sekolah`

## Akun Default
1. **Administrator**
   - Username: `admin`
   - Password: `admin123`
2. **Siswa**
   - Username: `siswa`
   - Password: `siswa123`

## Fitur Utama
- **Login/Logout**: Keamanan session dan password MD5.
- **Admin Dashboard**:
  - Statistik ringkas.
  - Daftar aspirasi terbaru.
  - Update status (Pending, Proses, Selesai, Ditolak).
  - Memberikan feedback/balasan.
- **Siswa Dashboard**:
  - Form pengaduan aspirasi.
  - Riwayat aspirasi.
  - Lihat status dan feedback dari admin.

## Struktur File Utama
- `config/database.php`: Koneksi database.
- `models/`: Logika data (UserModel, AspirasiModel).
- `controllers/`: Logika alur (AuthController).
- `views/`: (Files php di root folder admin/siswa/auth).
- `assets/`: CSS dan JS.

Silakan akses melalui browser dan login menggunakan akun di atas.
