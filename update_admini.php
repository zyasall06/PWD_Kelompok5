<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'config/db.php';

header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => ''
];

// Cek koneksi database
if (!$conn) {
    $response['message'] = 'Koneksi database gagal';
    echo json_encode($response);
    exit;
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    $response['message'] = 'Sesi admin tidak valid';
    echo json_encode($response);
    exit;
}

$adminEmail = $_SESSION['admin_email'] ?? 'admin@youthreverfest.com';
$id = 1;
$stmt = $conn->prepare("SELECT id FROM admin_users WHERE email=? LIMIT 1");
if ($stmt) {
    $stmt->bind_param("s", $adminEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $id = (int) $result->fetch_assoc()['id'];
    }
    $stmt->close();
}

// Pastikan request POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Metode request tidak valid';
    echo json_encode($response);
    exit;
}

$action = $_POST['action'] ?? '';

switch ($action) {

    // ==========================
    // UPDATE PROFIL
    // ==========================
    case 'update_profile':

        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        if (empty($name)) {
            $response['message'] = 'Nama tidak boleh kosong';
            break;
        }

        $stmt = $conn->prepare("UPDATE admin_users SET name=?, phone=? WHERE id=?");

        if (!$stmt) {
            $response['message'] = $conn->error;
            break;
        }

        $stmt->bind_param("ssi", $name, $phone, $id);

        if ($stmt->execute()) {
            $_SESSION['admin_name'] = $name;
            $response['success'] = true;
            $response['message'] = 'Profil admin berhasil diperbarui';
        } else {
            $response['message'] = $stmt->error;
        }

        $stmt->close();

        break;

    // ==========================
    // UPDATE NAMA
    // ==========================
    case 'update_name':

        $name = trim($_POST['name'] ?? '');

        if (empty($name)) {
            $response['message'] = 'Nama tidak boleh kosong';
            break;
        }

        $stmt = $conn->prepare("UPDATE admin_users SET name=? WHERE id=?");

        if (!$stmt) {
            $response['message'] = $conn->error;
            break;
        }

        $stmt->bind_param("si", $name, $id);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Nama berhasil diperbarui';
        } else {
            $response['message'] = $stmt->error;
        }

        $stmt->close();

        break;

    // ==========================
    // UPDATE TELEPON
    // ==========================
    case 'update_phone':

        $phone = trim($_POST['phone'] ?? '');

        $stmt = $conn->prepare("UPDATE admin_users SET phone=? WHERE id=?");

        if (!$stmt) {
            $response['message'] = $conn->error;
            break;
        }

        $stmt->bind_param("si", $phone, $id);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Nomor telepon berhasil diperbarui';
        } else {
            $response['message'] = $stmt->error;
        }

        $stmt->close();

        break;

    // ==========================
    // UPLOAD FOTO
    // ==========================
    case 'upload_photo':

        if (!isset($_FILES['photo'])) {
            $response['message'] = 'File tidak ditemukan';
            break;
        }

        if ($_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            $response['message'] = 'Gagal upload file';
            break;
        }

        $uploadDir = 'assets/uploads/';

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        $fileExt = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

        if (!in_array($fileExt, $allowedExt)) {
            $response['message'] = 'Format file tidak didukung';
            break;
        }

        $newFileName =
            'profile_' .
            time() .
            '_' .
            rand(1000, 9999) .
            '.' .
            $fileExt;

        $targetFile = $uploadDir . $newFileName;

        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
            $response['message'] = 'Gagal menyimpan file';
            break;
        }

        $stmt = $conn->prepare("UPDATE admin_users SET photo=? WHERE id=?");

        if (!$stmt) {
            $response['message'] = $conn->error;
            break;
        }

        $stmt->bind_param("si", $targetFile, $id);

        if ($stmt->execute()) {

            $response['success'] = true;
            $response['message'] = 'Foto berhasil diperbarui';
            $response['photo_url'] = $targetFile;

        } else {

            $response['message'] = $stmt->error;

        }

        $stmt->close();

        break;

    default:
        $response['message'] = 'Action tidak valid';
        break;
}

echo json_encode($response);

$conn->close();
?>
