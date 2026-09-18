
Conversation with Gemini
dalam sebuah website e-presensi yang nantinya memiliki 2 user yaitu siswa dan wali kelas. Kira kira modul CRUD apa saja yang perlu ada dalam POV user dan siswa?

Untuk sistem e-presensi, pembagian hak akses CRUD (Create, Read, Update, Delete) harus dibatasi secara ketat agar data kehadiran tetap valid dan tidak bisa dimanipulasi secara sepihak.

Modul dari POV Siswa
Siswa hanya memiliki kontrol penuh pada data pribadinya dan hak untuk mengirimkan data, namun tidak bisa mengubah data absensi yang sudah tercatat.

Modul Presensi Harian

Create: Melakukan check-in (hadir) dan check-out (pulang) harian, biasanya mencatat waktu dan lokasi/foto selfie.

Read: Melihat status presensi hari ini (Berhasil/Gagal/Terlambat).

Modul Pengajuan Izin/Sakit

Create: Mengunggah surat dokter atau alasan izin.

Read: Memantau status pengajuan (Menunggu/Disetujui/Ditolak).

Update: Mengedit pengajuan hanya jika belum diproses oleh Wali Kelas.

Delete: Membatalkan pengajuan izin yang belum diproses.

Modul Riwayat & Laporan

Read: Melihat rekapitulasi kehadiran sendiri (jumlah Hadir, Izin, Sakit, Alpha) per bulan atau semester.

Modul Profil Pengguna

Read: Melihat detail biodata siswa.

Update: Mengubah data minor (seperti nomor telepon, foto profil, atau password).

Modul dari POV Wali Kelas
Wali kelas bertindak sebagai pengawas dan verifikator untuk satu kelas spesifik, sehingga fokus utamanya adalah memantau dan memvalidasi.

Modul Monitor Kehadiran Kelas (Dashboard)

Read: Melihat rekap siapa saja yang hadir, terlambat, izin, sakit, atau alpha pada hari tersebut (real-time).

Modul Verifikasi Izin/Sakit

Read: Melihat daftar siswa yang mengajukan izin/sakit beserta lampiran buktinya.

Update: Mengubah status pengajuan siswa menjadi "Disetujui" atau "Ditolak".

Modul Manajemen Presensi Siswa

Read: Melihat detail riwayat absensi setiap siswa di kelasnya.

Update: Mengoreksi status kehadiran siswa (misal: siswa lupa check-in tapi ada di kelas, wali kelas bisa mengubah statusnya dari Alpha menjadi Hadir).

Modul Laporan Kelas

Read: Mengakses rekapitulasi absensi seluruh siswa di kelasnya dalam periode tertentu (mingguan/bulanan).

Create (Export): Membuat dan mengunduh laporan ke format PDF/Excel untuk diserahkan ke sekolah atau orang tua.

Modul Profil Wali Kelas

Read: Melihat biodata pribadi.

Update: Mengubah password atau detail kontak.

Catatan Keamanan: Fitur Delete pada data absensi yang sudah valid sebaiknya ditiadakan untuk kedua user ini guna mencegah hilangnya jejak audit. Penghapusan data permanen hanya boleh dilakukan oleh akun Admin Sekolah.

Rancang struktur database untuk modul ini?

Yes
Baik, sekaarang dari rundingan anda, buatkan file prd untuk website ini

PRODUCT REQUIREMENTS DOCUMENT (PRD): SISTEM E-PRESENSI SEKOLAH

1. Ringkasan Produk (Product Overview)
Sistem E-Presensi Sekolah adalah aplikasi berbasis web responsif yang dirancang untuk mendigitalkan proses pencatatan kehadiran siswa. Produk ini bertujuan untuk meminimalisir rekapitulasi manual, memberikan data kehadiran secara real-time, dan mempermudah proses pengajuan serta verifikasi izin/sakit.

Tujuan Bisnis:

Mencapai akurasi data kehadiran hingga 99% tanpa manipulasi.

