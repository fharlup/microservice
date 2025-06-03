<?php
header('Content-Type: application/json');

// Koneksi MySQL HospitalService
$host = 'mysql-hospital';
$db = 'hospital_db';
$user = 'root';
$pass = 'Bookselfpakemas123';

$pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$request = $_SERVER['REQUEST_URI'];

// Fungsi bantu response json
function sendJson($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data);
    exit();
}

// ======================
// ROUTING
// ======================
switch ($method) {

    // --- GET ---
    case 'GET':
        if ($path == '/patients') {
            $result = $GLOBALS['mysqli']->query("SELECT * FROM patients");
            $patients = [];
            while($row = $result->fetch_assoc()) {
                $patients[] = $row;
            }
            sendJson(['status'=>'success', 'data'=>$patients]);
        } 
        elseif (preg_match('/\/patients\/(\d+)/', $path, $matches)) {
            $id = $matches[1];
            $stmt = $mysqli->prepare("SELECT * FROM patients WHERE id=?");
            $stmt->bind_param("i",$id);
            $stmt->execute();
            $res = $stmt->get_result();
            if($res->num_rows > 0) {
                sendJson(['status'=>'success', 'data'=>$res->fetch_assoc()]);
            } else {
                sendJson(['status'=>'error','message'=>'Patient not found'],404);
            }
        }
        elseif ($path == '/consultations') {
            $result = $mysqli->query("SELECT * FROM consultations");
            $data = [];
            while($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            sendJson(['status'=>'success','data'=>$data]);
        }
        elseif ($path == '/doctors') {
            $result = $mysqli->query("SELECT * FROM doctors");
            $doctors = [];
            while($row = $result->fetch_assoc()) {
                $doctors[] = $row;
            }
            sendJson(['status'=>'success','data'=>$doctors]);
        }
        elseif ($path == '/prescriptions') {
            $result = $mysqli->query("SELECT * FROM prescriptions");
            $prescriptions = [];
            while($row = $result->fetch_assoc()) {
                $prescriptions[] = $row;
            }
            sendJson(['status'=>'success','data'=>$prescriptions]);
        }
        // Integrasi: ambil data obat dari ApotekService
        elseif ($path == '/obat-from-apotek') {
            $obatData = file_get_contents('http://apotek/obat');
            sendJson(json_decode($obatData, true));
        }
        else {
            sendJson(['status'=>'error','message'=>'Endpoint not found'],404);
        }
        break;

    // --- POST ---
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            sendJson(['status'=>'error','message'=>'Invalid JSON'],400);
        }

        if ($path == '/patients') {
            $stmt = $mysqli->prepare("INSERT INTO patients (name, age, address, phone) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("siss", $data['name'], $data['age'], $data['address'], $data['phone']);
            $stmt->execute();
            $id = $stmt->insert_id;
            sendJson(['status'=>'success', 'data'=>array_merge(['id'=>$id], $data)],201);
        }
        elseif ($path == '/consultations') {
            // Validasi sederhana
            $required = ['patient_id','doctor_id','symptoms','diagnosis','date'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    sendJson(['status'=>'error','message'=>"Field $field is required"],400);
                }
            }
            $stmt = $mysqli->prepare("INSERT INTO consultations (patient_id, doctor_id, symptoms, diagnosis, date) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iisss", $data['patient_id'], $data['doctor_id'], $data['symptoms'], $data['diagnosis'], $data['date']);
            $stmt->execute();
            $id = $stmt->insert_id;
            sendJson(['status'=>'success','data'=>array_merge(['id'=>$id], $data)],201);
        }
        elseif ($path == '/doctors') {
            $stmt = $mysqli->prepare("INSERT INTO doctors (name, specialization) VALUES (?, ?)");
            $stmt->bind_param("ss", $data['name'], $data['specialization']);
            $stmt->execute();
            $id = $stmt->insert_id;
            sendJson(['status'=>'success','data'=>array_merge(['id'=>$id], $data)],201);
        }
        elseif ($path == '/prescriptions') {
            $stmt = $mysqli->prepare("INSERT INTO prescriptions (consultation_id, medicine_name, dosage) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $data['consultation_id'], $data['medicine_name'], $data['dosage']);
            $stmt->execute();
            $id = $stmt->insert_id;
            sendJson(['status'=>'success','data'=>array_merge(['id'=>$id], $data)],201);
        }
        else {
            sendJson(['status'=>'error','message'=>'Endpoint not found'],404);
        }
        break;

    default:
        sendJson(['status'=>'error','message'=>'Method not allowed'],405);
}
