<?php
header('Content-Type: application/json');

// Koneksi MySQL ApotekService$host = 'mysql-apotek';
$db = 'apotek_db';
$user = 'root';
$pass = 'Bookselfpakemas123';

$pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$request = $_SERVER['REQUEST_URI'];

function sendJson($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data);
    exit();
}

switch ($method) {
    case 'GET':
        if ($path == '/obat') {
            $result = $GLOBALS['mysqli']->query("SELECT * FROM obat");
            $data = [];
            while($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            sendJson(['status'=>'success','data'=>$data]);
        }
        elseif ($path == '/suppliers') {
            $result = $GLOBALS['mysqli']->query("SELECT * FROM suppliers");
            $data = [];
            while($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            sendJson(['status'=>'success','data'=>$data]);
        }
        elseif ($path == '/orders') {
            $result = $GLOBALS['mysqli']->query("SELECT * FROM orders");
            $data = [];
            while($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            sendJson(['status'=>'success','data'=>$data]);
        }
        elseif ($path == '/purchase-history') {
            $result = $GLOBALS['mysqli']->query("SELECT * FROM purchase_history");
            $data = [];
            while($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            sendJson(['status'=>'success','data'=>$data]);
        }
        // Integrasi: ambil data pasien dari HospitalService
        elseif ($path == '/patients-from-hospital') {
            $patientsData = file_get_contents('http://hospital/patients');
            sendJson(json_decode($patientsData,true));
        }
        else {
            sendJson(['status'=>'error','message'=>'Endpoint not found'],404);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            sendJson(['status'=>'error','message'=>'Invalid JSON'],400);
        }

        if ($path == '/obat') {
            $stmt = $GLOBALS['mysqli']->prepare("INSERT INTO obat (name, stock, price) VALUES (?, ?, ?)");
            $stmt->bind_param("sid", $data['name'], $data['stock'], $data['price']);
            $stmt->execute();
            $id = $stmt->insert_id;
            sendJson(['status'=>'success', 'data'=>array_merge(['id'=>$id], $data)],201);
        }
        elseif ($path == '/suppliers') {
            $stmt = $GLOBALS['mysqli']->prepare("INSERT INTO suppliers (name, contact) VALUES (?, ?)");
            $stmt->bind_param("ss", $data['name'], $data['contact']);
            $stmt->execute();
            $id = $stmt->insert_id;
            sendJson(['status'=>'success', 'data'=>array_merge(['id'=>$id], $data)],201);
        }
        elseif ($path == '/orders') {
            $stmt = $GLOBALS['mysqli']->prepare("INSERT INTO orders (obat_id, quantity, order_date) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $data['obat_id'], $data['quantity'], $data['order_date']);
            $stmt->execute();
            $id = $stmt->insert_id;
            sendJson(['status'=>'success', 'data'=>array_merge(['id'=>$id], $data)],201);
        }
        elseif ($path == '/purchase-history') {
            $stmt = $GLOBALS['mysqli']->prepare("INSERT INTO purchase_history (patient_id, medicine_name, quantity, purchase_date) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isis", $data['patient_id'], $data['medicine_name'], $data['quantity'], $data['purchase_date']);
            $stmt->execute();
            $id = $stmt->insert_id;
            sendJson(['status'=>'success', 'data'=>array_merge(['id'=>$id], $data)],201);
        }
        else {
            sendJson(['status'=>'error','message'=>'Endpoint not found'],404);
        }
        break;

    default:
        sendJson(['status'=>'error','message'=>'Method not allowed'],405);
}
