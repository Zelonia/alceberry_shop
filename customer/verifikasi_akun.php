<?php
include("includes/db.php");
// verifikasi_akun.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data customer_confirm_code dari formulir
    $customer_confirm_code = $_POST['customer_confirm_code'];

    // Lakukan validasi atau proses verifikasi akun sesuai kebutuhan Anda
    // ...

    // Ambil data customer_confirm_code dari formulir

// Fungsi untuk memeriksa apakah verifikasi berhasil (sesuaikan dengan logika verifikasi Anda)
function verifikasiBerhasil($customer_confirm_code) {
    global $con; // Pastikan koneksi database sudah dibuat atau di-include di sini

    // Logika verifikasi akun, misalnya dengan memeriksa kecocokan dengan data di database
    $query = "SELECT * FROM customers WHERE customer_confirm_code =  $customer_confirm_code";
    $stmt = mysqli_prepare($con, $query);

    // Check if the preparation of the statement is successful
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $customer_confirm_code);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        // Sesuaikan logika verifikasi di sini
        // Contoh: jika ada baris data, anggap berhasil
        if (mysqli_num_rows($result) > 0) {
            return true; // Verifikasi berhasil
        }
    }

    return false; // Verifikasi gagal
}

// Panggil fungsi verifikasiBerhasil
if (verifikasiBerhasil($customer_confirm_code)) {
    // Jika verifikasi berhasil, lakukan pembaruan kolom customer_confirm_code
    $update_query = "UPDATE customers SET customer_confirm_code = '' WHERE customer_confirm_code =  $customer_confirm_code";
    $update_stmt = mysqli_prepare($con, $update_query);

    if ($update_stmt) {
        mysqli_stmt_bind_param($update_stmt, "s", $customer_confirm_code);
        mysqli_stmt_execute($update_stmt);

        // Verifikasi berhasil, lakukan tindakan lanjutan jika perlu
        echo "Verifikasi berhasil. Data telah diperbarui.";
    } else {
        echo "Error updating data: " . mysqli_error($con);
    }
} else {
    // Verifikasi gagal, berikan pesan atau lakukan tindakan lain jika perlu
    echo "Verifikasi gagal. Data tidak diperbarui.";
}


    // Jika verifikasi berhasil, lakukan pembaruan kolom customer_confirm_code
    if (verifikasiBerhasil($customer_confirm_code)) {
        // Lakukan pembaruan di database
        require_once("includes/db.php"); // Sesuaikan dengan konfigurasi koneksi database Anda

        $update_customer = "UPDATE customers SET customer_confirm_code = '' WHERE customer_confirm_code = $customer_confirm_code";
        $run_confirm = mysqli_prepare($con, $update_customer);
        mysqli_stmt_bind_param($run_confirm, "s", $customer_confirm_code);
        mysqli_stmt_execute($run_confirm);

        // Tampilkan pesan sukses atau redirect ke halaman tertentu
        echo "Verifikasi akun berhasil! Email telah dikonfirmasi.";
    } else {
        // Tampilkan pesan kesalahan atau redirect ke halaman tertentu jika verifikasi gagal
        echo "Verifikasi akun gagal! Silakan coba lagi atau hubungi dukungan pelanggan.";
    }
} else {
    // Jika akses langsung ke halaman ini tanpa POST, redirect atau tampilkan pesan kesalahan
    echo "Akses tidak valid!";
}

// Fungsi untuk memeriksa apakah verifikasi berhasil (sesuaikan dengan logika verifikasi Anda)
function verifikasiBerhasil($customer_confirm_code) {
    include("includes/db.php");
    $query = "SELECT * FROM customers WHERE customer_confirm_code = $customer_confirm_code";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $customer_confirm_code);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    // Sesuaikan logika verifikasi di sini
    // Contoh: jika ada baris data, anggap berhasil
    if (mysqli_num_rows($result) > 0) {
        return true; // Verifikasi berhasil
    }

    return false; // Verifikasi gagal
}
?>
