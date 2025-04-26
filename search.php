<?php

include 'db.php';

$search = $_GET['search'] ?? '';

$sql = "SELECT r.id, r.name, r.phone_number, r.start_date, pm.method_name, r.note
        FROM room r
        JOIN payment_method pm ON r.payment_method_id = pm.id
        WHERE r.name LIKE :search 
        OR r.phone_number LIKE :search 
        OR CONCAT('PT-', r.id) LIKE :search";
$stmt = $pdo->prepare($sql);
$stmt->execute(['search' => "%$search%"]);
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

$methods = $pdo->query("SELECT * FROM payment_method")->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['rooms' => $rooms, 'methods' => $methods]);
?>