Mengurangi waktu rekapitulasi absen oleh wali kelas dari hitungan jam menjadi hitungan menit.

2. Profil Pengguna (User Personas)
Siswa: Pengguna aktif harian yang membutuhkan antarmuka cepat (mobile-friendly) untuk melakukan absensi (check-in/check-out) dan mengajukan izin saat tidak bisa hadir.

Wali Kelas: Pengguna dengan peran pengawas yang membutuhkan dashboard komprehensif untuk memantau status kelas, memvalidasi surat izin, dan mencetak laporan bulanan.

3. Kebutuhan Fungsional (User Stories & Acceptance Criteria)
Modul Utama	Pengguna	User Story	Kriteria Penerimaan (Acceptance Criteria)
Autentikasi	Semua	Sebagai pengguna, saya ingin masuk ke sistem agar bisa mengakses dasbor saya.	Login menggunakan NISN/NIP dan password. Sistem mengarahkan ke dasbor yang sesuai dengan role.
Presensi Harian	Siswa	Sebagai siswa, saya ingin menekan tombol check-in agar kehadiran saya tercatat.	Tombol check-in hanya aktif pada jam yang ditentukan (misal: 06:00 - 07:15). Menyimpan timestamp otomatis.
Pengajuan Izin	Siswa	Sebagai siswa, saya ingin mengunggah surat dokter agar status saya menjadi "Sakit".	Form izin mewajibkan unggah bukti (JPG/PDF). Status awal adalah "Menunggu Verifikasi". Bisa dibatalkan sebelum diproses.
Dashboard Kelas	Wali Kelas	Sebagai wali kelas, saya ingin melihat ringkasan absen hari ini agar tahu siapa yang tidak hadir.	Terdapat ringkasan visual (angka/grafik) jumlah Hadir, Izin, Sakit, Alpha hari ini. Terdapat daftar nama siswa beserta statusnya.
Verifikasi Izin	Wali Kelas	Sebagai wali kelas, saya ingin menyetujui/menolak pengajuan izin agar data absensi valid.	Terdapat tombol "Setujui" dan "Tolak". Jika disetujui, status absensi siswa otomatis berubah di hari tersebut.
Koreksi Data	Wali Kelas	Sebagai wali kelas, saya ingin mengubah status alpha menjadi hadir jika ada error pada sistem siswa.	Wali kelas dapat mengedit status absen harian siswa beserta kolom catatan/alasan koreksi.
Laporan	Wali Kelas	Sebagai wali kelas, saya ingin mengunduh rekap absensi agar bisa diserahkan ke sekolah.	Terdapat filter tanggal/bulan. Tombol "Export" menghasilkan dokumen Excel (.xlsx) atau PDF yang rapi.
4. Kebutuhan Non-Fungsional
Aksesibilitas (UI/UX): Antarmuka utama untuk POV Siswa harus dirancang secara mobile-first, mengingat mayoritas siswa akan mengakses melalui smartphone.

Keamanan Data (Audit Trail): Sistem tidak menyediakan fungsi Delete untuk data presensi yang sudah divalidasi. Setiap koreksi data yang dilakukan oleh Wali Kelas harus meninggalkan jejak (log) siapa dan kapan data tersebut diubah.

Performa: Waktu muat (load time) untuk proses check-in tidak boleh lebih dari 3 detik untuk mencegah penumpukan/antrean digital di jam masuk sekolah.

5. Asumsi & Batasan (Out of Scope)
Fase MVP (Minimum Viable Product): Tidak menggunakan fitur GPS/Geofencing atau deteksi wajah (Face Recognition) pada rilis pertama untuk menekan biaya server. Check-in murni berbasis waktu (timestamp).

Manajemen Data Induk: Penambahan siswa baru, kenaikan kelas, dan penentuan wali kelas tidak dilakukan di modul ini, melainkan dipegang penuh oleh akun Super Admin (berada di luar cakupan PRD ini).

