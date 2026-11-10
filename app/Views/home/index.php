<section class="hero rounded-4 p-5 mb-5 text-white">
 <span class="badge text-bg-warning mb-3">HPC PHONE STORE</span><h1 class="display-5 fw-bold">Công nghệ trong tầm tay</h1>
 <p class="lead">Khám phá điện thoại chính hãng, ưu đãi hấp dẫn và dịch vụ tận tâm.</p>
 <a href="/products" class="btn btn-warning btn-lg">Khám phá ngay</a>
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
