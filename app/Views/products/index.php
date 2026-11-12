<h1 class="fw-bold mb-4">Tất cả điện thoại</h1>

<!-- ==========================================
     TÌM KIẾM VÀ LỌC SẢN PHẨM
========================================== -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">

        <form method="get" action="/products" class="row g-3">

            <!-- Tìm kiếm theo tên -->
            <div class="col-md-4">
                <label class="form-label fw-bold">
                    🔍 Tìm kiếm sản phẩm
                </label>

                <input
                    type="text"
                    name="keyword"
                    class="form-control"
                    placeholder="Nhập tên điện thoại..."
                    value="<?= htmlspecialchars($keyword ?? '') ?>"
                >
            </div>

            <!-- Giá từ -->
            <div class="col-md-2">
                <label class="form-label fw-bold">
                    Giá từ
                </label>

                <input
                    type="number"
                    name="min_price"
                    class="form-control"
                    placeholder="0"
                    min="0"
                    value="<?= htmlspecialchars((string) ($minPrice ?? '')) ?>"
                >
            </div>

            <!-- Giá đến -->
            <div class="col-md-2">
                <label class="form-label fw-bold">
                    Giá đến
                </label>

                <input
                    type="number"
                    name="max_price"
                    class="form-control"
                    placeholder="Không giới hạn"
                    min="0"
                    value="<?= htmlspecialchars((string) ($maxPrice ?? '')) ?>"
                >
            </div>

            <!-- Lọc còn hàng -->
            <div class="col-md-2">
                <label class="form-label fw-bold">
                    Tồn kho
                </label>

                <select
                    name="stock"
                    class="form-select"
                >
                    <option value="">
                        Tất cả
                    </option>

                    <option
                        value="in_stock"
                        <?= ($stockFilter ?? '') === 'in_stock'
                            ? 'selected'
                            : '' ?>
                    >
                        Còn hàng
                    </option>

                    <option
                        value="out_of_stock"
                        <?= ($stockFilter ?? '') === 'out_of_stock'
                            ? 'selected'
                            : '' ?>
                    >
                        Hết hàng
                    </option>
                </select>
            </div>

            <!-- Nút lọc -->
            <div class="col-md-2 d-flex align-items-end">

                <button
                    type="submit"
                    class="btn btn-warning w-100 fw-bold"
                >
                    Lọc sản phẩm
                </button>

            </div>

        </form>

    </div>
</div>


<!-- ==========================================
     THÔNG BÁO SỐ LƯỢNG
========================================== -->
<div class="d-flex justify-content-between align-items-center mb-3">

    <p class="text-muted mb-0">
        Tìm thấy
        <strong><?= count($products) ?></strong>
        sản phẩm
    </p>

    <a
        href="/products"
        class="btn btn-outline-secondary btn-sm"
    >
        Xóa bộ lọc
    </a>

</div>


<!-- ==========================================
     DANH SÁCH SẢN PHẨM
========================================== -->
<div class="row g-4">

    <?php if (empty($products)): ?>

        <div class="col-12">

            <div class="alert alert-warning text-center py-4">

                <h5>Không tìm thấy sản phẩm phù hợp.</h5>

                <p class="mb-0">
                    Hãy thử tìm kiếm với từ khóa hoặc mức giá khác.
                </p>

            </div>

        </div>

    <?php else: ?>

        <?php foreach ($products as $product): ?>

            <?php
                $displayPrice = !empty($product['sale_price'])
                    && (float) $product['sale_price'] > 0
                    ? (float) $product['sale_price']
                    : (float) $product['price'];

                $isSale = !empty($product['sale_price'])
                    && (float) $product['sale_price'] > 0
                    && (float) $product['sale_price']
                        < (float) $product['price'];

                $stock = (int) $product['stock'];
            ?>

            <div class="col-6 col-md-4 col-lg-3">

                <div class="card product-card h-100 shadow-sm">

                    <!-- Ảnh sản phẩm -->
                    <div class="position-relative">

                        <img
                            src="<?= htmlspecialchars(
                                $product['image']
                                ?: 'https://placehold.co/600x450?text=Phone'
                            ) ?>"
                            class="card-img-top"
                            alt="<?= htmlspecialchars($product['name']) ?>"
                            style="
                                height: 220px;
                                object-fit: contain;
                                padding: 15px;
                            "
                        >

                        <?php if ($isSale): ?>

                            <span
                                class="badge bg-danger position-absolute top-0 end-0 m-2"
                            >
                                Giảm giá
                            </span>

                        <?php endif; ?>

                    </div>


                    <!-- Nội dung sản phẩm -->
                    <div class="card-body d-flex flex-column">

                        <h5
                            class="card-title fw-bold"
                            style="min-height: 48px;"
                        >
                            <?= htmlspecialchars($product['name']) ?>
                        </h5>


                        <!-- Giá sản phẩm -->
                        <div class="mb-2">

                            <?php if ($isSale): ?>

                                <div
                                    class="text-muted text-decoration-line-through small"
                                >
                                    <?= number_format(
                                        (float) $product['price'],
                                        0,
                                        ',',
                                        '.'
                                    ) ?> đ
                                </div>

                            <?php endif; ?>

                            <div class="price fw-bold text-danger fs-5">

                                <?= number_format(
                                    $displayPrice,
                                    0,
                                    ',',
                                    '.'
                                ) ?> đ

                            </div>

                        </div>


                        <!-- Kiểm tra tồn kho -->
                        <div class="mb-3">

                            <?php if ($stock > 0): ?>

                                <span class="badge bg-success">

                                    ✓ Còn hàng:
                                    <?= $stock ?>

                                    sản phẩm

                                </span>

                            <?php else: ?>

                                <span class="badge bg-secondary">

                                    Hết hàng

                                </span>

                            <?php endif; ?>

                        </div>


                        <!-- Nút chi tiết -->
                        <a
                            href="/products/<?= (int) $product['id'] ?>"
                            class="
                                btn
                                <?= $stock > 0
                                    ? 'btn-dark'
                                    : 'btn-secondary' ?>
                                mt-auto
                            "
                        >

                            <?= $stock > 0
                                ? 'Xem chi tiết'
                                : 'Xem sản phẩm' ?>

                        </a>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    <?php endif; ?>

</div>