Rancang arsitektur database

Yes
Buatkan dalam file .docx

Dokumen PRD (Product Requirements Document) Anda dalam format .docx sudah siap.

DOCX icon
PRD_Sistem_E-Presensi_Sekolah
 DOCX 
File dokumen ini sudah diatur dengan rapi mencakup:

Ringkasan Produk beserta Tujuan Bisnis.

Profil Pengguna (Siswa dan Wali Kelas).

Tabel terstruktur untuk Kebutuhan Fungsional (User Story & Acceptance Criteria).

Kebutuhan Non-Fungsional (UI/UX, Keamanan, Performa).

Asumsi dan Batasan sistem.

Silakan unduh dokumen di atas. Beritahu saya jika ada bagian yang ingin Anda tambahkan atau modifikasi!

Untuk hasil yang paling efektif prompt apa yang efektif

Prompt yang ada di gambar (tangkapan layar) Anda sebenarnya sudah cukup baik karena memberikan konteks awal. Namun, prompt tersebut kurang instruksi spesifik (Call to Action) dan tidak menyebutkan file PRD yang sudah Anda lampirkan. AI butuh arahan yang jelas tentang apa yang harus dilakukan dengan file .zip dan .docx tersebut.

Untuk hasil yang paling efektif (agar AI tidak bingung dan langsung memberikan kode/solusi yang akurat), Anda bisa menggunakan formula: Konteks + Referensi File + Instruksi Langkah demi Langkah.

Berikut adalah rancangan prompt yang sangat efektif yang bisa Anda copy-paste dan modifikasi sedikit:

Opsi 1: Prompt Komprehensif (Langkah-demi-langkah dari Awal - Sangat Disarankan)
Gunakan prompt ini jika Anda ingin AI memberikan panduan lengkap dari modifikasi database hingga UI.

"Saya ingin mengembangkan website E-presensi ini menjadi sistem multi-user (Siswa dan Wali Kelas).

Tolong baca file PRD_Sistem_E-Presensi_Sekolah.docx untuk memahami fungsi, batasan, dan fitur spesifik dari masing-masing role (terutama modul Wali Kelas).

Kemudian, pelajari file [Nama file zip Anda].zip yang berisi progres kode saya sejauh ini. Saat ini sistem baru mendukung satu user (Siswa).

Berdasarkan kedua file tersebut, tolong berikan panduan langkah demi langkah untuk menambahkan role 'Wali Kelas'. Tolong buatkan:

Langkah 1: Perubahan struktur Database / file Migrasi (bagaimana cara memisahkan role dan menghubungkan wali kelas dengan tabel kelas/presensi).

Langkah 2: Logika Autentikasi / Middleware untuk mengatur hak akses halaman (Role-based access control).

Langkah 3: Kode untuk Controller dan Route yang dibutuhkan oleh Wali Kelas sesuai PRD.

Catatan: Lanjutkan menggunakan tech stack (bahasa pemrograman/framework) yang sama seperti di dalam file .zip."

Opsi 2: Prompt Fokus ke Database Dulu (Jika ingin bertahap)
Jika Anda takut AI memberikan jawaban yang terlalu panjang dan terpotong, mulailah dari fondasinya (Database).

"Saya melampirkan file .zip (progres kode website E-presensi saya, saat ini hanya ada 1 user) dan file .docx (Dokumen PRD sistemnya).

Sebagai langkah pertama untuk menambahkan role 'Wali Kelas' sesuai dengan dokumen PRD tersebut, tolong analisis kode backend saya di file .zip. Apa saja tabel atau kolom database yang perlu saya ubah atau tambahkan? Tolong buatkan rancangan skema database terbarunya (atau file migrasinya) terlebih dahulu sebelum kita lanjut ke bagian kode Controller/UI."

