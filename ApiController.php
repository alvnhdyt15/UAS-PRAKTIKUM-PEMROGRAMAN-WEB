<?php
// app/controllers/ApiController.php

class ApiController extends Controller {
    
    // Mock API for BMKG Weather
    public function cuaca($kabupaten = 'bangkalan') {
        header('Content-Type: application/json');
        
        $mock_data = [
            'status' => 'success',
            'data' => [
                'kabupaten' => ucfirst($kabupaten),
                'cuaca' => 'Cerah Berawan',
                'suhu' => rand(28, 34),
                'kelembapan' => rand(60, 90),
                'curah_hujan' => rand(0, 50), // mm
                'kecepatan_angin' => rand(10, 30), // km/h
                'tinggi_gelombang' => rand(1, 3), // meter
                'timestamp' => date('Y-m-d H:i:s')
            ]
        ];
        
        echo json_encode($mock_data);
    }

    // Mock API for WhatsApp Notification sending
    public function send_wa() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $no_hp = filter_input(INPUT_POST, 'no_hp', FILTER_SANITIZE_STRING);
            $pesan = filter_input(INPUT_POST, 'pesan', FILTER_SANITIZE_STRING);
            
            // Here we would normally call the Free WhatsApp Gateway API
            // For now, we return a mock success response
            
            $response = [
                'status' => 'success',
                'message' => 'Pesan berhasil dikirim ke ' . $no_hp,
                'gateway_provider' => 'Free Mock Gateway',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            echo json_encode($response);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid Request Method']);
        }
    }
}
