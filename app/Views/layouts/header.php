<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>NEXO TECH - Điện thoại chính hãng</title>

    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- CSS của dự án -->
    <link
        href="/assets/css/style.css"
        rel="stylesheet"
    >
</head>

<body>

<!-- ==========================================
     NAVBAR
========================================== -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">

    <div class="container">

        <!-- Logo -->
        <a
            class="navbar-brand fw-bold"
            href="/"
        >
            NEXO TECH
        </a>

        <!-- Nút menu trên điện thoại -->
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#nav"
            aria-controls="nav"
            aria-expanded="false"
            aria-label="Mở menu"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu -->
        <div
            class="collapse navbar-collapse"
            id="nav"
        >

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <!-- Sản phẩm -->
                <li class="nav-item">
                    <a
                        class="nav-link"
                        href="/products"
                    >
                        Điện thoại
                    </a>
                </li>

                <!-- Giỏ hàng -->
                <li class="nav-item">
                    <a
                        class="nav-link"
                        href="/cart"
                    >
                        Giỏ hàng
                        (<?= array_sum(
                            array_column(
                                $_SESSION['cart'] ?? [],
                                'quantity'
                            )
                        ) ?>)
                    </a>
                </li>

                <?php if (!empty($_SESSION['user'])): ?>

                    <!-- Đơn hàng của tôi -->
                    <li class="nav-item">
                        <a
                            class="nav-link"
                            href="/my-orders"
                        >
                            📦 Đơn hàng của tôi
                        </a>
                    </li>

                    <!-- Tên người dùng -->
                    <li class="nav-item">
                        <span class="nav-link">
                            Xin chào,
                            <?= htmlspecialchars(
                                $_SESSION['user']['name'] ?? 'Khách hàng'
                            ) ?>
                        </span>
                    </li>

                    <!-- Menu Admin -->
                    <?php if (
                        ($_SESSION['user']['role'] ?? '')
                        === 'admin'
                    ): ?>

                        <li class="nav-item">
                            <a
                                class="nav-link"
                                href="/admin"
                            >
                                Quản trị
                            </a>
                        </li>

                    <?php endif; ?>

                    <!-- Đăng xuất -->
                    <li class="nav-item ms-lg-2">

                        <form
                            method="post"
                            action="/logout"
                            class="m-0"
                        >
                            <button
                                type="submit"
                                class="btn btn-outline-light btn-sm"
                            >
                                Đăng xuất
                            </button>
                        </form>

                    </li>

                <?php else: ?>

                    <!-- Đăng nhập -->
                    <li class="nav-item">
                        <a
                            class="nav-link"
                            href="/login"
                        >
                            Đăng nhập
                        </a>
                    </li>

                    <!-- Đăng ký -->
                    <li class="nav-item">
                        <a
                            class="btn btn-warning btn-sm ms-lg-2"
                            href="/register"
                        >
                            Đăng ký
                        </a>
                    </li>

                <?php endif; ?>

            </ul>

        </div>

    </div>

</nav>

<!-- ==========================================
     NỘI DUNG TRANG
========================================== -->
<main class="container py-4">

    <!-- Thông báo hệ thống -->
    <?php if (!empty($_SESSION['flash'])): ?>

        <div
            class="alert alert-info alert-dismissible fade show"
            role="alert"
        >
            <?= htmlspecialchars($_SESSION['flash']) ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Đóng"
            ></button>
        </div>

        <?php unset($_SESSION['flash']); ?>

    <?php endif; ?>