Mengapa prompt di atas lebih efektif?
Menggunakan dokumen PRD: Anda memaksa AI membaca PRD_Sistem_E-Presensi_Sekolah.docx yang sudah dibuat sebelumnya sebagai "sumber kebenaran" (Source of Truth), sehingga fitur yang dibuat tidak melenceng.

Memecah Masalah (Chunking): Menginstruksikan AI untuk membaginya menjadi Database, Middleware (Keamanan/Autentikasi), dan Logika (Controller). Ini mencegah AI memberikan jawaban yang ngawur atau melompati bagian penting.

Mempertahankan Tech Stack: Mengingatkan AI untuk tetap menggunakan bahasa/framework yang ada di dalam .zip (misal: jika .zip isinya Laravel PHP, AI tidak akan tiba-tiba memberikan kode Node.js).

Bagaimana jika saya ingin mengirimkannya ke github copilit?

Untuk menggunakan GitHub Copilot (terutama GitHub Copilot Chat di VS Code atau JetBrains), caranya sedikit berbeda dengan AI Chatbot umum (seperti Gemini/ChatGPT).

GitHub Copilot bekerja langsung di dalam kode editor (IDE) Anda. Copilot tidak membaca file .zip atau .docx secara langsung melalui tombol upload, melainkan membaca file yang ada di dalam workspace (folder projek) yang sedang Anda buka.

Berikut adalah panduan langkah-demi-langkah dan prompt paling efektif untuk GitHub Copilot:

Langkah Persiapan di IDE (VS Code)
Ekstrak File .zip: Ekstrak file .zip Anda ke dalam sebuah folder, lalu buka folder tersebut di VS Code (File > Open Folder).

Ubah PRD menjadi File Markdown (PRD.md):

Buat file baru bernama PRD.md di root folder projek Anda.

Copy-paste isi dari file PRD_Sistem_E-Presensi_Sekolah.docx ke dalam file PRD.md tersebut. (File markdown sangat disukai dan mudah dibaca oleh GitHub Copilot).

Buka Panel GitHub Copilot Chat (Ctrl + Alt + I atau Cmd + Alt + I).

Cara Kerja Prompt di GitHub Copilot Chat
Copilot Chat menggunakan fitur konteks seperti:

@workspace : Memberitahu Copilot untuk menganalisis seluruh struktur kode di folder projek Anda.

#file:PRD.md : Merujuk langsung ke file PRD agar Copilot membaca ketentuannya.

#file:nama_file_anda.php/js : Merujuk ke file kode spesifik.

Contoh Prompt Efektif untuk GitHub Copilot
Karena Copilot paling efektif jika mengerjakan per modul / per langkah, gunakan prompt bertahap berikut:

1. Tahap 1: Analisis & Perubahan Database
@workspace Berdasarkan instruksi pada #file:PRD.md, analisis struktur database yang ada di projek ini saat ini. Apa saja tabel atau file migration/model yang harus saya tambahkan/ubah agar sistem mendukung dua role (Siswa dan Wali Kelas)? Berikan contoh kode migration dan modelnya.

2. Tahap 2: Autentikasi & Middleware (Role-Based Access Control)
@workspace Tolong buatkan middleware/guard untuk membatasi akses halaman Wali Kelas berdasarkan #file:PRD.md. Pastikan hanya user dengan role 'wali_kelas' yang bisa mengakses rute tersebut.

3. Tahap 3: Membuat Fitur/Controller Spesifik (Misal: Verifikasi Izin)
(Buka file controller yang ingin diisi, lalu jalankan prompt ini)

@workspace Buka #file:PRD.md pada bagian 'Verifikasi Izin'. Tolong buatkan method/fungsi pada controller ini untuk menyetujui (approve) dan menolak (reject) pengajuan izin dari siswa.

4. Tahap 4: Mengintegrasikan UI / Tampilan
Tolong buatkan komponen UI/view untuk Dashboard Wali Kelas sesuai kebutuhan di #file:PRD.md (menampilkan ringkasan siswa hadir, izin, sakit, dan alpha hari ini). Gunakan styling/framework CSS yang sudah terpasang di projek ini.

