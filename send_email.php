<?php

// Konfigurasi Tujuan
$recipient = "ghaniacatcare@gmail.com";
$phone_number = "081314110921"; // Untuk referensi internal

// Hanya proses jika request adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitasi dan Pengambilan Data
    $nama_pemilik = htmlspecialchars(trim($_POST['nama_pemilik'] ?? 'Tidak Diisi'));
    $nama_kucing  = htmlspecialchars(trim($_POST['nama_kucing'] ?? 'Tidak Diisi'));
    $alamat       = htmlspecialchars(trim($_POST['alamat'] ?? 'Tidak Diisi'));
    $telepon      = htmlspecialchars(trim($_POST['telepon'] ?? 'Tidak Diisi'));
    $email        = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $layanan      = htmlspecialchars(trim($_POST['pilihan_layanan'] ?? 'Tidak Memilih'));
    $pesan        = htmlspecialchars(trim($_POST['pesan'] ?? 'Tidak Ada Pesan Tambahan'));

    // Validasi Email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Redirect kembali dengan status error
        header("Location: index.html#contact?status=error");
        exit;
    }

    // Susun Subjek dan Konten Email
    $subject = "GHANIA CAT CARE: Permintaan Layanan Baru (" . ucwords($layanan) . ")";

    $email_content = "Halo Tim GHANIA CAT CARE,\n\n";
    $email_content .= "Anda menerima pesan permintaan layanan dari website:\n";
    $email_content .= "-------------------------------------------------------\n";
    $email_content .= "Nama Pemilik: " . $nama_pemilik . "\n";
    $email_content .= "Nama Kucing: " . $nama_kucing . "\n";
    $email_content .= "Email Pengirim: " . $email . "\n";
    $email_content .= "No. Telpon: " . $telepon . "\n";
    $email_content .= "Alamat Lengkap: " . $alamat . "\n";
    $email_content .= "Pilihan Layanan: " . ucwords(str_replace('_', ' ', $layanan)) . "\n";
    $email_content .= "-------------------------------------------------------\n";
    $email_content .= "Pesan Tambahan:\n" . $pesan . "\n";
    $email_content .= "-------------------------------------------------------\n";
    $email_content .= "Segera hubungi $telepon atau balas email ini ke $email.";

    // Susun Header Email (Penting untuk Reply-To)
    $email_headers = "From: GHANIA CAT CARE Website <noreply@ghaniacatcare.com>\r\n";
    $email_headers .= "Reply-To: " . $email . "\r\n";
    $email_headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Kirim Email
    if (mail($recipient, $subject, $email_content, $email_headers)) {
        // Pengiriman berhasil: Arahkan ke halaman kontak dengan pesan sukses
        header("Location: index.html#contact?status=success");
    } else {
        // Pengiriman gagal: Arahkan ke halaman kontak dengan pesan error
        header("Location: index.html#contact?status=error");
    }
    
    exit; // Pastikan eksekusi berhenti setelah redirect

} else {
    // Jika diakses tanpa POST, tolak akses
    http_response_code(403);
    echo "Akses terlarang.";
}

?>