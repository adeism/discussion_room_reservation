# Requirements
## Functional Requirement
Sistem harus mampu menerima input data anggota perpustakaan yang melakukan reservasi:
* Pengguna dapat menginput NIM
* Pengguna dapat menginput Nama Lengkap
* Pengguna dapat menginput Program Studi
* Pengguna dapat menginput Nomor Telepon

Sistem harus mampu menerima input data detail reservasi: 
* Pengguna dapat memilih Tanggal Booking
* Pengguna dapat memilih Jam Booking
* Pengguna dapat menginput surat peminjaman ruang (jika lebih dari 2 jam) 
    * Cari template surat peminjaman ruang
    * Pikirkan skenario dimana anggota perpustakaan yang hendak memesan ruang diskusi lebih dari 2 jam tetapi bentrok dengan jadwal yang sudah dipesan. Misal, ada yang ingin memesan selama sehari penuh untuk kebutuhan penting, tetapi di hari itu sudah ada beberapa kelompok yang memesan.
* Pengguna dapat menginput Jumlah Anggota
* Pengguna dapat menginput NIM masing-masing anggota (on progress)
* Pengguna dapat menginput Nama masing-masing anggota (on progress)
* Pengguna dapat menginput Kegiatan yang akan dilakukan
### Non-Functional Requirement
**Acceptance:**
* Sistem hanya akan mengizinkan pemesanan pada kelompok yang beranggotakan minimal 5 orang.
* Sistem hanya akan mengizinkan pemesanan pada kelompok yang beranggotakan maksimal 10 orang.
**Reliability:**
* Sistem hanya mengizinkan pengguna untuk memilih jadwal yang tersedia.
* Sistem harus mengizinkan pengguna untuk mendaftar kapanpun.
* Sistem harus dapat menampilkan nama anggota perpustakaan berdasarkan NIM mereka.
    * Fitur autocomplete
* Perhatikan bagaimana jika ada anggota perpustakaan yang mendaftarkan nama tanpa seizin orangnya.
* Sistem harus dapat mendeteksi hari libur.
**Security:**
* Sistem hanya mengizinkan pengguna untuk melakukan reservasi jika penggguna sudah menyetujui kebijakan.

**Catatan:**
* Perlu diperhatikan use case jika dosen atau selain mahasiswa yang mendaftar.
* Perlu diperhatikan spamming pendaftar.
