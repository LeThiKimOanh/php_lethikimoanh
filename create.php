<?php
include 'db.php';

$name = $_POST['name'] ?? '';
$phone = $_POST['phone_number'] ?? '';
$start = $_POST['start_date'] ?? '';
$method = $_POST['payment_method_id'] ?? '';
$note = $_POST['note'] ?? '';


if (!$name || !$phone || !$start || !$method) {
    echo "Vui lòng điền đầy đủ thông tin.";
    exit();
}

if (!preg_match("/^[\p{L}\s]{5,50}$/u", $name)) {
    echo "Tên người thuê phải dài từ 5 đến 50 ký tự và không chứa ký tự số hoặc đặc biệt.";
    exit();
}


if (!preg_match("/^[0-9]{10}$/", $phone)) {
    echo "Số điện thoại phải là 10 chữ số.";
    exit();
}

$currentDate = new DateTime();
$startDateForDatabase = date('d-m-Y', strtotime($start)); 

if ($startDateForDatabase < $currentDate->format('d-m-Y')) {
    echo "Ngày bắt đầu không được là ngày trong quá khứ.";
    exit();
}
$validMethods = ['1', '2', '3']; 
if (!in_array($method, $validMethods)) {
    echo "Hình thức thanh toán không hợp lệ.";
    exit();
}

try {
  
    $sql = "INSERT INTO room (name, phone_number, start_date, payment_method_id, note)
            VALUES (:name, :phone, :start, :method, :note)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'name' => $name,
        'phone' => $phone,
        'start' => $startDateForDatabase,
        'method' => $method,
        'note' => $note
    ]);
    echo "success";
} catch (Exception $e) {
    echo "Lỗi: " . $e->getMessage();
}
?>
