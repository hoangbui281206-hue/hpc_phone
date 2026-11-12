<div class="admin-dashboard">

    <!-- SIDEBAR -->
    <aside class="admin-sidebar">

        <h2>⚙️ ADMIN</h2>

        <a href="/admin" class="active">
            📊 Tổng quan
        </a>

        <a href="/admin/products">
            📱 Quản lý sản phẩm
        </a>

        <a href="/admin/orders">
            📦 Quản lý đơn hàng
        </a>

        <a href="/admin/users">
            👥 Quản lý người dùng
        </a>

        <hr>

        <a href="/">
            🏠 Về trang chủ
        </a>

    </aside>


    <!-- NỘI DUNG -->
    <main class="admin-content">

        <div class="dashboard-header">
            <div>
                <h1>Dashboard quản trị</h1>

                <p class="welcome">
                    Chào mừng bạn đến với trang quản trị NEXO TECH.
                </p>
            </div>

            <a href="/admin/orders" class="header-button">
                📦 Xem đơn hàng
            </a>
        </div>


        <!-- THÔNG BÁO -->
        <?php if (!empty($_SESSION['flash'])): ?>

            <div class="alert-message">
                <?= htmlspecialchars($_SESSION['flash']) ?>
            </div>

            <?php unset($_SESSION['flash']); ?>

        <?php endif; ?>


        <!-- THỐNG KÊ -->
        <div class="stat-grid">

            <!-- SẢN PHẨM -->
            <div class="stat-card">

                <div class="stat-icon product-icon">
                    📱
                </div>

                <div>
                    <p>Tổng sản phẩm</p>

                    <h2>
                        <?= (int) $totalProducts ?>
                    </h2>
                </div>

            </div>


            <!-- ĐƠN HÀNG -->
            <div class="stat-card">

                <div class="stat-icon order-icon">
                    📦
                </div>

                <div>
                    <p>Tổng đơn hàng</p>

                    <h2>
                        <?= (int) $totalOrders ?>
                    </h2>
                </div>

            </div>


            <!-- KHÁCH HÀNG -->
            <div class="stat-card">

                <div class="stat-icon user-icon">
                    👥
                </div>

                <div>
                    <p>Khách hàng</p>

                    <h2>
                        <?= (int) $totalUsers ?>
                    </h2>
                </div>

            </div>


            <!-- DOANH THU -->
            <div class="stat-card revenue-card">

                <div class="stat-icon revenue-icon">
                    💰
                </div>

                <div>
                    <p>Tổng doanh thu</p>

                    <h2>
                        <?= number_format(
                            (float) $totalRevenue,
                            0,
                            ',',
                            '.'
                        ) ?> đ
                    </h2>

                    <small>
                        Đơn hàng đã hoàn thành
                    </small>
                </div>

            </div>

        </div>


        <!-- ĐƠN HÀNG MỚI NHẤT -->
        <section class="dashboard-section">

            <div class="section-header">

                <div>
                    <h2>Đơn hàng mới nhất</h2>

                    <p>
                        Theo dõi các đơn hàng mới được tạo.
                    </p>
                </div>

                <a href="/admin/orders" class="btn-dashboard">
                    Xem tất cả →
                </a>

            </div>


            <?php if (empty($latestOrders)): ?>

                <div class="empty-orders">

                    <div class="empty-icon">
                        📦
                    </div>

                    <h3>Chưa có đơn hàng nào</h3>

                    <p>
                        Các đơn hàng mới sẽ xuất hiện tại đây.
                    </p>

                </div>

            <?php else: ?>

                <div class="table-wrapper">

                    <table class="dashboard-table">

                        <thead>

                            <tr>
                                <th>Mã đơn</th>
                                <th>Khách hàng</th>
                                <th>Số tiền</th>
                                <th>Trạng thái</th>
                                <th>Chi tiết</th>
                            </tr>

                        </thead>

                        <tbody>

                            <?php foreach ($latestOrders as $order): ?>

                                <?php

                                $statusText = [
                                    'pending' => 'Chờ xác nhận',
                                    'confirmed' => 'Đã xác nhận',
                                    'shipping' => 'Đang giao hàng',
                                    'completed' => 'Hoàn thành',
                                    'cancelled' => 'Đã hủy'
                                ];

                                $status = $order['status'] ?? 'pending';

                                ?>

                                <tr>

                                    <td class="order-id">
                                        #<?= (int) $order['id'] ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            $order['customer_name']
                                            ?? 'Không xác định'
                                        ) ?>
                                    </td>

                                    <td class="order-total">

                                        <?= number_format(
                                            (float) $order['total'],
                                            0,
                                            ',',
                                            '.'
                                        ) ?> đ

                                    </td>

                                    <td>

                                        <span
                                            class="status status-<?= htmlspecialchars($status) ?>"
                                        >
                                            <?= htmlspecialchars(
                                                $statusText[$status]
                                                ?? $status
                                            ) ?>
                                        </span>

                                    </td>

                                    <td>

                                        <a
                                            href="/admin/orders/detail?id=<?= (int) $order['id'] ?>"
                                            class="detail-link"
                                        >
                                            Xem
                                        </a>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php endif; ?>

        </section>

    </main>