Tips Penting Menggunakan GitHub Copilot:
Kerjakan Bertahap (Modular): Jangan minta Copilot membuat seluruh aplikasi sekaligus dalam satu prompt. Selesaikan Database -> Middleware -> Controller -> View secara berurutan.

Manfaatkan Inline Chat (Ctrl + I / Cmd + I): Anda bisa memblok baris kode tertentu di editor, tekan Ctrl + I, lalu ketik instruksi singkat (misal: "Ubah fungsi ini agar bisa menerima input dari Wali Kelas sesuai #file:PRD.md").

Hapus File PRD.md Sebelum Production: Setelah projek selesai, jangan lupa menghapus file PRD.md atau memasukkannya ke .gitignore agar tidak terikut saat deploy.

PRODUCT REQUIREMENTS DOCUMENT (PRD)

SISTEM E-PRESENSI SEKOLAH

1. Ringkasan Produk (Product Overview)

Sistem E-Presensi Sekolah adalah aplikasi berbasis web responsif yang dirancang untuk mendigitalkan proses pencatatan kehadiran siswa. Produk ini bertujuan untuk meminimalisir rekapitulasi manual, memberikan data kehadiran secara real-time, dan mempermudah proses pengajuan serta verifikasi izin/sakit.

Tujuan Bisnis:

•   Mencapai akurasi data kehadiran hingga 99% tanpa manipulasi.

•   Mengurangi waktu rekapitulasi absen oleh wali kelas dari hitungan jam menjadi hitungan menit.

2. Profil Pengguna (User Personas)

•   Siswa: Pengguna aktif harian yang membutuhkan antarmuka cepat (mobile-friendly) untuk melakukan absensi (check-in/check-out) dan mengajukan izin saat tidak bisa hadir.

•   Wali Kelas: Pengguna dengan peran pengawas yang membutuhkan dashboard komprehensif untuk memantau status kelas, memvalidasi surat izin, dan mencetak laporan bulanan.

3. Kebutuhan Fungsional (User Stories & Acceptance Criteria)

Modul Utama Pengguna    User Story  Kriteria Penerimaan (Acceptance Criteria)

Autentikasi Semua   Sebagai pengguna, saya ingin masuk ke sistem agar bisa mengakses dasbor saya.   Login menggunakan NISN/NIP dan password. Sistem mengarahkan ke dasbor yang sesuai dengan role.

Presensi Harian Siswa   Sebagai siswa, saya ingin menekan tombol check-in agar kehadiran saya tercatat. Tombol check-in hanya aktif pada jam yang ditentukan (misal: 06:00 - 07:15). Menyimpan timestamp otomatis.

Pengajuan Izin  Siswa   Sebagai siswa, saya ingin mengunggah surat dokter agar status saya menjadi "Sakit". Form izin mewajibkan unggah bukti (JPG/PDF). Status awal adalah "Menunggu Verifikasi". Bisa dibatalkan sebelum diproses.

Dashboard Kelas Wali Kelas  Sebagai wali kelas, saya ingin melihat ringkasan absen hari ini agar tahu siapa yang tidak hadir.   Terdapat ringkasan visual (angka/grafik) jumlah Hadir, Izin, Sakit, Alpha hari ini. Terdapat daftar nama siswa beserta statusnya.

Verifikasi Izin Wali Kelas  Sebagai wali kelas, saya ingin menyetujui/menolak pengajuan izin agar data absensi valid.   Terdapat tombol "Setujui" dan "Tolak". Jika disetujui, status absensi siswa otomatis berubah di hari tersebut.

Koreksi Data    Wali Kelas  Sebagai wali kelas, saya ingin mengubah status alpha menjadi hadir jika ada error pada sistem siswa.    Wali kelas dapat mengedit status absen harian siswa beserta kolom catatan/alasan koreksi.

