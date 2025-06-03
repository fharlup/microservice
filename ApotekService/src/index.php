<?php
header('Content-Type: application/json');

$host = getenv('DB_HOST') ?: 'db_apotek';
$db   = getenv('DB_NAME') ?: 'apotek_db';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: 'a';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

function sendJson($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data);
    exit();
}

// Helper untuk GET data dari tabel
function fetchAllFromTable(PDO $pdo, string $table) {
    $stmt = $pdo->query("SELECT * FROM `$table`");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

switch ($method) {
    case 'GET':
        if ($path === '/obat') {
            try {
                $data = fetchAllFromTable($pdo, 'obat');
                sendJson(['status'=>'success','data'=>$data]);
            } catch (PDOException $e) {
                sendJson(['status'=>'error','message'=>$e->getMessage()], 500);
            }
        } 
        elseif ($path === '/suppliers') {
            try {
                $data = fetchAllFromTable($pdo, 'suppliers');
                sendJson(['status'=>'success','data'=>$data]);
            } catch (PDOException $e) {
                sendJson(['status'=>'error','message'=>$e->getMessage()], 500);
            }
        } 
        elseif ($path === '/orders') {
            try {
                $data = fetchAllFromTable($pdo, 'orders');
                sendJson(['status'=>'success','data'=>$data]);
            } catch (PDOException $e) {
                sendJson(['status'=>'error','message'=>$e->getMessage()], 500);
            }
        } 
        elseif ($path === '/purchase-history') {
            try {
                $data = fetchAllFromTable($pdo, 'purchase_history');
                sendJson(['status'=>'success','data'=>$data]);
            } catch (PDOException $e) {
                sendJson(['status'=>'error','message'=>$e->getMessage()], 500);
            }
        }
        elseif ($path === '/patients-from-hospital') {
            // Ambil data pasien dari HospitalService
            $patientsData = @file_get_contents('http://hospital/patients');
            if ($patientsData === false) {
                sendJson(['status'=>'error', 'message'=>'Failed to fetch patients from hospital service'], 500);
            }
            $json = json_decode($patientsData, true);
            sendJson($json ?: ['status'=>'error', 'message'=>'Invalid response from hospital service'], 500);
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

        try {
            if ($path === '/obat') {
                $stmt = $pdo->prepare("INSERT INTO obat (name, stock, price) VALUES (?, ?, ?)");
                $stmt->execute([$data['name'], $data['stock'], $data['price']]);
                $id = $pdo->lastInsertId();
                sendJson(['status'=>'success', 'data'=>array_merge(['id'=>$id], $data)],201);
            }
            elseif ($path === '/suppliers') {
                $stmt = $pdo->prepare("INSERT INTO suppliers (name, contact) VALUES (?, ?)");
                $stmt->execute([$data['name'], $data['contact']]);
                $id = $pdo->lastInsertId();
                sendJson(['status'=>'success', 'data'=>array_merge(['id'=>$id], $data)],201);
            }
            elseif ($path === '/orders') {
                $stmt = $pdo->prepare("INSERT INTO orders (obat_id, quantity, order_date) VALUES (?, ?, ?)");
                $stmt->execute([$data['obat_id'], $data['quantity'], $data['order_date']]);
                $id = $pdo->lastInsertId();
                sendJson(['status'=>'success', 'data'=>array_merge(['id'=>$id], $data)],201);
            }
            elseif ($path === '/purchase-history') {
                $stmt = $pdo->prepare("INSERT INTO purchase_history (patient_id, medicine_name, quantity, purchase_date) VALUES (?, ?, ?, ?)");
                $stmt->execute([$data['patient_id'], $data['medicine_name'], $data['quantity'], $data['purchase_date']]);
                $id = $pdo->lastInsertId();
                sendJson(['status'=>'success', 'data'=>array_merge(['id'=>$id], $data)],201);
            }
            else {
                sendJson(['status'=>'error','message'=>'Endpoint not found'],404);
            }
        } catch (PDOException $e) {
            sendJson(['status'=>'error','message'=>$e->getMessage()], 500);
        }
        break;

    default:
        sendJson(['status'=>'error','message'=>'Method not allowed'],405);
}
