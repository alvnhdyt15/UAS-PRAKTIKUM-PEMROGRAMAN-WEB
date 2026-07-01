<?php
// app/controllers/KomunitasController.php

class KomunitasController extends Controller {
    public function index() {
        $data = [
            'title' => 'Komunitas & Notifikasi - ' . APP_NAME
        ];
        $this->view('layouts/header', $data);
        $this->view('komunitas/index', $data);
        $this->view('layouts/footer');
    }
}
