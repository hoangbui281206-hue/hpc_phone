<?php
declare(strict_types=1);
?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h1 class="fw-bold mb-1">
            💰 Quản lý doanh thu
        </h1>

        <p class="text-muted mb-0">
            Thống kê doanh thu từ các đơn hàng đã hoàn thành
        </p>
    </div>

    <a
        href="/admin"
        class="btn btn-outline-secondary"
    >
        ← Dashboard
    </a>

</div>


<div class="row g-4 mb-4">

    <div class="col-md-4">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="text-muted mb-2">
                    Tổng doanh thu
                </div>

                <h3 class="fw-bold text-success mb-0">

                    <?= number_format(
                        (float) $totalRevenue,
                        0,
                        ',',
                        '.'
                    ) ?> đ

                </h3>

            </div>

        </div>

    </div>


    <div class="col-md-4">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="text-muted mb-2">
                    Doanh thu hôm nay
                </div>

                <h3 class="fw-bold text-primary mb-0">

                    <?= number_format(
                        (float) $todayRevenue,
                        0,
                        ',',
                        '.'
                    ) ?> đ

                </h3>

            </div>

        </div>

    </div>


    <div class="col-md-4">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="text-muted mb-2">
                    Đơn hoàn thành
                </div>

                <h3 class="fw-bold mb-0">

                    <?= (int) $completedOrders ?>

                    <small class="fs-6 text-muted">
                        đơn
                    </small>

                </h3>

            </div>

        </div>

    </div>

</div>


<!-- DOANH THU THEO THÁNG -->
<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white py-3">

        <h5 class="fw-bold mb-0">
            📊 Doanh thu theo tháng
        </h5>

    </div>

    <div class="table-responsive">

        <table class="table table-hover align-middle mb-0">

            <thead class="table-light">

                <tr>
                    <th>Thời gian</th>
                    <th>Số đơn hoàn thành</th>
                    <th class="text-end">
                        Doanh thu
                    </th>
                </tr>

            </thead>

            <tbody>

                <?php if (!empty($monthlyRevenue)): ?>

                    <?php foreach ($monthlyRevenue as $item): ?>

                        <tr>

                            <td>

                                Tháng
                                <?= (int) $item['month'] ?>
                                /
                                <?= (int) $item['year'] ?>

                            </td>

                            <td>

                                <?= (int) $item['total_orders'] ?>

                                đơn

                            </td>

                            <td class="text-end fw-bold text-success">

                                <?= number_format(
                                    (float) $item['revenue'],
                                    0,
                                    ',',
                                    '.'
                                ) ?> đ

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>

                        <td
                            colspan="3"
                            class="text-center text-muted py-4"
                        >
                            Chưa có đơn hàng hoàn thành.
                        </td>

                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>


<!-- DANH SÁCH ĐƠN HÀNG -->
<div class="card border-0 shadow-sm">

    <div class="card-header bg-white py-3">

        <h5 class="fw-bold mb-0">
            📋 Đơn hàng tạo doanh thu
        </h5>

    </div>

    <div class="table-responsive">

        <table class="table table-hover align-middle mb-0">

            <thead class="table-light">

                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Số điện thoại</th>
                    <th>Thanh toán</th>
                    <th class="text-end">
                        Tổng tiền
                    </th>
                </tr>

            </thead>

            <tbody>

                <?php if (!empty($orders)): ?>

                    <?php foreach ($orders as $order): ?>

                        <tr>

                            <td>

                                <strong>
                                    #<?= (int) $order['id'] ?>
                                </strong>

                            </td>

                            <td>

                                <?= htmlspecialchars(
                                    $order['customer_name'] ?? ''
                                ) ?>

                            </td>

                            <td>

                                <?= htmlspecialchars(
                                    $order['phone'] ?? ''
                                ) ?>

                            </td>

                            <td>

                                <?php if (
                                    ($order['payment_status'] ?? '')
                                    === 'paid'
                                ): ?>

                                    <span class="badge bg-success">
                                        Đã thanh toán
                                    </span>

                                <?php else: ?>

                                    <span
                                        class="badge bg-warning text-dark"
                                    >
                                        Thanh toán khi nhận hàng
                                    </span>

                                <?php endif; ?>

                            </td>

                            <td class="text-end fw-bold">

                                <?= number_format(
                                    (float) $order['total'],
                                    0,
                                    ',',
                                    '.'
                                ) ?> đ

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>

                        <td
                            colspan="5"
                            class="text-center text-muted py-4"
                        >
                            Chưa có dữ liệu doanh thu.
                        </td>

                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>