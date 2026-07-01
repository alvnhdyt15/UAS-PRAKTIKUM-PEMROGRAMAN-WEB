<?php
// app/controllers/LaporanController.php

class LaporanController extends Controller {

    public function create() {
        $data = [
            'title' => 'Lapor Kejadian - ' . APP_NAME
        ];
        $this->view('layouts/header', $data);
        $this->view('laporan/create', $data);
        $this->view('layouts/footer');
    }

    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/laporan/create');
            exit;
        }

        // Sanitize inputs
        $nama_pelapor    = filter_input(INPUT_POST, 'nama_pelapor', FILTER_SANITIZE_STRING);
        $no_hp           = filter_input(INPUT_POST, 'no_hp_pelapor', FILTER_SANITIZE_STRING);
        $jenis_bencana   = filter_input(INPUT_POST, 'jenis_bencana_id', FILTER_SANITIZE_NUMBER_INT);
        $tingkat         = filter_input(INPUT_POST, 'tingkat', FILTER_SANITIZE_STRING);
        $kabupaten       = filter_input(INPUT_POST, 'kabupaten', FILTER_SANITIZE_NUMBER_INT);
        $nama_lokasi     = filter_input(INPUT_POST, 'nama_lokasi', FILTER_SANITIZE_STRING);
        $deskripsi       = filter_input(INPUT_POST, 'deskripsi', FILTER_SANITIZE_STRING);
        $latitude        = filter_input(INPUT_POST, 'latitude', FILTER_SANITIZE_STRING);
        $longitude       = filter_input(INPUT_POST, 'longitude', FILTER_SANITIZE_STRING);

        // Handle file upload
        $foto_path = null;
        if (!empty($_FILES['foto']['name'])) {
            $uploadDir = '../public/uploads/laporan/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            if (in_array(strtolower($ext), $allowed) && $_FILES['foto']['size'] <= 5 * 1024 * 1024) {
                $filename = 'laporan_' . time() . '_' . uniqid() . '.' . $ext;
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadDir . $filename)) {
                    $foto_path = 'uploads/laporan/' . $filename;
                }
            }
        }

        // Save to DB (user_id = null for guest/public reports)
        $db = new Database();

        // Get logged in user_id
        $user_id = $_SESSION['user_id'] ?? null;

        $db->executeParams(
            "INSERT INTO laporan_masyarakat (user_id, jenis_bencana_id, deskripsi, foto, latitude, longitude, status) VALUES (?, ?, ?, ?, ?, ?, 'menunggu')",
            [$user_id, $jenis_bencana, "[{$nama_pelapor} | {$no_hp}] [{$nama_lokasi}] " . $deskripsi, $foto_path, $latitude ?: null, $longitude ?: null],
            "iissss"
        );

        // Redirect with success message
        header('Location: ' . BASE_URL . '/laporan/success');
        exit;
    }

    public function success() {
        $data = ['title' => 'Laporan Terkirim - ' . APP_NAME];
        $this->view('layouts/header', $data);
        $this->view('laporan/success', $data);
        $this->view('layouts/footer');
    }
}