Laporan Wali Kelas  Sebagai wali kelas, saya ingin mengunduh rekap absensi agar bisa diserahkan ke sekolah. Terdapat filter tanggal/bulan. Tombol "Export" menghasilkan dokumen Excel (.xlsx) atau PDF yang rapi.

4. Kebutuhan Non-Fungsional

•   Aksesibilitas (UI/UX): Antarmuka utama untuk POV Siswa harus dirancang secara mobile-first, mengingat mayoritas siswa akan mengakses melalui smartphone.

•   Keamanan Data (Audit Trail): Sistem tidak menyediakan fungsi Delete untuk data presensi yang sudah divalidasi. Setiap koreksi data yang dilakukan oleh Wali Kelas harus meninggalkan jejak (log) siapa dan kapan data tersebut diubah.

•   Performa: Waktu muat (load time) untuk proses check-in tidak boleh lebih dari 3 detik untuk mencegah penumpukan/antrean digital di jam masuk sekolah.

5. Asumsi & Batasan (Out of Scope)

•   Fase MVP (Minimum Viable Product): Tidak menggunakan fitur GPS/Geofencing atau deteksi wajah (Face Recognition) pada rilis pertama untuk menekan biaya server. Check-in murni berbasis waktu (timestamp).

•   Manajemen Data Induk: Penambahan siswa baru, kenaikan kelas, dan penentuan wali kelas tidak dilakukan di modul ini, melainkan dipegang penuh oleh akun Super Admin (berada di luar cakupan PRD ini).





tolong rapikan hassil copy-paste document menjadi file .md yang bagus

Your Markdown file is ready

MD icon
PRD
 MD 
Berikut adalah isi file Markdown (PRD.md) yang sudah dirapikan dengan struktur heading, tabel, list, dan pembatas yang bersih:

Markdown
# PRODUCT REQUIREMENTS DOCUMENT (PRD)
## SISTEM E-PRESENSI SEKOLAH

---

### 1. Ringkasan Produk (Product Overview)
Sistem E-Presensi Sekolah adalah aplikasi berbasis web responsif yang dirancang untuk mendigitalkan proses pencatatan kehadiran siswa. Produk ini bertujuan untuk meminimalisir rekapitulasi manual, memberikan data kehadiran secara *real-time*, dan mempermudah proses pengajuan serta verifikasi izin/sakit.

**Tujuan Bisnis:**
* Mencapai akurasi data kehadiran hingga 99% tanpa manipulasi.
* Mengurangi waktu rekapitulasi absen oleh wali kelas dari hitungan jam menjadi hitungan menit.

---

### 2. Profil Pengguna (User Personas)
* **Siswa:** Pengguna aktif harian yang membutuhkan antarmuka cepat (*mobile-friendly*) untuk melakukan absensi (*check-in/check-out*) dan mengajukan izin saat tidak bisa hadir.
* **Wali Kelas:** Pengguna dengan peran pengawas yang membutuhkan *dashboard* komprehensif untuk memantau status kelas, memvalidasi surat izin, dan mencetak laporan bulanan.

---

### 3. Kebutuhan Fungsional (User Stories & Acceptance Criteria)

