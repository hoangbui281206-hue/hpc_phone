<h1 class="fw-bold mb-4">Giỏ hàng</h1>

<?php if (!$cart): ?>

    <div class="alert alert-secondary">
        Giỏ hàng đang trống.
        <a href="/products">Mua sắm ngay</a>
    </div>

<?php else: ?>

    <!-- DANH SÁCH SẢN PHẨM -->
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <?php $total = 0; ?>

                <?php foreach ($cart as $id => $item): ?>

                    <?php
                    $total += $item['price'] * $item['quantity'];
                    ?>

                    <tr>
                        <td>
                            <?= htmlspecialchars($item['name']) ?>
                        </td>

                        <td>
                            <?= number_format($item['price'], 0, ',', '.') ?> đ
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
                                    value="<?= (int)$id ?>"
                                >

                                <input
                                    class="form-control"
                                    style="width: 85px"
                                    type="number"
                                    min="1"
                                    max="20"
                                    name="quantity"
                                    value="<?= (int)$item['quantity'] ?>"
                                >

                                <button class="btn btn-sm btn-outline-primary">
                                    Cập nhật
                                </button>
                            </form>
                        </td>

                        <td>
                            <form method="post" action="/cart/remove">

                                <input
                                    type="hidden"
                                    name="product_id"
                                    value="<?= (int)$id ?>"
                                >

                                <button class="btn btn-sm btn-outline-danger">
                                    Xóa
                                </button>

                            </form>
                        </td>
                    </tr>

                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- TỔNG TIỀN VÀ THANH TOÁN -->
    <div class="row justify-content-end">

        <div class="col-md-5">

            <h4 class="mb-3">
                Tổng cộng:
                <span class="price">
                    <?= number_format($total, 0, ',', '.') ?> đ
                </span>
            </h4>

            <?php if (empty($_SESSION['user'])): ?>

                <a href="/login" class="btn btn-dark w-100">
                    Đăng nhập để đặt hàng
                </a>

            <?php else: ?>

                <!-- FORM ĐẶT HÀNG -->
                <form
                    method="post"
                    action="/checkout"
                    class="card card-body mt-3"
                >

                    <h5 class="mb-3">Thông tin nhận hàng</h5>

                    <input
                        class="form-control mb-2"
                        name="customer_name"
                        placeholder="Họ tên người nhận"
                        required
                    >

                    <input
                        class="form-control mb-2"
                        name="phone"
                        placeholder="Số điện thoại"
                        required
                    >

                    <textarea
                        class="form-control mb-3"
                        name="address"
                        placeholder="Địa chỉ nhận hàng"
                        required
                    ></textarea>


                    <!-- PHƯƠNG THỨC THANH TOÁN -->
                    <h6 class="fw-bold mt-2">
                        Phương thức thanh toán
                    </h6>

                    <!-- COD -->
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


                    <!-- THANH TOÁN ONLINE -->
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

<?php endif; ?>