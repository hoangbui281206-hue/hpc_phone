<!-- ==========================================
     BANNER CHẠY NEXO TECH
========================================== -->

<section class="mb-5">

    <div
        id="nexoBanner"
        class="carousel slide carousel-fade"
        data-bs-ride="carousel"
    >

        <!-- Dấu chấm chuyển banner -->
        <div class="carousel-indicators">

            <button
                type="button"
                data-bs-target="#nexoBanner"
                data-bs-slide-to="0"
                class="active"
                aria-current="true"
                aria-label="Banner 1"
            ></button>

            <button
                type="button"
                data-bs-target="#nexoBanner"
                data-bs-slide-to="1"
                aria-label="Banner 2"
            ></button>

        </div>

        <!-- Nội dung banner -->
        <div class="carousel-inner">

            <!-- Banner 1 -->
            <div
                class="carousel-item active"
                data-bs-interval="4000"
            >
                <img
                    src="/assets/images/nexo-banner.png"
                    class="d-block w-100 banner-image"
                    alt="NEXO TECH - Điện thoại chính hãng"
                >
            </div>

            <!-- Banner 2 -->
            <div
                class="carousel-item"
                data-bs-interval="4000"
            >
                <img
                    src="/assets/images/nexo-banner-2.png"
                    class="d-block w-100 banner-image"
                    alt="NEXO TECH - Ưu đãi điện thoại"
                >
            </div>

        </div>

        <!-- Nút chuyển sang trái -->
        <button
            class="carousel-control-prev"
            type="button"
            data-bs-target="#nexoBanner"
            data-bs-slide="prev"
        >
            <span
                class="carousel-control-prev-icon"
                aria-hidden="true"
            ></span>

            <span class="visually-hidden">
                Trước
            </span>
        </button>

        <!-- Nút chuyển sang phải -->
        <button
            class="carousel-control-next"
            type="button"
            data-bs-target="#nexoBanner"
            data-bs-slide="next"
        >
            <span
                class="carousel-control-next-icon"
                aria-hidden="true"
            ></span>

            <span class="visually-hidden">
                Tiếp theo
            </span>
        </button>

    </div>

</section>


<!-- ==========================================
     TIÊU ĐỀ SẢN PHẨM
========================================== -->

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="fw-bold mb-1">
            Sản phẩm nổi bật
        </h2>

        <p class="text-muted mb-0">
            Những mẫu điện thoại được nhiều khách hàng quan tâm
        </p>
    </div>

    <a
        href="/products"
        class="btn btn-outline-primary"
    >
        Xem tất cả →
    </a>

</div>


<!-- ==========================================
     DANH SÁCH SẢN PHẨM
========================================== -->

<div class="row g-4">

    <?php if (!empty($products)): ?>

        <?php foreach (array_slice($products, 0, 12) as $product): ?>

            <?php
                /*
                 * Lấy ảnh sản phẩm.
                 * Nếu database chưa có ảnh thì dùng ảnh mặc định.
                 */
                $image = !empty($product['image'])
                    ? $product['image']
                    : '/assets/images/products/default-phone.png';

                /*
                 * Lấy giá khuyến mãi nếu có.
                 */
                $price = !empty($product['sale_price'])
                    ? (float) $product['sale_price']
                    : (float) $product['price'];
            ?>

            <div class="col-6 col-md-4 col-lg-3">

                <div class="card product-card h-100">

                    <!-- Ảnh điện thoại -->
                    <a
                        href="/products/<?= (int) $product['id'] ?>"
                        class="product-image-link"
                    >

                        <img
                            src="<?= htmlspecialchars($image) ?>"
                            class="card-img-top product-image"
                            alt="<?= htmlspecialchars($product['name']) ?>"
                            onerror="this.src='/assets/images/products/default-phone.png'"
                        >

                    </a>


                    <!-- Thông tin sản phẩm -->
                    <div class="card-body d-flex flex-column">

                        <h5 class="product-name">

                            <?= htmlspecialchars(
                                $product['name']
                            ) ?>

                        </h5>


                        <!-- Giá -->
                        <p class="price mb-3">

                            <?= number_format(
                                $price,
                                0,
                                ',',
                                '.'
                            ) ?> đ

                        </p>


                        <!-- Nút xem chi tiết -->
                        <a
                            class="btn btn-outline-dark mt-auto w-100"
                            href="/products/<?= (int) $product['id'] ?>"
                        >
                            Xem chi tiết
                        </a>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    <?php else: ?>

        <div class="col-12">

            <div class="alert alert-info text-center">

                Hiện chưa có sản phẩm nào.

                <a href="/admin/products">
                    Thêm sản phẩm
                </a>

            </div>

        </div>

    <?php endif; ?>

</div>


<!-- ==========================================
     DỊCH VỤ NEXO TECH
========================================== -->

<section class="mt-5 pt-3">

    <div class="row g-4">

        <div class="col-md-4">

            <div class="feature-box">

                <div class="feature-icon">
                    🚚
                </div>

                <div>
                    <h5>Giao hàng nhanh</h5>

                    <p>
                        Hỗ trợ giao hàng nhanh chóng và tiện lợi.
                    </p>
                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="feature-box">

                <div class="feature-icon">
                    🛡️
                </div>

                <div>
                    <h5>Sản phẩm chính hãng</h5>

                    <p>
                        Cam kết sản phẩm chất lượng và rõ nguồn gốc.
                    </p>
                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="feature-box">

                <div class="feature-icon">
                    💬
                </div>

                <div>
                    <h5>Hỗ trợ khách hàng</h5>

                    <p>
                        Luôn sẵn sàng hỗ trợ bạn khi cần.
                    </p>
                </div>

            </div>

        </div>

    </div>

</section>