| Modul Utama | Pengguna | User Story | Kriteria Penerimaan (Acceptance Criteria) |
| :--- | :--- | :--- | :--- |
| **Autentikasi** | Semua | Sebagai pengguna, saya ingin masuk ke sistem agar bisa mengakses dasbor saya. | Login menggunakan NISN/NIP dan *password*. Sistem mengarahkan ke dasbor yang sesuai dengan *role*. |
| **Presensi Harian** | Siswa | Sebagai siswa, saya ingin menekan tombol *check-in* agar kehadiran saya tercatat. | Tombol *check-in* hanya aktif pada jam yang ditentukan (misal: 06:00 - 07:15). Menyimpan *timestamp* otomatis. |
| **Pengajuan Izin** | Siswa | Sebagai siswa, saya ingin mengunggah surat dokter agar status saya menjadi "Sakit". | Form izin mewajibkan unggah bukti (JPG/PDF). Status awal adalah "Menunggu Verifikasi". Bisa dibatalkan sebelum diproses. |
| **Dashboard Kelas** | Wali Kelas | Sebagai wali kelas, saya ingin melihat ringkasan absen hari ini agar tahu siapa yang tidak hadir. | Terdapat ringkasan visual (angka/grafik) jumlah Hadir, Izin, Sakit, Alpha hari ini. Terdapat daftar nama siswa beserta statusnya. |
| **Verifikasi Izin** | Wali Kelas | Sebagai wali kelas, saya ingin menyetujui/menolak pengajuan izin agar data absensi valid. | Terdapat tombol "Setujui" dan "Tolak". Jika disetujui, status absensi siswa otomatis berubah di hari tersebut. |
| **Koreksi Data** | Wali Kelas | Sebagai wali kelas, saya ingin mengubah status alpha menjadi hadir jika ada *error* pada sistem siswa. | Wali kelas dapat mengedit status absen harian siswa beserta kolom catatan/alasan koreksi. |
| **Laporan** | Wali Kelas | Sebagai wali kelas, saya ingin mengunduh rekap absensi agar bisa diserahkan ke sekolah. | Terdapat filter tanggal/bulan. Tombol "Export" menghasilkan dokumen Excel (.xlsx) atau PDF yang rapi. |

---

### 4. Kebutuhan Non-Fungsional
* **Aksesibilitas (UI/UX):** Antarmuka utama untuk POV Siswa harus dirancang secara *mobile-first*, mengingat mayoritas siswa akan mengakses melalui *smartphone*.
* **Keamanan Data (Audit Trail):** Sistem tidak menyediakan fungsi **Delete** untuk data presensi yang sudah divalidasi. Setiap koreksi data yang dilakukan oleh Wali Kelas harus meninggalkan jejak (*log*) siapa dan kapan data tersebut diubah.
* **Performa:** Waktu muat (*load time*) untuk proses *check-in* tidak boleh lebih dari 3 detik untuk mencegah penumpukan/antrean digital di jam masuk sekolah.

---

### 5. Asumsi & Batasan (Out of Scope)
* **Fase MVP (Minimum Viable Product):** Tidak menggunakan fitur GPS/Geofencing atau deteksi wajah (*Face Recognition*) pada rilis pertama untuk menekan biaya server. *Check-in* murni berbasis waktu (*timestamp*).
* **Manajemen Data Induk:** Penambahan siswa baru, kenaikan kelas, dan penentuan wali kelas tidak dilakukan di modul ini, melainkan dipegang penuh oleh akun **Super Admin** (berada di luar cakupan PRD ini).
File .md di atas bisa langsung Anda letakkan di root folder projek VS Code Anda dengan nama PRD.md agar mudah dibaca oleh GitHub Copilot.

Gemini is AI and can make mistakes.






Structuring the Document Outline
# PRODUCT REQUIREMENTS DOCUMENT (PRD)
## SISTEM E-PRESENSI SEKOLAH

---

### 1. Ringkasan Produk (Product Overview)
Sistem E-Presensi Sekolah adalah aplikasi berbasis web responsif yang dirancang untuk mendigitalkan proses pencatatan kehadiran siswa. Produk ini bertujuan untuk meminimalisir rekapitulasi manual, memberikan data kehadiran secara *real-time*, dan mempermudah proses pengajuan serta verifikasi izin/sakit.

**Tujuan Bisnis:**
* Mencapai akurasi data kehadiran hingga 99% tanpa manipulasi.
* Mengurangi waktu rekapitulasi absen oleh wali kelas dari hitungan jam menjadi hitungan menit.

---

