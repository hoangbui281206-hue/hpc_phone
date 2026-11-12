<?php
declare(strict_types=1);
?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h1 class="fw-bold mb-1">
            Chi tiết đơn hàng #<?= (int) $order['id'] ?>
        </h1>

        <p class="text-muted mb-0">
            Thông tin chi tiết đơn hàng của bạn
        </p>
    </div>

    <a
        href="/my-orders"
        class="btn btn-outline-secondary"
    >
        ← Quay lại đơn hàng
    </a>

</div>


<!-- THÔNG TIN ĐƠN HÀNG -->
<div class="card shadow-sm border-0 mb-4">

    <div class="card-body">

        <h5 class="fw-bold mb-3">
            Thông tin nhận hàng
        </h5>

        <div class="row">

            <div class="col-md-6 mb-3">

                <strong>Người nhận:</strong>

                <p class="mb-0">
                    <?= htmlspecialchars(
                        $order['customer_name'] ?? ''
                    ) ?>
                </p>

            </div>

            <div class="col-md-6 mb-3">

                <strong>Số điện thoại:</strong>

                <p class="mb-0">
                    <?= htmlspecialchars(
                        $order['phone'] ?? ''
                    ) ?>
                </p>

            </div>

            <div class="col-12 mb-3">

                <strong>Địa chỉ nhận hàng:</strong>

                <p class="mb-0">
                    <?= htmlspecialchars(
                        $order['address'] ?? ''
                    ) ?>
                </p>

            </div>

            <div class="col-md-6">

                <strong>Phương thức thanh toán:</strong>

                <p class="mb-0">

                    <?php if (
                        ($order['payment_method'] ?? 'cod') === 'online'
                    ): ?>

                        Thanh toán online

                    <?php else: ?>

                        Thanh toán khi nhận hàng

                    <?php endif; ?>

                </p>

            </div>

            <div class="col-md-6">

                <strong>Trạng thái thanh toán:</strong>

                <p class="mb-0">

                    <?php if (
                        ($order['payment_status'] ?? 'unpaid') === 'paid'
                    ): ?>

                        <span class="badge bg-success">
                            Đã thanh toán
                        </span>

                    <?php else: ?>

                        <span class="badge bg-warning text-dark">
                            Chưa thanh toán
                        </span>

                    <?php endif; ?>

                </p>

            </div>

        </div>

    </div>

</div>


<!-- SẢN PHẨM ĐÃ ĐẶT -->
<div class="card shadow-sm border-0">

    <div class="card-header bg-white">

        <h5 class="fw-bold mb-0">
            Sản phẩm đã đặt
        </h5>

    </div>

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>
                        <th>Sản phẩm</th>
                        <th class="text-center">Đơn giá</th>
                        <th class="text-center">Số lượng</th>
                        <th class="text-end">Thành tiền</th>
                    </tr>

                </thead>

                <tbody>

                    <?php if (!empty($items)): ?>

                        <?php foreach ($items as $item): ?>

                            <tr>

                                <td>

                                    <strong>
                                        <?= htmlspecialchars(
                                            $item['product_name'] ?? ''
                                        ) ?>
                                    </strong>

                                </td>

                                <td class="text-center">

                                    <?= number_format(
                                        (float) ($item['price'] ?? 0),
                                        0,
                                        ',',
                                        '.'
                                    ) ?> đ

                                </td>

                                <td class="text-center">

                                    <?= (int) (
                                        $item['quantity'] ?? 0
                                    ) ?>

                                </td>

                                <td class="text-end fw-bold">

                                    <?= number_format(
                                        (float) (
                                            ($item['price'] ?? 0)
                                            *
                                            ($item['quantity'] ?? 0)
                                        ),
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
                                colspan="4"
                                class="text-center py-4 text-muted"
                            >
                                Không tìm thấy sản phẩm trong đơn hàng.
                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

                <tfoot class="table-light">

                    <tr>

                        <td
                            colspan="3"
                            class="text-end fw-bold"
                        >
                            Tổng tiền:
                        </td>

                        <td class="text-end">

                            <strong class="text-danger fs-5">

                                <?= number_format(
                                    (float) ($order['total'] ?? 0),
                                    0,
                                    ',',
                                    '.'
                                ) ?> đ

                            </strong>

                        </td>

                    </tr>

                </tfoot>

            </table>

        </div>

    </div>

</div>


<!-- TRẠNG THÁI ĐƠN HÀNG -->
<div class="card shadow-sm border-0 mt-4">

    <div class="card-body">

        <h5 class="fw-bold mb-3">
            Trạng thái đơn hàng
        </h5>

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
            'pending' => 'bg-warning text-dark',
            'confirmed' => 'bg-info text-dark',
            'shipping' => 'bg-primary',
            'completed' => 'bg-success',
            'cancelled' => 'bg-danger'
        ];
        ?>

        <span
            class="badge <?= $statusClass[$status] ?? 'bg-secondary' ?> fs-6 p-2"
        >
            <?= htmlspecialchars(
                $statusText[$status] ?? $status
            ) ?>
        </span>

    </div>

</div>