<div class="admin-dashboard">

    <aside class="admin-sidebar">

        <h2>⚙️ ADMIN</h2>

        <a href="/admin">
            📊 Tổng quan
        </a>

        <a href="/admin/products">
            📱 Quản lý sản phẩm
        </a>

        <a href="/admin/orders">
            📦 Quản lý đơn hàng
        </a>

        <a href="/admin/revenue" class="active">
            💰 Doanh thu
        </a>

        <a href="/admin/users">
            👥 Quản lý người dùng
        </a>

        <hr>

        <a href="/">
            🏠 Về trang chủ
        </a>

    </aside>


    <main class="admin-content">

        <h1>💰 Quản lý doanh thu</h1>

        <p class="welcome">
            Thống kê doanh thu từ các đơn hàng đã hoàn thành.
        </p>


        <!-- THỐNG KÊ DOANH THU -->
        <div class="revenue-grid">

            <div class="revenue-card">

                <span class="revenue-icon">
                    📅
                </span>

                <div>
                    <p>Doanh thu hôm nay</p>

                    <h2>
                        <?= number_format(
                            (float) $revenueToday,
                            0,
                            ',',
                            '.'
                        ) ?> đ
                    </h2>
                </div>

            </div>


            <div class="revenue-card">

                <span class="revenue-icon">
                    📆
                </span>

                <div>
                    <p>Doanh thu tháng này</p>

                    <h2>
                        <?= number_format(
                            (float) $revenueThisMonth,
                            0,
                            ',',
                            '.'
                        ) ?> đ
                    </h2>
                </div>

            </div>


            <div class="revenue-card">

                <span class="revenue-icon">
                    💰
                </span>

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
                </div>

            </div>


            <div class="revenue-card">

                <span class="revenue-icon">
                    📦
                </span>

                <div>
                    <p>Đơn hoàn thành</p>

                    <h2>
                        <?= (int) $completedOrders ?>
                    </h2>
                </div>

            </div>

        </div>


        <!-- DANH SÁCH ĐƠN HÀNG -->
        <div class="revenue-table-card">

            <div class="section-header">

                <div>

                    <h2>
                        Đơn hàng đã hoàn thành
                    </h2>

                    <p>
                        Danh sách các đơn hàng được tính vào doanh thu.
                    </p>

                </div>

            </div>


            <?php if (empty($orders)): ?>

                <div class="empty-data">

                    <div>📭</div>

                    <h3>
                        Chưa có doanh thu
                    </h3>

                    <p>
                        Chưa có đơn hàng nào hoàn thành.
                    </p>

                </div>

            <?php else: ?>

                <div class="table-wrapper">

                    <table class="revenue-table">

                        <thead>

                            <tr>
                                <th>Mã đơn</th>
                                <th>Khách hàng</th>
                                <th>Số điện thoại</th>
                                <th>Thanh toán</th>
                                <th>Doanh thu</th>
                            </tr>

                        </thead>

                        <tbody>

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

                                    <td>

                                        <?php if (
                                            ($order['payment_method'] ?? '')
                                            === 'online'
                                        ): ?>

                                            <span class="payment-online">
                                                Online
                                            </span>

                                        <?php else: ?>

                                            <span class="payment-cod">
                                                COD
                                            </span>

                                        <?php endif; ?>

                                    </td>

                                    <td class="money">

                                        <?= number_format(
                                            (float) $order['total'],
                                            0,
                                            ',',
                                            '.'
                                        ) ?> đ

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php endif; ?>

        </div>

    </main>

</div>


<style>

.admin-dashboard {
    display: flex;
    gap: 25px;
    margin: 20px 0;
    min-height: 650px;
}

.admin-sidebar {
    width: 230px;
    flex-shrink: 0;
    padding: 20px 15px;
    background: #212529;
    border-radius: 10px;
}

.admin-sidebar h2 {
    color: #fff;
    text-align: center;
    margin-bottom: 25px;
}

.admin-sidebar a {
    display: block;
    padding: 13px 15px;
    margin-bottom: 7px;
    color: #ddd;
    text-decoration: none;
    border-radius: 6px;
}

.admin-sidebar a:hover,
.admin-sidebar a.active {
    background: #ffc107;
    color: #212529;
}

.admin-sidebar hr {
    border-color: #555;
    margin: 20px 0;
}

.admin-content {
    flex: 1;
    min-width: 0;
}

.welcome {
    color: #777;
}

.revenue-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
    margin: 25px 0;
}

.revenue-card {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
}

.revenue-icon {
    font-size: 32px;
}

.revenue-card p {
    margin: 0 0 6px;
    color: #777;
}

.revenue-card h2 {
    margin: 0;
    font-size: 20px;
}

.revenue-table-card {
    padding: 20px;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
}

.section-header h2 {
    margin: 0;
}

.section-header p {
    color: #777;
}

.table-wrapper {
    overflow-x: auto;
}

.revenue-table {
    width: 100%;
    margin-top: 20px;
    border-collapse: collapse;
}

.revenue-table th,
.revenue-table td {
    padding: 13px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.revenue-table th {
    background: #f5f5f5;
}

.money {
    font-weight: bold;
}

.payment-online,
.payment-cod {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 12px;
}

.payment-online {
    background: #d1e7dd;
    color: #0f5132;
}

.payment-cod {
    background: #fff3cd;
    color: #856404;
}

.empty-data {
    padding: 50px 20px;
    text-align: center;
    color: #777;
}

.empty-data div {
    font-size: 45px;
}

@media (max-width: 1000px) {

    .revenue-grid {
        grid-template-columns: repeat(2, 1fr);
    }

}

@media (max-width: 750px) {

    .admin-dashboard {
        flex-direction: column;
    }

    .admin-sidebar {
        width: 100%;
    }

}

@media (max-width: 500px) {

    .revenue-grid {
        grid-template-columns: 1fr;
    }

}

</style>