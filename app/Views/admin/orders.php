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
        <h1>Quản lý đơn hàng</h1>

        <?php if (!empty($_SESSION['flash'])): ?>
            <div class="alert">
                <?= htmlspecialchars($_SESSION['flash']) ?>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <div class="admin-card">

            <div class="card-header">
                <h2>Danh sách đơn hàng</h2>
                <span>Tổng: <?= count($orders) ?> đơn hàng</span>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Số điện thoại</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Chi tiết</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (empty($orders)): ?>
                            <tr>
                                <td colspan="6" class="empty">
                                    Chưa có đơn hàng nào.
                                </td>
                            </tr>
                        <?php else: ?>

                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>
                                        #<?= (int) $order['id'] ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            $order['customer_name']
                                        ) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            $order['phone']
                                        ) ?>
                                    </td>

                                    <td class="price">
                                        <?= number_format(
                                            (float) $order['total'],
                                            0,
                                            ',',
                                            '.'
                                        ) ?> đ
                                    </td>

                                    <td>
                                        <form
                                            action="/admin/orders/status"
                                            method="POST"
                                            class="status-form"
                                        >
                                            <input
                                                type="hidden"
                                                name="id"
                                                value="<?= (int) $order['id'] ?>"
                                            >

                                            <select name="status">
                                                <option
                                                    value="pending"
                                                    <?= $order['status'] === 'pending'
                                                        ? 'selected'
                                                        : '' ?>
                                                >
                                                    Chờ xử lý
                                                </option>

                                                <option
                                                    value="confirmed"
                                                    <?= $order['status'] === 'confirmed'
                                                        ? 'selected'
                                                        : '' ?>
                                                >
                                                    Đã xác nhận
                                                </option>

                                                <option
                                                    value="shipping"
                                                    <?= $order['status'] === 'shipping'
                                                        ? 'selected'
                                                        : '' ?>
                                                >
                                                    Đang giao
                                                </option>

                                                <option
                                                    value="completed"
                                                    <?= $order['status'] === 'completed'
                                                        ? 'selected'
                                                        : '' ?>
                                                >
                                                    Hoàn thành
                                                </option>

                                                <option
                                                    value="cancelled"
                                                    <?= $order['status'] === 'cancelled'
                                                        ? 'selected'
                                                        : '' ?>
                                                >
                                                    Đã hủy
                                                </option>
                                            </select>

                                            <button
                                                type="submit"
                                                class="update-btn"
                                            >
                                                Cập nhật
                                            </button>
                                        </form>
                                    </td>

                                    <td>
                                        <a
                                            href="/admin/orders/detail?id=<?= (int) $order['id'] ?>"
                                            class="detail-btn"
                                        >
                                            Xem
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

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

.admin-content h1 {
    margin-top: 0;
}

.admin-card {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 20px;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.card-header h2 {
    margin: 0;
}

.card-header span {
    color: #666;
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
    padding: 12px 10px;
    border-bottom: 1px solid #ddd;
    text-align: left;
    vertical-align: middle;
}

th {
    background: #f5f5f5;
}

.empty {
    text-align: center;
    padding: 30px;
    color: #777;
}

.price {
    font-weight: bold;
    color: #d9534f;
}

.status-form {
    display: flex;
    gap: 6px;
}

.status-form select {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.update-btn,
.detail-btn {
    border: none;
    padding: 8px 12px;
    border-radius: 5px;
    cursor: pointer;
    text-decoration: none;
    font-size: 14px;
}

.update-btn {
    background: #ffc107;
    color: #212529;
}

.detail-btn {
    display: inline-block;
    background: #212529;
    color: white;
}

.alert {
    padding: 12px;
    margin-bottom: 20px;
    background: #d1e7dd;
    color: #0f5132;
    border-radius: 6px;
}

@media (max-width: 800px) {
    .admin-page {
        flex-direction: column;
    }

    .admin-sidebar {
        width: 100%;
    }

    .status-form {
        flex-direction: column;
    }
}
</style>