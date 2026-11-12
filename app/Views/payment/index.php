<div class="row justify-content-center">
    <div class="col-md-6">

        <div class="card shadow border-0">
            <div class="card-body p-4 text-center">

                <h2 class="fw-bold mb-2">
                    Thanh toán MoMo
                </h2>

                <p class="text-muted">
                    Quét mã QR bằng ứng dụng MoMo để thanh toán
                </p>

                <div class="alert alert-warning text-start">
                    <strong>Mã đơn hàng:</strong>
                    #<?= (int) $order['id'] ?><br>

                    <strong>Số tiền:</strong>
                    <?= number_format(
                        (float) $order['total'],
                        0,
                        ',',
                        '.'
                    ) ?> đ
                </div>

                <!-- QR DEMO -->
                <div class="border rounded p-3 bg-white mb-3">
                    <img
                        src="https://api.qrserver.com/v1/create-qr-code/?size=260x260&data=NEXO-MOMO-ORDER-<?= (int) $order['id'] ?>"
                        alt="Mã QR thanh toán MoMo"
                        class="img-fluid"
                        style="max-width: 260px;"
                    >
                </div>

                <div class="mb-4">
                    <span class="badge text-bg-warning p-2">
                        ⏳ Đang chờ thanh toán
                    </span>
                </div>

                <form method="post" action="/payment/confirm">

                    <input
                        type="hidden"
                        name="order_id"
                        value="<?= (int) $order['id'] ?>"
                    >

                    <button
                        type="submit"
                        class="btn btn-danger btn-lg w-100"
                    >
                        Tôi đã thanh toán
                    </button>

                </form>

                <a
                    href="/cart"
                    class="btn btn-outline-secondary w-100 mt-2"
                >
                    Quay lại giỏ hàng
                </a>

            </div>
        </div>

    </div>
</div>