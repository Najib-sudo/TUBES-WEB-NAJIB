<?php
// admin/dashboard.php
// View for Ustad Dashboard

require_once __DIR__ . '/../models/SetoranModel.php';
require_once __DIR__ . '/../models/MahasiswaModel.php';

$setoranModel = new SetoranModel();
$mhsModel = new MahasiswaModel();

$stats = $setoranModel->getSummaryStats();
$activeStudents = $mhsModel->getActiveStudents();
$allSetorans = $setoranModel->getAll();

// Take last 5 setoran
$recentSetorans = array_slice($allSetorans, 0, 5);
?>

<div class="container-fluid py-2">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold text-accent-dark mb-1">Dashboard Ustad</h2>
            <p class="text-muted mb-0">Selamat datang, kelola pencatatan setoran hafalan hari ini secara efisien.</p>
        </div>
        <div class="d-none d-sm-block text-end">
            <span class="text-muted font-monospace"><i class="bi bi-calendar3 me-2"></i><?= date('d M Y') ?></span>
        </div>
    </div>

    <!-- Stats Summary Cards -->
    <div class="row g-4 mb-4">
        <!-- Total Mahasiswa Card -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="glass-card p-4 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-semibold d-block mb-1">Total Mahasiswa</span>
                    <h2 class="fw-bold text-accent-dark counter-anim m-0" data-target="<?= $stats['total_mahasiswa'] ?>">0</h2>
                </div>
                <div class="rounded-4 bg-primary bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; color: var(--accent);">
                    <i class="bi bi-people-fill fs-3"></i>
                </div>
            </div>
        </div>

        <!-- Total Setoran Card -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="glass-card p-4 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-semibold d-block mb-1">Total Setoran</span>
                    <h2 class="fw-bold text-accent-dark counter-anim m-0" data-target="<?= $stats['total_setoran'] ?>">0</h2>
                </div>
                <div class="rounded-4 bg-success bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; color: var(--success);">
                    <i class="bi bi-journal-bookmark-fill fs-3"></i>
                </div>
            </div>
        </div>

        <!-- Setoran Hari Ini Card -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="glass-card p-4 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-semibold d-block mb-1">Setoran Hari Ini</span>
                    <h2 class="fw-bold text-accent-dark counter-anim m-0" data-target="<?= $stats['setoran_hari_ini'] ?>">0</h2>
                </div>
                <div class="rounded-4 bg-warning bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; color: #856404;">
                    <i class="bi bi-calendar-check-fill fs-3"></i>
                </div>
            </div>
        </div>

        <!-- Total Surat Card -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="glass-card p-4 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-semibold d-block mb-1">Total Surat Juz 30</span>
                    <h2 class="fw-bold text-accent-dark counter-anim m-0" data-target="<?= $stats['total_surat'] ?>">0</h2>
                </div>
                <div class="rounded-4 bg-info bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; color: #0dcaf0;">
                    <i class="bi bi-book-half fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Students & Recent Setorans -->
    <div class="row g-4">
        <!-- Active Students (Left Column) -->
        <div class="col-12 col-lg-5">
            <div class="glass-card p-4 h-100">
                <h5 class="fw-bold text-accent-dark mb-3"><i class="bi bi-award-fill text-warning me-2"></i>Mahasiswa Teraktif</h5>
                <p class="text-muted small mb-4">Mahasiswa dengan jumlah setoran lulus terbanyak di Juz 30.</p>
                
                <?php if (empty($activeStudents)): ?>
                    <div class="text-center py-4">
                        <i class="bi bi-inboxes text-muted fs-2"></i>
                        <p class="text-muted mt-2">Belum ada data setoran yang dinilai lulus.</p>
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush bg-transparent">
                        <?php foreach ($activeStudents as $index => $student): ?>
                            <div class="list-group-item bg-transparent d-flex align-items-center border-0 border-bottom border-light px-0 py-3">
                                <div class="d-flex align-items-center justify-content-center rounded-circle font-monospace text-white shadow-sm me-3" 
                                     style="width: 32px; height: 32px; background: <?= ($index == 0) ? 'gold' : (($index == 1) ? 'silver' : (($index == 2) ? '#cd7f32' : 'var(--primary)')) ?>;">
                                    <?= $index + 1 ?>
                                </div>
                                <div class="flex-grow-1 min-w-0">
                                    <h6 class="mb-0 text-accent-dark fw-bold text-truncate"><?= htmlspecialchars($student['nama']) ?></h6>
                                    <small class="text-muted font-monospace small"><?= htmlspecialchars($student['nim']) ?></small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-success bg-opacity-25 text-success rounded-3 px-3 py-2 border border-success-subtle fw-semibold">
                                        <?= $student['total_setoran'] ?> Setoran
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recent Setorans (Right Column) -->
        <div class="col-12 col-lg-7">
            <div class="glass-card p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="fw-bold text-accent-dark mb-0"><i class="bi bi-clock-history text-success me-2"></i>Setoran Terbaru</h5>
                    <a href="index.php?page=setoran" class="btn btn-sm btn-glass-secondary py-1 px-3 border-0">Lihat Semua</a>
                </div>
                <p class="text-muted small mb-4">Aktivitas pencatatan setoran hafalan terkini oleh santri.</p>

                <?php if (empty($recentSetorans)): ?>
                    <div class="text-center py-4">
                        <i class="bi bi-inboxes text-muted fs-2"></i>
                        <p class="text-muted mt-2">Belum ada data setoran hafalan.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                            <thead>
                                <tr>
                                    <th class="border-0 bg-transparent text-muted fw-semibold">Mahasiswa</th>
                                    <th class="border-0 bg-transparent text-muted fw-semibold">Surat</th>
                                    <th class="border-0 bg-transparent text-muted fw-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentSetorans as $setoran): ?>
                                    <tr>
                                        <td class="ps-0 border-bottom border-light">
                                            <div class="fw-bold text-accent-dark"><?= htmlspecialchars($setoran['mahasiswa_nama']) ?></div>
                                            <small class="text-muted font-monospace small"><?= htmlspecialchars($setoran['nim']) ?></small>
                                        </td>
                                        <td class="border-bottom border-light">
                                            <strong><?= htmlspecialchars($setoran['nama_surat']) ?></strong>
                                            <small class="d-block text-muted">Ayat <?= $setoran['ayat_awal'] ?> - <?= $setoran['ayat_akhir'] ?></small>
                                        </td>
                                        <td class="pe-0 border-bottom border-light">
                                            <?php 
                                            $badgeClass = 'badge-proses';
                                            if ($setoran['status'] === 'Lulus') $badgeClass = 'badge-lulus';
                                            if ($setoran['status'] === 'Mengulang') $badgeClass = 'badge-mengulang';
                                            ?>
                                            <span class="<?= $badgeClass ?> font-monospace" style="font-size: 0.8rem;"><?= $setoran['status'] ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
