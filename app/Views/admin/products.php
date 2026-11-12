<div class="container py-4">

    <!-- Tiêu đề -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="fw-bold mb-1">
                Quản lý sản phẩm
            </h1>

            <p class="text-muted mb-0">
                Thêm, sửa và quản lý điện thoại trên NEXO TECH
            </p>
        </div>

    </div>


    <!-- THÔNG BÁO -->
    <?php if (!empty($_SESSION['flash'])): ?>

        <div class="alert alert-info alert-dismissible fade show">

            <?= htmlspecialchars($_SESSION['flash']) ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

        <?php unset($_SESSION['flash']); ?>

    <?php endif; ?>


    <!-- ==========================================
         FORM THÊM SẢN PHẨM
    ========================================== -->

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body p-4">

            <h5 class="fw-bold mb-3">
                Thêm sản phẩm mới
            </h5>

            <form
                method="post"
                action="/admin/products/create"
                class="row g-3"
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
                        placeholder="Ví dụ: iPhone 16 Pro Max"
                        required
                    >

                </div>


                <!-- Giá -->
                <div class="col-md-3">

                    <label class="form-label fw-bold">
                        Giá gốc
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        name="price"
                        placeholder="Ví dụ: 18990000"
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
                        placeholder="Không bắt buộc"
                        inputmode="numeric"
                    >

                </div>


                <!-- Tồn kho -->
                <div class="col-md-3">

                    <label class="form-label fw-bold">
                        Tồn kho
                    </label>

                    <input
                        type="number"
                        class="form-control"
                        name="stock"
                        min="0"
                        placeholder="Ví dụ: 10"
                        required
                    >

                </div>


                <!-- Trạng thái -->
                <div class="col-md-3">

                    <label class="form-label fw-bold">
                        Trạng thái
                    </label>

                    <select
                        class="form-select"
                        name="status"
                    >
                        <option value="1">
                            Đang bán
                        </option>

                        <option value="0">
                            Tạm ẩn
                        </option>
                    </select>

                </div>


                <!-- Đường dẫn ảnh -->
                <div class="col-md-6">

                    <label class="form-label fw-bold">
                        Đường dẫn ảnh
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        name="image"
                        placeholder="/assets/images/products/images.jpeg"
                    >

                    <small class="text-muted">
                        Ví dụ: /assets/images/products/images.jpeg
                    </small>

                </div>


                <!-- Mô tả -->
                <div class="col-12">

                    <label class="form-label fw-bold">
                        Mô tả sản phẩm
                    </label>

                    <textarea
                        class="form-control"
                        name="description"
                        rows="4"
                        placeholder="Nhập thông tin chi tiết về điện thoại..."
                    ></textarea>

                </div>


                <!-- Nút thêm -->
                <div class="col-12">

                    <button
                        type="submit"
                        class="btn btn-warning px-4"
                    >
                        + Thêm sản phẩm
                    </button>

                </div>

            </form>

        </div>

    </div>


    <!-- ==========================================
         TÌM KIẾM
    ========================================== -->

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form
                method="get"
                action="/admin/products"
                class="row g-2"
            >

                <div class="col-md-10">

                    <input
                        type="text"
                        class="form-control"
                        name="keyword"
                        value="<?= htmlspecialchars($keyword ?? '') ?>"
                        placeholder="Tìm kiếm theo tên điện thoại..."
                    >

                </div>

                <div class="col-md-2 d-grid">

                    <button
                        type="submit"
                        class="btn btn-dark"
                    >
                        Tìm kiếm
                    </button>

                </div>

            </form>

        </div>

    </div>


    <!-- ==========================================
         DANH SÁCH SẢN PHẨM
    ========================================== -->

    <div class="card shadow-sm border-0">

        <div class="card-body p-4">

            <div class="d-flex justify-content-between mb-3">

                <h5 class="fw-bold mb-0">
                    Danh sách sản phẩm
                </h5>

                <span class="text-muted">
                    <?= count($products) ?> sản phẩm
                </span>

            </div>


            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">

                        <tr>
                            <th>ID</th>
                            <th>Ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Giá</th>
                            <th>Tồn kho</th>
                            <th>Trạng thái</th>
                            <th class="text-center">
                                Thao tác
                            </th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php if (empty($products)): ?>

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center text-muted py-4"
                                >
                                    Chưa có sản phẩm nào.
                                </td>

                            </tr>

                        <?php else: ?>

                            <?php foreach ($products as $p): ?>

                                <tr>

                                    <!-- ID -->
                                    <td>
                                        <?= (int) $p['id'] ?>
                                    </td>


                                    <!-- Ảnh -->
                                    <td>

                                        <?php if (!empty($p['image'])): ?>

                                            <img
                                                src="<?= htmlspecialchars($p['image']) ?>"
                                                alt="<?= htmlspecialchars($p['name']) ?>"
                                                style="
                                                    width: 70px;
                                                    height: 70px;
                                                    object-fit: contain;
                                                    background: #f8f9fa;
                                                    border-radius: 8px;
                                                    padding: 5px;
                                                "
                                            >

                                        <?php else: ?>

                                            <span class="text-muted small">
                                                Chưa có ảnh
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <!-- Tên -->
                                    <td>

                                        <strong>
                                            <?= htmlspecialchars($p['name']) ?>
                                        </strong>

                                        <?php if (!empty($p['description'])): ?>

                                            <div
                                                class="text-muted small mt-1"
                                                style="
                                                    max-width: 250px;
                                                    white-space: nowrap;
                                                    overflow: hidden;
                                                    text-overflow: ellipsis;
                                                "
                                            >
                                                <?= htmlspecialchars($p['description']) ?>
                                            </div>

                                        <?php endif; ?>

                                    </td>


                                    <!-- Giá -->
                                    <td>

                                        <?php if (
                                            !empty($p['sale_price']) &&
                                            (float) $p['sale_price'] > 0
                                        ): ?>

                                            <div
                                                class="text-muted small text-decoration-line-through"
                                            >
                                                <?= number_format(
                                                    (float) $p['price'],
                                                    0,
                                                    ',',
                                                    '.'
                                                ) ?> đ
                                            </div>

                                            <strong class="text-danger">

                                                <?= number_format(
                                                    (float) $p['sale_price'],
                                                    0,
                                                    ',',
                                                    '.'
                                                ) ?> đ

                                            </strong>

                                        <?php else: ?>

                                            <strong>

                                                <?= number_format(
                                                    (float) $p['price'],
                                                    0,
                                                    ',',
                                                    '.'
                                                ) ?> đ

                                            </strong>

                                        <?php endif; ?>

                                    </td>


                                    <!-- Tồn kho -->
                                    <td>
                                        <?= (int) $p['stock'] ?>
                                    </td>


                                    <!-- Trạng thái -->
                                    <td>

                                        <?php if ((int) $p['status'] === 1): ?>

                                            <span class="badge bg-success">
                                                Đang bán
                                            </span>

                                        <?php else: ?>

                                            <span class="badge bg-secondary">
                                                Tạm ẩn
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <!-- Thao tác -->
                                    <td class="text-center">

                                        <!-- Nút sửa -->
                                        <a
                                            href="/admin/products/edit?id=<?= (int) $p['id'] ?>"
                                            class="btn btn-primary btn-sm mb-1"
                                        >
                                            Sửa
                                        </a>


                                        <!-- Nút xóa -->
                                        <form
                                            method="post"
                                            action="/admin/products/delete"
                                            class="d-inline"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa vĩnh viễn sản phẩm này không?');"
                                        >

                                            <input
                                                type="hidden"
                                                name="id"
                                                value="<?= (int) $p['id'] ?>"
                                            >

                                            <button
                                                type="submit"
                                                class="btn btn-danger btn-sm mb-1"
                                            >
                                                Xóa
                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>