</div>


<style>

/* ==========================================
   LAYOUT ADMIN
========================================== */

.admin-dashboard {
    display: flex;
    min-height: 650px;
    margin: 20px 0;
    gap: 25px;
}


/* ==========================================
   SIDEBAR
========================================== */

.admin-sidebar {
    width: 240px;
    background: #212529;
    border-radius: 12px;
    padding: 22px 15px;
    box-sizing: border-box;
    flex-shrink: 0;
}

.admin-sidebar h2 {
    color: #ffffff;
    margin: 0 0 25px;
    text-align: center;
    font-size: 22px;
}

.admin-sidebar a {
    display: block;
    color: #dddddd;
    text-decoration: none;
    padding: 13px 15px;
    margin-bottom: 7px;
    border-radius: 7px;
    transition: 0.2s;
}

.admin-sidebar a:hover,
.admin-sidebar a.active {
    background: #ffc107;
    color: #212529;
    font-weight: 600;
}

.admin-sidebar hr {
    border: none;
    border-top: 1px solid #555;
    margin: 20px 0;
}


/* ==========================================
   NỘI DUNG
========================================== */

.admin-content {
    flex: 1;
    min-width: 0;
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
}

.admin-content h1 {
    margin: 0 0 8px;
    font-size: 28px;
}

.welcome {
    margin: 0;
    color: #6c757d;
}

.header-button,
.btn-dashboard {
    background: #ffc107;
    color: #212529;
    text-decoration: none;
    padding: 10px 16px;
    border-radius: 7px;
    font-weight: 600;
    white-space: nowrap;
}

.header-button:hover,
.btn-dashboard:hover {
    background: #e0a800;
    color: #212529;
}


/* ==========================================
   THÔNG BÁO
========================================== */

.alert-message {
    margin-top: 20px;
    padding: 13px 16px;
    background: #d1e7dd;
    border: 1px solid #badbcc;
    border-radius: 8px;
    color: #0f5132;
}


/* ==========================================
   THẺ THỐNG KÊ
========================================== */

.stat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
    margin: 25px 0;
}

.stat-card {
    background: #ffffff;
    border: 1px solid #e5e5e5;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.stat-icon {
    font-size: 32px;
    width: 55px;
    height: 55px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    border-radius: 12px;
}

.stat-card p {
    margin: 0 0 6px;
    color: #6c757d;
    font-size: 14px;
}

.stat-card h2 {
    margin: 0;
    font-size: 22px;
    color: #212529;
}

.stat-card small {
    display: block;
    margin-top: 5px;
    color: #999;
    font-size: 11px;
}

.revenue-card {
    border-color: #ffe69c;
}


/* ==========================================
   ĐƠN HÀNG MỚI
========================================== */

.dashboard-section {
    background: #ffffff;
    border: 1px solid #e5e5e5;
    border-radius: 12px;
    padding: 22px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
}

.section-header h2 {
    margin: 0;
    font-size: 20px;
}

.section-header p {
    margin: 5px 0 0;
    color: #6c757d;
    font-size: 14px;
}

.table-wrapper {
    overflow-x: auto;
}

.dashboard-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.dashboard-table th,
.dashboard-table td {
    padding: 14px 12px;
    border-bottom: 1px solid #eeeeee;
    text-align: left;
}

.dashboard-table th {
    background: #f8f9fa;
    color: #555;
    font-size: 14px;
}

.dashboard-table tbody tr:hover {
    background: #fafafa;
}

.order-id {
    font-weight: 700;
}

.order-total {
    font-weight: 600;
}


/* ==========================================
   TRẠNG THÁI ĐƠN HÀNG
========================================== */

.status {
    display: inline-block;
    padding: 6px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-confirmed {
    background: #cfe2ff;
    color: #084298;
}

.status-shipping {
    background: #cff4fc;
    color: #055160;
}

.status-completed {
    background: #d1e7dd;
    color: #0f5132;
}

.status-cancelled {
    background: #f8d7da;
    color: #842029;
}

.detail-link {
    text-decoration: none;
    color: #0d6efd;
    font-weight: 600;
}


/* ==========================================
   TRẠNG THÁI RỖNG
========================================== */

.empty-orders {
    text-align: center;
    padding: 45px 20px;
    color: #777;
}

.empty-icon {
    font-size: 45px;
}

.empty-orders h3 {
    margin: 10px 0;
    color: #333;
}


/* ==========================================
   RESPONSIVE
========================================== */

@media (max-width: 1100px) {

    .stat-grid {
        grid-template-columns: repeat(2, 1fr);
    }

}

@media (max-width: 800px) {

    .admin-dashboard {
        flex-direction: column;
    }

    .admin-sidebar {
        width: 100%;
    }

}

@media (max-width: 600px) {

    .dashboard-header,
    .section-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .stat-grid {
        grid-template-columns: 1fr;
    }

}

</style>