### 2. Profil Pengguna (User Personas)
* **Siswa:** Pengguna aktif harian yang membutuhkan antarmuka cepat (*mobile-friendly*) untuk melakukan absensi (*check-in/check-out*) dan mengajukan izin saat tidak bisa hadir.
* **Wali Kelas:** Pengguna dengan peran pengawas yang membutuhkan *dashboard* komprehensif untuk memantau status kelas, memvalidasi surat izin, dan mencetak laporan bulanan.

---

### 3. Kebutuhan Fungsional (User Stories & Acceptance Criteria)

| Modul Utama | Pengguna | User Story | Kriteria Penerimaan (Acceptance Criteria) |
| :--- | :--- | :--- | :--- |
| **Autentikasi** | Semua | Sebagai pengguna, saya ingin masuk ke sistem agar bisa mengakses dasbor saya. | Login menggunakan NISN/NIP dan *password*. Sistem mengarahkan ke dasbor yang sesuai dengan *role*. |
| **Presensi Harian** | Siswa | Sebagai siswa, saya ingin menekan tombol *check-in* agar kehadiran saya tercatat. | Tombol *check-in* hanya aktif pada jam yang ditentukan (misal: 06:00 - 07:15). Menyimpan *timestamp* otomatis. |
| **Pengajuan Izin** | Siswa | Sebagai siswa, saya ingin mengunggah surat dokter agar status saya menjadi "Sakit". | Form izin mewajibkan unggah bukti (JPG/PDF). Status awal adalah "Menunggu Verifikasi". Bisa dibatalkan sebelum diproses. |
| **Dashboard Kelas** | Wali Kelas | Sebagai wali kelas, saya ingin melihat ringkasan absen hari ini agar tahu siapa yang tidak hadir. | Terdapat ringkasan visual (angka/grafik) jumlah Hadir, Izin, Sakit, Alpha hari ini. Terdapat daftar nama siswa beserta statusnya. |
| **Verifikasi Izin** | Wali Kelas | Sebagai wali kelas, saya ingin menyetujui/menolak pengajuan izin agar data absensi valid. | Terdapat tombol "Setujui" dan "Tolak". Jika disetujui, status absensi siswa otomatis berubah di hari tersebut. |
| **Koreksi Data** | Wali Kelas | Sebagai wali kelas, saya ingin mengubah status alpha menjadi hadir jika ada *error* pada sistem siswa. | Wali kelas dapat mengedit status absen harian siswa beserta kolom catatan/alasan koreksi. |
| **Laporan** | Wali Kelas | Sebagai wali kelas, saya ingin mengunduh rekap absensi agar bisa diserahkan ke sekolah. | Terdapat filter tanggal/bulan. Tombol "Export" menghasilkan dokumen Excel (.xlsx) atau PDF yang rapi. |

---

### 4. Kebutuhan Non-Fungsional
* **Aksesibilitas (UI/UX):** Antarmuka utama untuk POV Siswa harus dirancang secara *mobile-first*, mengingat mayoritas siswa akan mengakses melalui *smartphone*.
* **Keamanan Data (Audit Trail):** Sistem tidak menyediakan fungsi **Delete** untuk data presensi yang sudah divalidasi. Setiap koreksi data yang dilakukan oleh Wali Kelas harus meninggalkan jejak (*log*) siapa dan kapan data tersebut diubah.
* **Performa:** Waktu muat (*load time*) untuk proses *check-in* tidak boleh lebih dari 3 detik untuk mencegah penumpukan/antrean digital di jam masuk sekolah.

---

### 5. Asumsi & Batasan (Out of Scope)
* **Fase MVP (Minimum Viable Product):** Tidak menggunakan fitur GPS/Geofencing atau deteksi wajah (*Face Recognition*) pada rilis pertama untuk menekan biaya server. *Check-in* murni berbasis waktu (*timestamp*).
* **Manajemen Data Induk:** Penambahan siswa baru, kenaikan kelas, dan penentuan wali kelas tidak dilakukan di modul ini, melainkan dipegang penuh oleh akun **Super Admin** (berada di luar cakupan PRD ini).
PRD.md
Displaying PRD.md.
