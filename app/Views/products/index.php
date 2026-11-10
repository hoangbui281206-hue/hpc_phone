<h1 class="fw-bold mb-4">Tất cả điện thoại</h1><div class="row g-4">
<?php foreach ($products as $product): ?><div class="col-6 col-md-4 col-lg-3"><div class="card product-card h-100">
<img src="<?= htmlspecialchars($product['image'] ?: 'https://placehold.co/600x450?text=Phone') ?>" class="card-img-top" alt="">
<div class="card-body d-flex flex-column"><h5><?= htmlspecialchars($product['name']) ?></h5><p class="price"><?= number_format((float)($product['sale_price'] ?: $product['price']), 0, ',', '.') ?> đ</p><a href="/products/<?= (int)$product['id'] ?>" class="btn btn-dark mt-auto">Chi tiết</a></div>
</div></div><?php endforeach; ?></div>
