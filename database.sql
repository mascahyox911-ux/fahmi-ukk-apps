

-- Table: users
CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_md5 VARCHAR(255) NOT NULL,
    role ENUM('admin', 'siswa') NOT NULL
) ENGINE=InnoDB;

-- Table: kategori
CREATE TABLE kategori (
    id_kategori INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

-- Table: aspirasi
CREATE TABLE aspirasi (
    id_aspirasi INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_kategori INT NOT NULL,
    judul VARCHAR(255) NOT NULL,
    deskripsi TEXT NOT NULL,
    tanggal DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'proses', 'selesai', 'ditolak') DEFAULT 'pending',
    fotobukti VARCHAR(255),
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Table: feedback
CREATE TABLE feedback (
    id_feedback INT AUTO_INCREMENT PRIMARY KEY,
    id_aspirasi INT NOT NULL,
    id_user INT NOT NULL, 
    pesan TEXT NOT NULL,
    tanggal DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_aspirasi) REFERENCES aspirasi(id_aspirasi) ON DELETE CASCADE,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Table: histori
CREATE TABLE histori (
    id_histori INT AUTO_INCREMENT PRIMARY KEY,
    id_aspirasi INT NOT NULL,
    keterangan VARCHAR(255) NOT NULL,
    tanggal DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_aspirasi) REFERENCES aspirasi(id_aspirasi) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Insert Default Users
-- admin / admin123
INSERT INTO users (nama, username, password_md5, role) VALUES ('Administrator', 'admin', MD5('admin123'), 'admin');
-- siswa / siswa123
INSERT INTO users (nama, username, password_md5, role) VALUES ('Siswa Contoh', 'siswa', MD5('siswa123'), 'siswa');

-- Insert Default Categories
INSERT INTO kategori (nama_kategori) VALUES ('Fasilitas Kelas');
INSERT INTO kategori (nama_kategori) VALUES ('Fasilitas Umum');
INSERT INTO kategori (nama_kategori) VALUES ('Kebersihan');
INSERT INTO kategori (nama_kategori) VALUES ('Keamanan');
