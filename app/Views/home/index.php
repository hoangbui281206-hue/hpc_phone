<!-- BANNER CHẠY NEXO TECH -->
<section class="mb-5">

    <div
        id="nexoBanner"
        class="carousel slide carousel-fade"
        data-bs-ride="carousel"
    >

        <!-- Dấu chấm chuyển ảnh -->
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

        <!-- Hai ảnh banner -->
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
                    alt="NEXO TECH - Điện thoại Samsung"
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
            <span class="carousel-control-prev-icon"></span>
            <span class="visually-hidden">Trước</span>
        </button>

        <!-- Nút chuyển sang phải -->
        <button
            class="carousel-control-next"
            type="button"
            data-bs-target="#nexoBanner"
            data-bs-slide="next"
        >
            <span class="carousel-control-next-icon"></span>
            <span class="visually-hidden">Tiếp theo</span>
        </button>

    </div>

</section>
<div class="d-flex justify-content-between align-items-center mb-3"><h2 class="fw-bold">Sản phẩm nổi bật</h2><a href="/products">Xem tất cả →</a></div>
<div class="row g-4">
<?php foreach (array_slice($products, 0, 8) as $product): ?>
<div class="col-6 col-md-4 col-lg-3"><div class="card product-card h-100">
<img src="<?= htmlspecialchars($product['image'] ?: 'https://placehold.co/600x450?text=Phone') ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
<div class="card-body d-flex flex-column"><h5><?= htmlspecialchars($product['name']) ?></h5>
<p class="price"><?= number_format((float)($product['sale_price'] ?: $product['price']), 0, ',', '.') ?> đ</p>
<a class="btn btn-outline-dark mt-auto" href="/products/<?= (int)$product['id'] ?>">Xem chi tiết</a></div></div></div>
<?php endforeach; ?>
</div>
