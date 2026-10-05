<?php require_once BASE_PATH . '/views/layouts/header.php'; ?>

<div class="ps-admin-layout">
    <?php require_once BASE_PATH . '/views/layouts/sidebar_admin.php'; ?>

    <main class="ps-admin-main">
        <header class="ps-topbar">
            <div class="ps-topbar-left">
                <button class="ps-sidebar-toggle" id="sidebarToggle">
                    <i data-lucide="panel-left-close"></i>
                </button>
                <div>
                    <h1 class="ps-page-title">Manajemen Pengguna</h1>
                    <p class="ps-page-subtitle">Data seluruh pengguna terdaftar</p>
                </div>
            </div>
        </header>

        <div class="ps-admin-content">
            <!-- Stats mini -->
            <div class="ps-mini-stats mb-4">
                <div class="ps-mini-stat">
                    <i data-lucide="users"></i>
                    <div>
                        <strong><?= count($users) ?></strong>
                        <span>Total Pengguna</span>
                    </div>
                </div>
                <div class="ps-mini-stat">
                    <i data-lucide="shield-check"></i>
                    <div>
                        <strong><?= $totalAdmin ?></strong>
                        <span>Administrator</span>
                    </div>
                </div>
                <div class="ps-mini-stat">
                    <i data-lucide="user"></i>
                    <div>
                        <strong><?= $totalUser ?></strong>
                        <span>Pengguna Aktif</span>
                    </div>
                </div>
            </div>

            <div class="ps-card">
                <div class="ps-card-header">
                    <h3 class="ps-card-title">
                        <i data-lucide="users"></i> Daftar Pengguna
                        <span class="ps-badge ps-badge-primary ms-2"><?= count($users) ?></span>
                    </h3>
                </div>
                <div class="ps-card-body p-0">
                    <?php if (empty($users)): ?>
                        <div class="ps-empty-state">
                            <i data-lucide="user-x"></i>
                            <p>Belum ada data pengguna</p>
                        </div>
                    <?php else: ?>
                        <div class="ps-table-wrap">
                            <table class="ps-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Pengguna</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Bergabung</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $i => $u): ?>
                                        <tr>
                                            <td class="ps-td-num"><?= $i + 1 ?></td>
                                            <td>
                                                <div class="ps-user-cell">
                                                    <div class="ps-user-avatar-tbl">
                                                        <?= strtoupper(substr($u['nama'], 0, 1)) ?>
                                                    </div>
                                                    <span><?= e($u['nama']) ?></span>
                                                </div>
                                            </td>
                                            <td><code class="ps-code-text">@<?= e($u['username']) ?></code></td>
                                            <td><?= e($u['email']) ?></td>
                                            <td>
                                                <?php if ($u['role'] === 'admin'): ?>
                                                    <span class="ps-badge ps-badge-primary">
                                                        <i data-lucide="shield-check" style="width:12px;height:12px"></i>
                                                        Admin
                                                    </span>
                                                <?php else: ?>
                                                    <span class="ps-badge ps-badge-secondary">
                                                        <i data-lucide="user" style="width:12px;height:12px"></i>
                                                        User
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= date('d M Y', strtotime($u['created_at'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>