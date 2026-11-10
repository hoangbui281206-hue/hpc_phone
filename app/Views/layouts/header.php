<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>HPC PHONE - Điện thoại chính hãng</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="/assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
 <div class="container"><a class="navbar-brand fw-bold" href="/">HPC PHONE</a>
 <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
 <div class="collapse navbar-collapse" id="nav"><ul class="navbar-nav ms-auto align-items-lg-center">
 <li class="nav-item"><a class="nav-link" href="/products">Điện thoại</a></li>
 <li class="nav-item"><a class="nav-link" href="/cart">Giỏ hàng (<?= array_sum(array_column($_SESSION['cart'] ?? [], 'quantity')) ?>)</a></li>
 <?php if (!empty($_SESSION['user'])): ?>
 <li class="nav-item"><span class="nav-link">Xin chào, <?= htmlspecialchars($_SESSION['user']['name']) ?></span></li>
 <?php if (($_SESSION['user']['role'] ?? '') === 'admin'): ?><li class="nav-item"><a class="nav-link" href="/admin">Quản trị</a></li><?php endif; ?>
 <li class="nav-item"><form method="post" action="/logout"><button class="btn btn-outline-light btn-sm">Đăng xuất</button></form></li>
 <?php else: ?><li class="nav-item"><a class="nav-link" href="/login">Đăng nhập</a></li><li class="nav-item"><a class="btn btn-warning btn-sm ms-lg-2" href="/register">Đăng ký</a></li><?php endif; ?>
 </ul></div></div>
</nav>
<main class="container py-4">
<?php if (!empty($_SESSION['flash'])): ?><div class="alert alert-info"><?= htmlspecialchars($_SESSION['flash']); unset($_SESSION['flash']); ?></div><?php endif; ?>
