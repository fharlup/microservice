<?php
header('Content-Type: application/json');

$host = getenv('DB_HOST') ?: 'db_hospital';
$db   = getenv('DB_NAME') ?: 'hospital_db';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: 'a';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status'=>'error', 'message'=>'Database connection failed: '.$e->getMessage()]);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

function sendJson($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data);
    exit();
}

switch ($method) {
    case 'GET':
        if ($path === '/patients') {
            $stmt = $pdo->query("SELECT * FROM patients");
            $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
            sendJson(['status'=>'success', 'data'=>$patients]);
        }
        elseif (preg_match('/\/patients\/(\d+)/', $path, $matches)) {
            $id = $matches[1];
            $stmt = $pdo->prepare("SELECT * FROM patients WHERE id = ?");
            $stmt->execute([$id]);
            $patient = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($patient) {
                sendJson(['status'=>'success', 'data'=>$patient]);
            } else {
                sendJson(['status'=>'error', 'message'=>'Patient not found'], 404);
            }
        }
        elseif ($path === '/consultations') {
            $stmt = $pdo->query("SELECT * FROM consultations");
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            sendJson(['status'=>'success','data'=>$data]);
        }
        elseif ($path === '/doctors') {
            $stmt = $pdo->query("SELECT * FROM doctors");
            $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
            sendJson(['status'=>'success','data'=>$doctors]);
        }
        elseif ($path === '/prescriptions') {
            $stmt = $pdo->query("SELECT * FROM prescriptions");
            $prescriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            sendJson(['status'=>'success','data'=>$prescriptions]);
        }
        elseif ($path === '/obat-from-apotek') {
            $obatData = @file_get_contents('http://apotek_service/obat');
            if ($obatData === false) {
                sendJson(['status'=>'error','message'=>'Failed to fetch obat data from apotek service'], 500);
            }
            $json = json_decode($obatData, true);
            if ($json === null) {
                sendJson(['status'=>'error','message'=>'Invalid JSON from apotek service'], 500);
            }
            sendJson($json);
        }
        else {
            sendJson(['status'=>'error','message'=>'Endpoint not found'], 404);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            sendJson(['status'=>'error', 'message'=>'Invalid JSON'], 400);
        }

        try {
            if ($path === '/patients') {
                $stmt = $pdo->prepare("INSERT INTO patients (name, age, address, phone) VALUES (?, ?, ?, ?)");
                $stmt->execute([$data['name'], $data['age'], $data['address'], $data['phone']]);
                $id = $pdo->lastInsertId();
                sendJson(['status'=>'success', 'data'=>array_merge(['id'=>$id], $data)], 201);
            }
            elseif ($path === '/consultations') {
                $required = ['patient_id','doctor_id','symptoms','diagnosis','date'];
                foreach ($required as $field) {
                    if (empty($data[$field])) {
                        sendJson(['status'=>'error','message'=>"Field $field is required"],400);
                    }
                }
                $stmt = $pdo->prepare("INSERT INTO consultations (patient_id, doctor_id, symptoms, diagnosis, date) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$data['patient_id'], $data['doctor_id'], $data['symptoms'], $data['diagnosis'], $data['date']]);
                $id = $pdo->lastInsertId();
                sendJson(['status'=>'success', 'data'=>array_merge(['id'=>$id], $data)], 201);
            }
            elseif ($path === '/doctors') {
                $stmt = $pdo->prepare("INSERT INTO doctors (name, specialization) VALUES (?, ?)");
                $stmt->execute([$data['name'], $data['specialization']]);
                $id = $pdo->lastInsertId();
                sendJson(['status'=>'success', 'data'=>array_merge(['id'=>$id], $data)], 201);
            }
            elseif ($path === '/prescriptions') {
                $stmt = $pdo->prepare("INSERT INTO prescriptions (consultation_id, medicine_name, dosage) VALUES (?, ?, ?)");
                $stmt->execute([$data['consultation_id'], $data['medicine_name'], $data['dosage']]);
                $id = $pdo->lastInsertId();
                sendJson(['status'=>'success', 'data'=>array_merge(['id'=>$id], $data)], 201);
            }
            else {
                sendJson(['status'=>'error','message'=>'Endpoint not found'], 404);
            }
        } catch (PDOException $e) {
            sendJson(['status'=>'error','message'=>$e->getMessage()], 500);
        }
        break;

    default:
        sendJson(['status'=>'error','message'=>'Method not allowed'], 405);
}
