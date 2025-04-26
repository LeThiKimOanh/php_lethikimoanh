<?php
include 'db.php';

if (isset($_POST['delete_ids'])) {
    $deleteIds = $_POST['delete_ids'];

   
    $placeholders = implode(',', array_fill(0, count($deleteIds), '?'));

    $sql = "DELETE FROM room WHERE id IN ($placeholders)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($deleteIds);

    header("Location: index.php");
    exit;
} else {
    
    header("Location: index.php");
    exit;
}
?>
