<?php
// Koneksi Database
$koneksi = mysqli_connect("localhost", "root", "", "phpnative");

function query($query)
{
    // Koneksi database
    global $koneksi;

    $result = mysqli_query($koneksi, $query);

    $rows = [];

    // mengambil semua data dalam bentuk array
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

function tambah($data)
{
    global $koneksi;

    $nis = htmlspecialchars($data['nis']);
    $nama = htmlspecialchars($data['nama']);
    $tmpt_Lahir = htmlspecialchars($data['tmpt_Lahir']);
    $tgl_Lahir = $data['tgl_Lahir'];
    $jekel = $data['jekel'];
    $jurusan = $data['jurusan'];
    $email = htmlspecialchars($data['email']);
    $ipk = htmlspecialchars($data['ipk']);
    $gambar = upload();
    $alamat = htmlspecialchars($data['alamat']);

    if (!$gambar) {
        return false;
    }

    $sql = "INSERT INTO siswa VALUES ('$nis','$nama','$tmpt_Lahir','$tgl_Lahir','$jekel','$jurusan','$email','$ipk','$gambar','$alamat')";

    mysqli_query($koneksi, $sql);

    return mysqli_affected_rows($koneksi);
}

// Membuat fungsi hapus
function hapus($nis)
{
    global $koneksi;

    mysqli_query($koneksi, "DELETE FROM siswa WHERE nis = $nis");
    return mysqli_affected_rows($koneksi);
}

// Membuat fungsi ubah
function ubah($data)
{
    global $koneksi;

    $nis = $data['nis'];
    $nama = htmlspecialchars($data['nama']);
    $tmpt_Lahir = htmlspecialchars($data['tmpt_Lahir']);
    $tgl_Lahir = $data['tgl_Lahir'];
    $jekel = $data['jekel'];
    $jurusan = $data['jurusan'];
    $email = htmlspecialchars($data['email']);
    $ipk = htmlspecialchars($data['ipk']);
    $alamat = htmlspecialchars($data['alamat']);

    $gambarLama = $data['gambarLama'];

    if ($_FILES['gambar']['error'] === 4) {
        $gambar = $gambarLama;
    } else {
        $gambar = upload();
    }

    $sql = "UPDATE siswa SET nama = '$nama', tmpt_Lahir = '$tmpt_Lahir', tgl_Lahir = '$tgl_Lahir', jekel = '$jekel', jurusan = '$jurusan', email = '$email', ipk = '$ipk', gambar = '$gambar', alamat = '$alamat' WHERE nis = $nis";

    mysqli_query($koneksi, $sql);

    return mysqli_affected_rows($koneksi);
}

// Membuat fungsi upload gambar
function upload()
{
    // Syarat
    $namaFile = $_FILES['gambar']['name'];
    $ukuranFile = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmpName = $_FILES['gambar']['tmp_name'];

    if ($error === 4) {
        echo "<script>alert('Pilih gambar terlebih dahulu!');</script>";
        return false;
    }

    // format atau ekstensi yang diperbolehkan untuk upload gambar adalah
    $extValid = ['jpg', 'jpeg', 'png'];
    $ext = explode('.', $namaFile);
    $ext = strtolower(end($ext));

    if (!in_array($ext, $extValid)) {
        echo "<script>alert('Yang anda upload bukanlah gambar!');</script>";
        return false;
    }

    if ($ukuranFile > 3000000) {
        echo "<script>alert('Ukuran gambar anda terlalu besar!');</script>";
        return false;
    }

    $namaFileBaru = uniqid();
    $namaFileBaru .= '.';
    $namaFileBaru .= $ext;

    // memindahkan file ke dalam folde img dengan nama baru
    move_uploaded_file($tmpName, 'img/' . $namaFileBaru);

    return $namaFileBaru;
}
