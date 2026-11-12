<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold mb-0">
            Sửa sản phẩm
        </h1>

        <a
            href="/admin/products"
            class="btn btn-outline-secondary"
        >
            ← Quay lại
        </a>
    </div>

    <div class="card shadow-sm border-0">

        <div class="card-body p-4">

            <form
                method="post"
                action="/admin/products/update"
                class="row g-3"
            >

                <!-- ID sản phẩm -->
                <input
                    type="hidden"
                    name="id"
                    value="<?= (int) $product['id'] ?>"
                >

                <!-- Tên sản phẩm -->
                <div class="col-md-6">

                    <label class="form-label fw-bold">
                        Tên điện thoại
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        name="name"
                        value="<?= htmlspecialchars($product['name']) ?>"
                        required
                    >

                </div>

                <!-- Giá gốc -->
                <div class="col-md-3">

                    <label class="form-label fw-bold">
                        Giá gốc
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        name="price"
                        value="<?= number_format((float) $product['price'], 0, '', '') ?>"
                        inputmode="numeric"
                        required
                    >

                </div>

                <!-- Giá khuyến mãi -->
                <div class="col-md-3">

                    <label class="form-label fw-bold">
                        Giá khuyến mãi
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        name="sale_price"
                        value="<?= !empty($product['sale_price']) ? number_format((float) $product['sale_price'], 0, '', '') : '' ?>"
                        placeholder="Để trống nếu không giảm giá"
                        inputmode="numeric"
                    >

                </div>

                <!-- Tồn kho -->
                <div class="col-md-4">

                    <label class="form-label fw-bold">
                        Số lượng tồn kho
                    </label>

                    <input
                        type="number"
                        class="form-control"
                        name="stock"
                        min="0"
                        value="<?= (int) $product['stock'] ?>"
                        required
                    >

                </div>

                <!-- Trạng thái -->
                <div class="col-md-4">

                    <label class="form-label fw-bold">
                        Trạng thái
                    </label>

                    <select
                        class="form-select"
                        name="status"
                    >
                        <option
                            value="1"
                            <?= (int) $product['status'] === 1 ? 'selected' : '' ?>
                        >
                            Đang bán
                        </option>

                        <option
                            value="0"
                            <?= (int) $product['status'] === 0 ? 'selected' : '' ?>
                        >
                            Tạm ẩn
                        </option>
                    </select>

                </div>

                <!-- Đường dẫn ảnh -->
                <div class="col-md-4">

                    <label class="form-label fw-bold">
                        Đường dẫn ảnh
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        name="image"
                        value="<?= htmlspecialchars($product['image'] ?? '') ?>"
                        placeholder="/assets/images/products/images.jpeg"
                    >

                    <small class="text-muted">
                        Ví dụ: /assets/images/products/images.jpeg
                    </small>

                </div>

                <!-- Xem trước ảnh -->
                <?php if (!empty($product['image'])): ?>

                    <div class="col-12">

                        <label class="form-label fw-bold">
                            Ảnh hiện tại
                        </label>

                        <div>
                            <img
                                src="<?= htmlspecialchars($product['image']) ?>"
                                alt="<?= htmlspecialchars($product['name']) ?>"
                                style="
                                    width: 180px;
                                    height: 180px;
                                    object-fit: contain;
                                    border: 1px solid #ddd;
                                    border-radius: 10px;
                                    padding: 10px;
                                    background: #fff;
                                "
                            >
                        </div>

                    </div>

                <?php endif; ?>

                <!-- MÔ TẢ SẢN PHẨM -->
                <div class="col-12">

                    <label class="form-label fw-bold">
                        Mô tả sản phẩm
                    </label>

                    <textarea
                        class="form-control"
                        name="description"
                        rows="6"
                        placeholder="Nhập mô tả chi tiết về điện thoại..."
                    ><?= htmlspecialchars($product['description'] ?? '') ?></textarea>

                    <small class="text-muted">
                        Bạn có thể nhập thông tin về màn hình, camera,
                        dung lượng, pin và các đặc điểm của điện thoại.
                    </small>

                </div>

                <!-- Nút lưu -->
                <div class="col-12 mt-3">

                    <button
                        type="submit"
                        class="btn btn-warning px-4"
                    >
                        💾 Lưu thay đổi
                    </button>

                    <a
                        href="/admin/products"
                        class="btn btn-outline-secondary"
                    >
                        Hủy
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>