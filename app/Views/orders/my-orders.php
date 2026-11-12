<h1 class="fw-bold mb-4">
    Đơn hàng của tôi
</h1>

<?php if (empty($orders)): ?>

    <div class="alert alert-info">
        Bạn chưa có đơn hàng nào.
    </div>

    <a href="/products" class="btn btn-dark">
        Mua sắm ngay
    </a>

<?php else: ?>

    <div class="card shadow-sm border-0">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-dark">
                        <tr>
                            <th>Mã đơn</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Thanh toán</th>
                            <th>Trạng thái</th>
                            <th class="text-center">
                                Chi tiết
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($orders as $order): ?>

                            <tr>

                                <td>
                                    <strong>
                                        #<?= (int) $order['id'] ?>
                                    </strong>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $order['created_at']
                                    ) ?>
                                </td>

                                <td class="fw-bold text-danger">
                                    <?= number_format(
                                        (float) $order['total'],
                                        0,
                                        ',',
                                        '.'
                                    ) ?> đ
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $order['payment_method']
                                    ) ?>
                                </td>

                                <td>

                                    <?php
                                    $status = $order['status'] ?? 'pending';

                                    $statusText = [
                                        'pending' => 'Chờ xác nhận',
                                        'confirmed' => 'Đã xác nhận',
                                        'shipping' => 'Đang giao hàng',
                                        'completed' => 'Hoàn thành',
                                        'cancelled' => 'Đã hủy'
                                    ];

                                    $statusClass = [
                                        'pending' => 'warning',
                                        'confirmed' => 'info',
                                        'shipping' => 'primary',
                                        'completed' => 'success',
                                        'cancelled' => 'danger'
                                    ];
                                    ?>

                                    <span
                                        class="badge bg-<?= $statusClass[$status] ?? 'secondary' ?>"
                                    >
                                        <?= $statusText[$status] ?? $status ?>
                                    </span>

                                </td>

                                <td class="text-center">

                                    <a
                                        href="/my-orders/detail?id=<?= (int) $order['id'] ?>"
                                        class="btn btn-sm btn-outline-dark"
                                    >
                                        Xem đơn
                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

<?php endif; ?>