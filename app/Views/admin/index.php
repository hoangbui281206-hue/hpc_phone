<div class="admin-dashboard">
    <div class="admin-sidebar">
        <h2>⚙️ ADMIN</h2>

        <a href="/admin" class="active">📊 Tổng quan</a>
        <a href="/admin/products">📱 Quản lý sản phẩm</a>
        <a href="/admin/orders">📦 Quản lý đơn hàng</a>
        <a href="/admin/users">👥 Quản lý người dùng</a>

        <hr>

        <a href="/">🏠 Về trang chủ</a>
    </div>

    <div class="admin-content">
        <h1>Dashboard quản trị</h1>
        <p class="welcome">
            Chào mừng bạn đến với trang quản trị NEXO TECH.
        </p>

        <?php if (!empty($_SESSION['flash'])): ?>
            <div class="alert">
                <?= htmlspecialchars($_SESSION['flash']) ?>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon">📱</div>
                <div>
                    <p>Tổng sản phẩm</p>
                    <h2><?= (int) $totalProducts ?></h2>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">📦</div>
                <div>
                    <p>Tổng đơn hàng</p>
                    <h2><?= (int) $totalOrders ?></h2>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div>
                    <p>Khách hàng</p>
                    <h2><?= (int) $totalUsers ?></h2>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">💰</div>
                <div>
                    <p>Doanh thu</p>
                    <h2><?= number_format((float) $totalRevenue, 0, ',', '.') ?> đ</h2>
                </div>
            </div>
        </div>

        <div class="dashboard-section">
            <div class="section-header">
                <h2>Đơn hàng mới nhất</h2>
                <a href="/admin/orders" class="btn">Xem tất cả</a>
            </div>

            <?php if (empty($latestOrders)): ?>
                <p>Chưa có đơn hàng nào.</p>
            <?php else: ?>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Mã đơn</th>
                                <th>Khách hàng</th>
                                <th>Số tiền</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($latestOrders as $order): ?>
                                <tr>
                                    <td>#<?= (int) $order['id'] ?></td>
                                    <td>
                                        <?= htmlspecialchars(
                                            $order['customer_name']
                                        ) ?>
                                    </td>
                                    <td>
                                        <?= number_format(
                                            (float) $order['total'],
                                            0,
                                            ',',
                                            '.'
                                        ) ?> đ
                                    </td>
                                    <td>
                                        <?= htmlspecialchars(
                                            $order['status']
                                        ) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.admin-dashboard {
    display: flex;
    min-height: 650px;
    margin: 20px 0;
    gap: 25px;
}

.admin-sidebar {
    width: 230px;
    background: #212529;
    border-radius: 10px;
    padding: 20px 15px;
    box-sizing: border-box;
}

.admin-sidebar h2 {
    color: white;
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
}

.admin-content h1 {
    margin-top: 0;
}

.welcome {
    color: #666;
}

.stat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
    margin: 25px 0;
}

.stat-card {
    background: white;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.stat-icon {
    font-size: 32px;
}

.stat-card p {
    margin: 0 0 5px;
    color: #777;
}

.stat-card h2 {
    margin: 0;
}

.dashboard-section {
    background: white;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 20px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.btn {
    background: #ffc107;
    color: #212529;
    text-decoration: none;
    padding: 10px 15px;
    border-radius: 6px;
}

.table-wrapper {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

th,
td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
    text-align: left;
}

th {
    background: #f5f5f5;
}

.alert {
    padding: 12px;
    background: #d1e7dd;
    border-radius: 6px;
    color: #0f5132;
}

@media (max-width: 900px) {
    .stat-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 650px) {
    .admin-dashboard {
        flex-direction: column;
    }

    .admin-sidebar {
        width: 100%;
    }

    .stat-grid {
        grid-template-columns: 1fr;
    }
}
</style>