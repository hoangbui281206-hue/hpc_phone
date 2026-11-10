<div class="admin-page">
    <div class="admin-sidebar">
        <h2>⚙️ ADMIN</h2>

        <a href="/admin">📊 Tổng quan</a>
        <a href="/admin/products">📱 Quản lý sản phẩm</a>
        <a href="/admin/orders">📦 Quản lý đơn hàng</a>
        <a href="/admin/users" class="active">👥 Quản lý người dùng</a>

        <hr>

        <a href="/">🏠 Về trang chủ</a>
    </div>

    <div class="admin-content">
        <h1>Quản lý người dùng</h1>

        <?php if (!empty($_SESSION['flash'])): ?>
            <div class="alert">
                <?= htmlspecialchars($_SESSION['flash']) ?>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <div class="admin-card">
            <div class="card-header">
                <h2>Danh sách tài khoản</h2>
                <span>Tổng: <?= count($users) ?> người dùng</span>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Họ tên</th>
                            <th>Email</th>
                            <th>Quyền hiện tại</th>
                            <th>Thay đổi quyền</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="5" class="empty">
                                    Chưa có người dùng nào.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td>#<?= (int) $user['id'] ?></td>

                                    <td>
                                        <?= htmlspecialchars(
                                            $user['name'] ?? 'Chưa có tên'
                                        ) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            $user['email']
                                        ) ?>
                                    </td>

                                    <td>
                                        <?php if ($user['role'] === 'admin'): ?>
                                            <span class="badge admin">
                                                Admin
                                            </span>
                                        <?php else: ?>
                                            <span class="badge customer">
                                                Khách hàng
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <form
                                            action="/admin/users/role"
                                            method="POST"
                                            class="role-form"
                                        >
                                            <input
                                                type="hidden"
                                                name="id"
                                                value="<?= (int) $user['id'] ?>"
                                            >

                                            <select name="role">
                                                <option
                                                    value="customer"
                                                    <?= $user['role'] === 'customer'
                                                        ? 'selected'
                                                        : '' ?>
                                                >
                                                    Khách hàng
                                                </option>

                                                <option
                                                    value="admin"
                                                    <?= $user['role'] === 'admin'
                                                        ? 'selected'
                                                        : '' ?>
                                                >
                                                    Admin
                                                </option>
                                            </select>

                                            <button
                                                type="submit"
                                                onclick="return confirm(
                                                    'Bạn có chắc muốn thay đổi quyền người dùng này?'
                                                )"
                                            >
                                                Cập nhật
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

<style>
.admin-page {
    display: flex;
    min-height: 650px;
    margin: 20px 0;
    gap: 25px;
}

.admin-sidebar {
    width: 230px;
    flex-shrink: 0;
    background: #212529;
    border-radius: 10px;
    padding: 20px 15px;
    box-sizing: border-box;
}

.admin-sidebar h2 {
    color: white;
    margin: 0 0 25px;
    text-align: center;
}

.admin-sidebar a {
    display: block;
    color: #ddd;
    text-decoration: none;
    padding: 13px 15px;
    margin-bottom: 7px;
    border-radius: 6px;
}

.admin-sidebar a:hover,
.admin-sidebar a.active {
    background: #ffc107;
    color: #212529;
}

.admin-sidebar hr {
    border: none;
    border-top: 1px solid #555;
    margin: 20px 0;
}

.admin-content {
    flex: 1;
    min-width: 0;
}

.admin-content h1 {
    margin-top: 0;
}

.admin-card {
    background: white;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 20px;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.card-header h2 {
    margin: 0;
}

.card-header span {
    color: #666;
}

.table-wrapper {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    padding: 13px 10px;
    border-bottom: 1px solid #ddd;
    text-align: left;
}

th {
    background: #f5f5f5;
}

.empty {
    text-align: center;
    color: #777;
    padding: 30px;
}

.badge {
    display: inline-block;
    padding: 6px 10px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: bold;
}

.badge.admin {
    background: #ffe08a;
    color: #6b5000;
}

.badge.customer {
    background: #d1e7dd;
    color: #0f5132;
}

.role-form {
    display: flex;
    gap: 8px;
}

.role-form select {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.role-form button {
    padding: 8px 12px;
    border: none;
    background: #ffc107;
    color: #212529;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
}

.alert {
    padding: 12px;
    margin-bottom: 20px;
    background: #d1e7dd;
    color: #0f5132;
    border-radius: 6px;
}

@media (max-width: 800px) {
    .admin-page {
        flex-direction: column;
    }

    .admin-sidebar {
        width: 100%;
    }

    .role-form {
        flex-direction: column;
    }
}
</style>