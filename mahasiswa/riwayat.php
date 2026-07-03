<?php
// mahasiswa/riwayat.php
// View for Mahasiswa Setoran History

require_once __DIR__ . '/../models/SetoranModel.php';
$setoranModel = new SetoranModel();
$mhsId = $_SESSION['user']['id_mahasiswa'];
$mySetorans = $setoranModel->findByMahasiswaId($mhsId);
?>

<div class="container-fluid py-2">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold text-accent-dark mb-1">Riwayat Setoran Saya</h2>
            <p class="text-muted mb-0">Daftar rekaman seluruh setoran hafalan Al-Qur'an Anda.</p>
        </div>
    </div>

    <!-- Table Container -->
    <div class="glass-card p-4">
        <!-- Search -->
        <div class="row mb-3">
            <div class="col-12 col-md-4 ms-auto">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="border-radius: 12px 0 0 12px; border-color: var(--glass-border); color: var(--text-muted);">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control border-start-0 table-search" data-target="table-riwayat" placeholder="Cari surat, status, tanggal..." style="border-radius: 0 12px 12px 0; border-color: var(--glass-border);">
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table align-middle table-glass" id="table-riwayat">
                <thead>
                    <tr>
                        <th style="width: 10%;">No</th>
                        <th style="width: 25%;">Nama Surat</th>
                        <th style="width: 20%;">Rentang Ayat</th>
                        <th style="width: 15%;">Tanggal</th>
                        <th style="width: 15%;">Status</th>
                        <th style="width: 15%;">Ustad Penilai</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($mySetorans)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-info-circle fs-3 d-block mb-2"></i>
                                Anda belum memiliki catatan setoran. Silakan hubungi ustad/dosen untuk menyetor.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($mySetorans as $index => $s): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td class="fw-bold text-accent-dark"><?= htmlspecialchars($s['nama_surat']) ?></td>
                                <td class="font-monospace">Ayat <?= $s['ayat_awal'] ?> - <?= $s['ayat_akhir'] ?></td>
                                <td><?= date('d M Y', strtotime($s['tanggal'])) ?></td>
                                <td>
                                    <?php 
                                    $badge = 'badge-proses';
                                    if ($s['status'] === 'Lulus') $badge = 'badge-lulus';
                                    if ($s['status'] === 'Mengulang') $badge = 'badge-mengulang';
                                    ?>
                                    <span class="<?= $badge ?> font-monospace" style="font-size: 0.8rem;"><?= $s['status'] ?></span>
                                    
                                    <?php if (!empty($s['catatan'])): ?>
                                        <small class="d-block text-muted text-truncate mt-1" style="max-width: 180px;" title="<?= htmlspecialchars($s['catatan']) ?>">
                                            <i class="bi bi-chat-left-dots text-accent-dark me-1"></i><?= htmlspecialchars($s['catatan']) ?>
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td class="small text-muted"><?= htmlspecialchars($s['ustad_nama'] ? $s['ustad_nama'] : 'Belum Dinilai') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
