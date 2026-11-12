<?php
$price = (float) (
    !empty($product['sale_price'])
        ? $product['sale_price']
        : $product['price']
);

$stock = (int) ($product['stock'] ?? 0);
?>

<div class="row g-4">

    <!-- Ảnh sản phẩm -->
    <div class="col-md-6">
        <div class="card">
            <img
                src="<?= htmlspecialchars(
                    $product['image']
                    ?: 'https://placehold.co/600x450?text=Phone'
                ) ?>"
                class="img-fluid p-4"
                alt="<?= htmlspecialchars($product['name']) ?>"
                style="
                    width: 100%;
                    height: 450px;
                    object-fit: contain;
                "
            >
        </div>
    </div>

    <!-- Thông tin sản phẩm -->
    <div class="col-md-6">

        <h1 class="fw-bold mb-3">
            <?= htmlspecialchars($product['name']) ?>
        </h1>

        <?php if (!empty($product['description'])): ?>
            <p class="text-muted">
                <?= nl2br(
                    htmlspecialchars($product['description'])
                ) ?>
            </p>
        <?php endif; ?>

        <?php if (
            !empty($product['sale_price'])
            && (float) $product['sale_price']
                < (float) $product['price']
        ): ?>

            <p class="text-decoration-line-through text-muted mb-1">
                <?= number_format(
                    (float) $product['price'],
                    0,
                    ',',
                    '.'
                ) ?> đ
            </p>

        <?php endif; ?>

        <h2 class="text-danger fw-bold mb-3">
            <?= number_format(
                $price,
                0,
                ',',
                '.'
            ) ?> đ
        </h2>

        <!-- Trạng thái tồn kho -->
        <?php if ($stock > 0): ?>

            <div class="alert alert-success">
                ✓ Còn hàng: <?= $stock ?> sản phẩm
            </div>

            <!-- FORM THÊM VÀO GIỎ -->
            <form
                method="post"
                action="/cart/add"
                class="mt-3"
            >

                <input
                    type="hidden"
                    name="product_id"
                    value="<?= (int) $product['id'] ?>"
                >

                <div class="mb-3">
                    <label class="form-label">
                        Số lượng
                    </label>

                    <input
                        type="number"
                        name="quantity"
                        value="1"
                        min="1"
                        max="<?= $stock ?>"
                        class="form-control"
                        style="max-width: 150px"
                    >
                </div>

                <button
                    type="submit"
                    class="btn btn-warning btn-lg"
                >
                    🛒 Thêm vào giỏ hàng
                </button>

            </form>

        <?php else: ?>

            <div class="alert alert-danger">
                Sản phẩm hiện đang hết hàng.
            </div>

            <button
                class="btn btn-secondary btn-lg"
                disabled
            >
                Hết hàng
            </button>

        <?php endif; ?>

        <a
            href="/products"
            class="btn btn-outline-dark mt-3"
        >
            ← Quay lại danh sách
        </a>

    </div>

</div>