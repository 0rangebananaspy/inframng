# InfraData Manager

Aplikasi pendataan Infrastruktur berbasis PHP Native dengan dukungan JSON database.

## 📁 Struktur Direktori

```
/htdocs/namadomain/infra/
├── config/              # Konfigurasi aplikasi
│   └── config.php       # File konfigurasi utama (atur BASE_PATH di sini)
├── includes/            # Class dan helper functions
│   ├── Auth.php         # Autentikasi & authorization
│   ├── Database.php     # Class database JSON
│   └── helpers.php      # Helper functions untuk path
├── modules/             # Module-based structure
│   ├── auth/            # Login/logout
│   │   ├── login.php
│   │   └── logout.php
│   ├── devices/         # Infrastructure Devices
│   │   └── index.php
│   ├── vms/             # Proxmox & Virtual Machines
│   │   ├── proxmox.php
│   │   └── virtual-machines.php
│   └── users/           # User management & Custom Fields
│       ├── index.php
│       └── fields.php
├── dashboard/           # Dashboard utama
│   ├── index.php
│   └── dashboard-content.php
├── templates/           # Layout template
│   └── layout.php
├── data/                # JSON database storage
│   ├── users.json
│   ├── devices.json
│   ├── proxmox.json
│   ├── virtual_machines.json
│   └── custom_fields.json
├── assets/              # CSS/JS custom (jika diperlukan)
├── index.php            # Root redirect ke login
└── README.md
```

## ⚙️ Konfigurasi

### 1. Set Base Path

Edit file `config/config.php`:

```php
// Untuk instalasi di /htdocs/namadomain/infra/
define('BASE_PATH', '/infra');

// Untuk instalasi di root domain
define('BASE_PATH', '');
```

### 2. Permissions

Pastikan folder `data/` memiliki permission write:

```bash
chmod 755 /htdocs/namadomain/infra/data
```

## 🚀 Fitur Utama

### 🔐 Autentikasi & Role
- **Login terenkripsi** dengan PASSWORD_ARGON2ID
- **2 Role**: Admin (full access) dan Operator (entry data only)
- Session timeout otomatis
- Default credentials: `admin / admin123`

### 🎨 UI/UX
- **TailwindCSS** via CDN
- **Font Awesome Duotone** icons
- **Font Roboto** dari Google Fonts
- **Dark mode** elegan dengan toggle
- Warna: Primary `#0256ac`, Accent Gold `#eeb60f`
- Fully **responsive** design
- **Glass blur effect** ala Apple

### 📊 Modules

1. **Dashboard** - Cards grid dengan statistik & quick links
2. **Infrastructure Devices** - Mesin, merk, IP, tahun pembelian, vendor, rekanan + custom fields
3. **Proxmox Management** - Host, IP, cluster, join cluster, user/password
4. **Virtual Machines** - VM, IP, user/password, kegunaan, linked to Proxmox host
5. **User Management** (Admin only) - CRUD users dengan role assignment
6. **Custom Fields** (Admin only) - Admin bisa buat field sendiri untuk form devices

### 💾 Database
- Menggunakan **file JSON** per module
- Class Database dengan metode: read, write, insert, update, delete, find

## 🔑 Default Login

```
Username: admin
Password: admin123
```

## 🛠️ Teknologi

- PHP 8.5 Native
- TailwindCSS (CDN)
- Font Awesome Duotone (CDN)
- Google Fonts - Roboto
- JSON File Database

## 📝 Cara Menjalankan

1. Copy semua file ke `/htdocs/namadomain/infra/`
2. Edit `config/config.php` dan set `BASE_PATH` sesuai kebutuhan
3. Buka browser ke `http://namadomain/infra/modules/auth/login.php`
4. Login dengan kredensial default

## 🔒 Keamanan

- Password di-hash menggunakan Argon2id (algoritma paling aman)
- Session management dengan timeout
- Role-based access control
- Input sanitization dengan htmlspecialchars()

## 📄 License

Private - All rights reserved
