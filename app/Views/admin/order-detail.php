<div class="admin-page">

    <!-- Sidebar -->
    <div class="admin-sidebar">
        <h2>⚙️ ADMIN</h2>

        <a href="/admin">📊 Tổng quan</a>
        <a href="/admin/products">📱 Quản lý sản phẩm</a>
        <a href="/admin/orders" class="active">📦 Quản lý đơn hàng</a>
        <a href="/admin/users">👥 Quản lý người dùng</a>

        <hr>

        <a href="/">🏠 Về trang chủ</a>
    </div>

    <!-- Nội dung -->
    <div class="admin-content">

        <div class="page-header">
            <div>
                <h1>Chi tiết đơn hàng #<?= (int) $order['id'] ?></h1>
                <p>Thông tin đầy đủ của đơn hàng.</p>
            </div>

            <a href="/admin/orders" class="back-btn">
                ← Quay lại
            </a>
        </div>

        <!-- Thông tin khách hàng -->
        <div class="info-grid">

            <div class="admin-card">
                <h2>👤 Thông tin khách hàng</h2>

                <div class="info-row">
                    <strong>Họ tên:</strong>
                    <span>
                        <?= htmlspecialchars($order['customer_name']) ?>
                    </span>
                </div>

                <div class="info-row">
                    <strong>Số điện thoại:</strong>
                    <span>
                        <?= htmlspecialchars($order['phone']) ?>
                    </span>
                </div>

                <?php if (!empty($order['email'])): ?>
                    <div class="info-row">
                        <strong>Email:</strong>
                        <span>
                            <?= htmlspecialchars($order['email']) ?>
                        </span>
                    </div>
                <?php endif; ?>

                <div class="info-row">
                    <strong>Địa chỉ:</strong>
                    <span>
                        <?= htmlspecialchars($order['address']) ?>
                    </span>
                </div>
            </div>

            <!-- Thông tin đơn hàng -->
            <div class="admin-card">
                <h2>📦 Thông tin đơn hàng</h2>

                <div class="info-row">
                    <strong>Mã đơn:</strong>
                    <span>#<?= (int) $order['id'] ?></span>
                </div>

                <div class="info-row">
                    <strong>Trạng thái:</strong>
                    <span class="status">
                        <?php
                        $statuses = [
                            'pending' => 'Chờ xử lý',
                            'confirmed' => 'Đã xác nhận',
                            'shipping' => 'Đang giao',
                            'completed' => 'Hoàn thành',
                            'cancelled' => 'Đã hủy'
                        ];

                        echo htmlspecialchars(
                            $statuses[$order['status']]
                            ?? $order['status']
                        );
                        ?>
                    </span>
                </div>

                <div class="info-row">
                    <strong>Tổng tiền:</strong>
                    <span class="total">
                        <?= number_format(
                            (float) $order['total'],
                            0,
                            ',',
                            '.'
                        ) ?> đ
                    </span>
                </div>

                <?php if (!empty($order['created_at'])): ?>
                    <div class="info-row">
                        <strong>Ngày đặt:</strong>
                        <span>
                            <?= htmlspecialchars($order['created_at']) ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="admin-card products-card">
            <h2>🛒 Sản phẩm đã đặt</h2>

            <?php if (empty($items)): ?>
                <p class="empty">
                    Không tìm thấy sản phẩm trong đơn hàng này.
                </p>
            <?php else: ?>

                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>ID sản phẩm</th>
                                <th>Tên sản phẩm</th>
                                <th>Đơn giá</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td>
                                        #<?= (int) $item['product_id'] ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            $item['product_name']
                                        ) ?>
                                    </td>

                                    <td>
                                        <?= number_format(
                                            (float) $item['price'],
                                            0,
                                            ',',
                                            '.'
                                        ) ?> đ
                                    </td>

                                    <td>
                                        <?= (int) $item['quantity'] ?>
                                    </td>

                                    <td class="item-total">
                                        <?= number_format(
                                            (float) $item['price']
                                            * (int) $item['quantity'],
                                            0,
                                            ',',
                                            '.'
                                        ) ?> đ
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>

                        <tfoot>
                            <tr>
                                <td colspan="4" class="grand-label">
                                    Tổng thanh toán
                                </td>

                                <td class="grand-total">
                                    <?= number_format(
                                        (float) $order['total'],
                                        0,
                                        ',',
                                        '.'
                                    ) ?> đ
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            <?php endif; ?>
        </div>

    </div>
</div>

<style>
.admin-page {
    display: flex;
    min-height: 650px;
    margin: 20px 0;
    gap: 25px;
}

.admin-sidebar {
    width: 230px;
    flex-shrink: 0;
    background: #212529;
    border-radius: 10px;
    padding: 20px 15px;
    box-sizing: border-box;
}

.admin-sidebar h2 {
    color: #fff;
    margin: 0 0 25px;
    text-align: center;
}

.admin-sidebar a {
    display: block;
    color: #ddd;
    text-decoration: none;
    padding: 13px 15px;
    margin-bottom: 7px;
    border-radius: 6px;
}

.admin-sidebar a:hover,
.admin-sidebar a.active {
    background: #ffc107;
    color: #212529;
}

.admin-sidebar hr {
    border: none;
    border-top: 1px solid #555;
    margin: 20px 0;
}

.admin-content {
    flex: 1;
    min-width: 0;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.page-header h1 {
    margin: 0 0 8px;
}

.page-header p {
    margin: 0;
    color: #777;
}

.back-btn {
    background: #212529;
    color: #fff;
    padding: 10px 16px;
    border-radius: 6px;
    text-decoration: none;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 20px;
}

.admin-card {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 20px;
}

.admin-card h2 {
    margin-top: 0;
    font-size: 19px;
}

.info-row {
    display: flex;
    gap: 10px;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.info-row strong {
    min-width: 120px;
}

.status {
    background: #fff3cd;
    padding: 4px 10px;
    border-radius: 20px;
}

.total,
.item-total,
.grand-total {
    font-weight: bold;
}

.products-card {
    margin-top: 20px;
}

.table-wrapper {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background: #f5f5f5;
}

tfoot {
    background: #f8f9fa;
}

.grand-label {
    text-align: right;
    font-weight: bold;
}

.grand-total {
    font-size: 17px;
}

.empty {
    color: #777;
    text-align: center;
    padding: 25px;
}

@media (max-width: 800px) {
    .admin-page {
        flex-direction: column;
    }

    .admin-sidebar {
        width: 100%;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>