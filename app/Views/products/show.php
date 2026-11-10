<div class="row g-5"><div class="col-md-6"><img class="img-fluid rounded-4" src="<?= htmlspecialchars($product['image'] ?: 'https://placehold.co/700x600?text=Phone') ?>" alt=""></div>
<div class="col-md-6"><span class="badge text-bg-success">Chính hãng</span><h1 class="fw-bold mt-3"><?= htmlspecialchars($product['name']) ?></h1>
<p class="price fs-3"><?= number_format((float)($product['sale_price'] ?: $product['price']), 0, ',', '.') ?> đ</p>
<p><?= nl2br(htmlspecialchars($product['description'] ?? 'Đang cập nhật mô tả sản phẩm.')) ?></p><p>Còn lại: <?= (int)$product['stock'] ?> sản phẩm</p>
<form method="post" action="/cart/add"><input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>"><button class="btn btn-warning btn-lg" <?= (int)$product['stock'] < 1 ? 'disabled' : '' ?>>Thêm vào giỏ hàng</button></form></div></div>
