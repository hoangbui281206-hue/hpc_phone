<h1 class="fw-bold mb-4">Giỏ hàng</h1>
<?php if (!$cart): ?><div class="alert alert-secondary">Giỏ hàng đang trống. <a href="/products">Mua sắm ngay</a></div><?php else: ?>
<div class="table-responsive"><table class="table align-middle"><thead><tr><th>Sản phẩm</th><th>Giá</th><th>Số lượng</th><th></th></tr></thead><tbody>
<?php $total = 0; foreach ($cart as $id => $item): $total += $item['price'] * $item['quantity']; ?>
<tr><td><?= htmlspecialchars($item['name']) ?></td><td><?= number_format($item['price'],0,',','.') ?> đ</td><td><form class="d-flex gap-2" method="post" action="/cart/update"><input type="hidden" name="product_id" value="<?= (int)$id ?>"><input class="form-control" style="width:85px" type="number" min="1" max="20" name="quantity" value="<?= (int)$item['quantity'] ?>"><button class="btn btn-sm btn-outline-primary">Cập nhật</button></form></td><td><form method="post" action="/cart/remove"><input type="hidden" name="product_id" value="<?= (int)$id ?>"><button class="btn btn-sm btn-outline-danger">Xóa</button></form></td></tr>
<?php endforeach; ?></tbody></table></div>
<div class="row justify-content-end"><div class="col-md-5"><h4>Tổng cộng: <span class="price"><?= number_format($total,0,',','.') ?> đ</span></h4>
<?php if (empty($_SESSION['user'])): ?><a href="/login" class="btn btn-dark w-100">Đăng nhập để đặt hàng</a><?php else: ?>
<form method="post" action="/checkout" class="card card-body mt-3"><h5>Thông tin nhận hàng</h5><input class="form-control mb-2" name="customer_name" placeholder="Họ tên người nhận" required><input class="form-control mb-2" name="phone" placeholder="Số điện thoại" required><textarea class="form-control mb-2" name="address" placeholder="Địa chỉ nhận hàng" required></textarea><button class="btn btn-warning">Đặt hàng (COD)</button></form><?php endif; ?></div></div>
<?php endif; ?>
