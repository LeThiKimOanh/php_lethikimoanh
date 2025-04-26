<?php
include 'db.php';

$search = $_GET['search'] ?? '';

$sql = "SELECT r.id, r.name, r.phone_number, r.start_date, pm.method_name, r.note
        FROM room r
        JOIN payment_method pm ON r.payment_method_id = pm.id
        WHERE r.name LIKE :search OR r.phone_number LIKE :search OR r.id LIKE :search";
$stmt = $pdo->prepare($sql);
$stmt->execute(['search' => "%$search%"]);
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

$methods = $pdo->query("SELECT * FROM payment_method")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách phòng trọ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Danh sách phòng trọ</h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createModal">Tạo mới</button>
    </div>

  <!-- Modal Tạo mới -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="createForm" class="modal-content p-3">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">🌟 Thêm mới phòng trọ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">👤 Tên người thuê</label>
                        <input type="text" class="form-control" name="name" placeholder="Nhập họ tên" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">📞 Số điện thoại</label>
                        <input type="tel" class="form-control" name="phone_number" placeholder="Ví dụ: 098xxxxxxx" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">📅 Ngày bắt đầu thuê</label>
                        <input type="date" class="form-control" name="start_date" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">💳 Hình thức thanh toán</label>
                        <select name="payment_method_id" class="form-select" required>
                            <option value="" disabled selected>-- Chọn hình thức --</option>
                            <?php foreach ($methods as $method): ?>
                                <option value="<?= $method['id'] ?>"><?= htmlspecialchars($method['method_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">📝 Ghi chú</label>
                        <textarea class="form-control" name="note" rows="3" placeholder="Thêm ghi chú (nếu có)..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer mt-3">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" class="btn btn-primary">✅ Tạo mới</button>
            </div>
        </form>
    </div>
</div>

    <!-- Form tìm kiếm -->
    <form method="GET" class="row g-3 mb-3">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Tìm theo tên, số điện thoại, mã phòng..." value="<?= htmlspecialchars($search) ?>">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
        </div>
    </form>

    <!-- Danh sách phòng -->
<form method="POST" action="delete.php" id="deleteForm" onsubmit="return confirmDelete();">
    <div id="roomList">
        <?php if (count($rooms) > 0): ?>
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Chọn</th>
                        <th>Mã phòng</th>
                        <th>Tên người thuê</th>
                        <th>Số điện thoại</th>
                        <th>Ngày bắt đầu thuê</th>
                        <th>Hình thức thanh toán</th>
                        <th>Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rooms as $room): 
                        
                        $startDate = new DateTime($room['start_date']);
                        $formattedStartDate = $startDate->format('d-m-Y');
                    ?>
                        <tr>
                            <td><input type="checkbox" name="delete_ids[]" value="<?= $room['id'] ?>" class="delete-checkbox"></td>
                            <td>PT-<?= htmlspecialchars($room['id']) ?></td>
                            <td><?= htmlspecialchars($room['name']) ?></td>
                            <td><?= htmlspecialchars($room['phone_number']) ?></td>
                            <td><?= $formattedStartDate ?></td> 
                            <td><?= htmlspecialchars($room['method_name']) ?></td>
                            <td><?= htmlspecialchars($room['note']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-warning" role="alert">
                Không có phòng trọ nào trong danh sách.
            </div>
        <?php endif; ?>
    </div>

        <div class="text-end">
            <button type="submit" class="btn btn-danger">Xóa các mục đã chọn</button>
        </div>
    </form>
</div>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    
    $('#createForm').submit(function (e) {
        e.preventDefault();
        const formData = $(this).serialize();

        $.post('create.php', formData, function (response) {
            if (response === 'success') {
                
                const modal = bootstrap.Modal.getInstance(document.getElementById('createModal'));
                modal.hide();
                location.reload();
            } else {
                alert(response);
            }
        });
    });

  
    function confirmDelete() {
        var selectedRooms = [];
        $("input[name='delete_ids[]']:checked").each(function() {
            var roomId = $(this).val();
            selectedRooms.push("PT-" + roomId);
        });

        if (selectedRooms.length > 0) {
            var message = "Bạn có muốn xóa thông tin thuê trọ " + selectedRooms.join(", ") + " hay không?";
            return confirm(message);
        } else {
            alert("Vui lòng chọn ít nhất một phòng để xóa.");
            return false;
        }
    }
</script>

</body>
</html>
