<h1 class="fw-bold mb-4">Giỏ hàng</h1>

<?php if (empty($cart)): ?>

    <div class="alert alert-secondary">
        Giỏ hàng đang trống.
        <a href="/products">Mua sắm ngay</a>
    </div>

<?php else: ?>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <?php $total = 0; ?>

                <?php foreach ($cart as $id => $item): ?>

                    <?php
                    $price = (float) ($item['price'] ?? 0);
                    $quantity = (int) ($item['quantity'] ?? 1);
                    $subtotal = $price * $quantity;
                    $total += $subtotal;
                    ?>

                    <tr>
                        <td>
                            <?= htmlspecialchars(
                                (string) ($item['name'] ?? 'Sản phẩm')
                            ) ?>
                        </td>

                        <td>
                            <?= number_format(
                                $price,
                                0,
                                ',',
                                '.'
                            ) ?> đ
                        </td>

                        <td>
                            <form
                                class="d-flex gap-2"
                                method="post"
                                action="/cart/update"
                            >
                                <input
                                    type="hidden"
                                    name="product_id"
                                    value="<?= (int) $id ?>"
                                >

                                <input
                                    class="form-control"
                                    style="width: 85px"
                                    type="number"
                                    min="1"
                                    max="20"
                                    name="quantity"
                                    value="<?= $quantity ?>"
                                >

                                <button
                                    type="submit"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    Cập nhật
                                </button>
                            </form>
                        </td>

                        <td>
                            <?= number_format(
                                $subtotal,
                                0,
                                ',',
                                '.'
                            ) ?> đ
                        </td>

                        <td>
                            <form
                                method="post"
                                action="/cart/remove"
                            >
                                <input
                                    type="hidden"
                                    name="product_id"
                                    value="<?= (int) $id ?>"
                                >

                                <button
                                    type="submit"
                                    class="btn btn-sm btn-outline-danger"
                                >
                                    Xóa
                                </button>
                            </form>
                        </td>
                    </tr>

                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="row justify-content-end">
        <div class="col-md-5">

            <div class="card card-body">
                <h4 class="mb-3">
                    Tổng cộng:
                    <span class="price">
                        <?= number_format(
                            $total,
                            0,
                            ',',
                            '.'
                        ) ?> đ
                    </span>
                </h4>

                <?php if (empty($_SESSION['user'])): ?>

                    <a
                        href="/login"
                        class="btn btn-dark w-100"
                    >
                        Đăng nhập để đặt hàng
                    </a>

                <?php else: ?>

                    <form
                        method="post"
                        action="/checkout"
                        class="mt-3"
                    >
                        <h5 class="mb-3">
                            Thông tin nhận hàng
                        </h5>

                        <input
                            class="form-control mb-2"
                            type="text"
                            name="customer_name"
                            placeholder="Họ tên người nhận"
                            value="<?= htmlspecialchars(
                                (string) (
                                    $_SESSION['user']['name'] ?? ''
                                )
                            ) ?>"
                            required
                        >

                        <input
                            class="form-control mb-2"
                            type="tel"
                            name="phone"
                            placeholder="Số điện thoại"
                            required
                        >

                        <textarea
                            class="form-control mb-3"
                            name="address"
                            placeholder="Địa chỉ nhận hàng"
                            rows="3"
                            required
                        ></textarea>

                        <h6 class="fw-bold">
                            Phương thức thanh toán
                        </h6>

                        <div class="form-check mb-2">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="payment_method"
                                id="paymentCod"
                                value="cod"
                                checked
                            >

                            <label
                                class="form-check-label"
                                for="paymentCod"
                            >
                                💵 Thanh toán khi nhận hàng (COD)
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="payment_method"
                                id="paymentOnline"
                                value="online"
                            >

                            <label
                                class="form-check-label"
                                for="paymentOnline"
                            >
                                📱 Thanh toán online
                            </label>
                        </div>

                        <button
                            type="submit"
                            class="btn btn-warning w-100"
                        >
                            Xác nhận đặt hàng
                        </button>
                    </form>

                <?php endif; ?>

            </div>
        </div>
    </div>

<?php endif; ?>