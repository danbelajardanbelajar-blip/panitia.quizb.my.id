<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold m-0">Kolaborasi Tim</h2>
</div>

<?php if (!$isOwner): ?>
<div class="alert alert-warning badge-warning text-dark border-0 rounded-3 mb-4">
    <i data-lucide="info" class="me-2" style="width: 18px; margin-top: -2px;"></i>
    Anda sedang melihat Workspace milik orang lain. Beralih ke Workspace Pribadi Anda untuk mengelola anggota tim Anda sendiri.
</div>
<?php else: ?>
<div class="row">
    <div class="col-md-5 mb-4">
        <div class="card-modern">
            <div class="card-header-modern">
                <div class="d-flex align-items-center gap-2">
                    <i data-lucide="user-plus" class="text-primary"></i>
                    <span>Undang Anggota</span>
                </div>
            </div>
            <div class="card-body-modern">
                <p class="text-muted" style="font-size: 0.9rem;">
                    Anggota yang diundang dapat melihat dan mengelola data Kegiatan, RAPB, dan Keuangan dalam Kepanitiaan Anda.
                </p>
                <form action="tim.php?action=add" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                    <div class="mb-3">
                        <label class="form-label-modern">Alamat Email</label>
                        <input type="email" name="email" class="form-control-modern" placeholder="contoh@gmail.com" required>
                    </div>
                    <button type="submit" class="btn-modern btn-primary-modern w-100">Undang Sekarang</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7 mb-4">
        <div class="card-modern">
            <div class="card-header-modern">
                <div class="d-flex align-items-center gap-2">
                    <i data-lucide="users" class="text-primary"></i>
                    <span>Anggota Tim</span>
                </div>
            </div>
            <div class="table-responsive-modern">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Email</th>
                            <th>Tanggal Diundang</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($members)): ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">Belum ada anggota tim.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($members as $member): ?>
                            <tr>
                                <td class="fw-medium"><?= htmlspecialchars($member['email']) ?></td>
                                <td><?= date('d M Y', strtotime($member['created_at'])) ?></td>
                                <td class="text-end">
                                    <form action="tim.php?action=delete" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akses untuk email ini?');">
                                        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                                        <input type="hidden" name="id" value="<?= $member['id'] ?>">
                                        <button type="submit" class="btn-modern btn-ghost-modern btn-ghost-danger px-2 py-1" title="Hapus Akses">
                                            <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
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
<?php endif; ?>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
