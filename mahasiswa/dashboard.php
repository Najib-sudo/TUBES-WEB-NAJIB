<?php
// mahasiswa/dashboard.php
// View for Mahasiswa Dashboard

require_once __DIR__ . '/../models/SetoranModel.php';
require_once __DIR__ . '/../models/MahasiswaModel.php';

$setoranModel = new SetoranModel();
$mhsModel = new MahasiswaModel();

$mhsId = $_SESSION['user']['id_mahasiswa'];
$mhsData = $mhsModel->findById($mhsId);
$progress = $setoranModel->getMahasiswaProgress($mhsId);
$mySetorans = $setoranModel->findByMahasiswaId($mhsId);

// Count mengulang
$mengulangCount = count(array_filter($mySetorans, function($s) { return $s['status'] === 'Mengulang'; }));

// Islamic motivational quotes
$quotes = [
    [
        'text'   => "Sebaik-baik kalian adalah orang yang belajar Al-Qur'an dan mengajarkannya.",
        'source' => "HR. Bukhari"
    ],
    [
        'text'   => "Bacalah Al-Qur'an, karena sesungguhnya ia akan datang pada hari kiamat sebagai pemberi syafaat bagi para pembacanya.",
        'source' => "HR. Muslim"
    ],
    [
        'text'   => "Dikatakan kepada pembaca Al-Qur'an: Bacalah, naiklah, dan tartilkanlah sebagaimana kamu mentartilkannya di dunia. Karena kedudukanmu berada di akhir ayat yang kamu baca.",
        'source' => "HR. Abu Dawud & Tirmidzi"
    ]
];
$randomQuote = $quotes[array_rand($quotes)];
?>

<div class="container-fluid py-2">

    <!-- ── Page Header ── -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--accent-dark);">Dashboard Mahasiswa</h2>
            <p class="mb-0" style="color: var(--text-muted);">Semoga harimu dipenuhi keberkahan bersama Al-Qur'an.</p>
        </div>
        <span class="font-monospace px-3 py-2 rounded-3"
              style="background:rgba(76,175,130,0.10); color:var(--accent); font-size:0.82rem; border:1px solid rgba(76,175,130,0.2);">
            <i class="bi bi-calendar3 me-2"></i><?= date('d M Y') ?>
        </span>
    </div>

    <div class="row g-4">

        <!-- ── Student Profile Card ── -->
        <div class="col-12 col-md-4">
            <div class="glass-card p-4 h-100 text-center">

                <!-- Avatar -->
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle fw-bold text-white"
                     style="width:80px;height:80px;font-size:2.2rem;background:linear-gradient(135deg,#1B8A4E,#4CAF82);box-shadow:0 6px 20px rgba(27,138,78,0.30);">
                    <?= strtoupper(substr($mhsData['nama'], 0, 1)) ?>
                </div>

                <!-- Name -->
                <h5 class="fw-bold mb-2" style="color: var(--accent-dark);">
                    <?= htmlspecialchars($mhsData['nama']) ?>
                </h5>

                <!-- NIM Badge -->
                <span class="badge px-3 py-1 mb-4 d-inline-block font-monospace"
                      style="background:rgba(27,138,78,0.10); color:var(--accent); border:1px solid rgba(27,138,78,0.25); border-radius:20px; font-size:0.78rem;">
                    <?= htmlspecialchars($mhsData['nim']) ?>
                </span>

                <!-- Details -->
                <div class="text-start" style="border-top:1px solid rgba(76,175,130,0.15); padding-top:16px;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="small" style="color:var(--text-muted);">Program Studi</span>
                        <strong class="small text-end ms-2" style="color:var(--accent-dark); max-width:60%;">
                            <?= htmlspecialchars($mhsData['prodi']) ?>
                        </strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small" style="color:var(--text-muted);">Angkatan</span>
                        <strong class="small" style="color:var(--accent-dark);">
                            <?= htmlspecialchars($mhsData['angkatan']) ?>
                        </strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Progress Card ── -->
        <div class="col-12 col-md-8">
            <div class="glass-card p-4 h-100">
                <h5 class="fw-bold mb-1" style="color:var(--accent-dark);">
                    <i class="bi bi-trophy-fill me-2" style="color:#F4C430;"></i>Progress Hafalan (Juz 30)
                </h5>
                <p class="small mb-4" style="color:var(--text-muted);">Grafik tingkat kelulusan setoran surat-surat di Juz 30.</p>

                <!-- Big Percentage -->
                <div class="d-flex align-items-baseline gap-2 mb-3">
                    <h1 class="fw-bold m-0 counter-anim"
                        data-target="<?= $progress['percentage'] ?>"
                        style="color:var(--accent-dark); font-size:3rem;">0</h1>
                    <span class="fs-4 fw-semibold" style="color:var(--accent);">% Selesai</span>
                </div>

                <!-- Progress Bar -->
                <div class="progress mb-4" style="height:14px; border-radius:20px; background:rgba(76,175,130,0.15);">
                    <div class="progress-bar"
                         role="progressbar"
                         style="width:<?= $progress['percentage'] ?>%;
                                background: linear-gradient(90deg, #1B8A4E, #4CAF82);
                                border-radius:20px;
                                box-shadow: 0 2px 10px rgba(27,138,78,0.35);"
                         aria-valuenow="<?= $progress['percentage'] ?>"
                         aria-valuemin="0"
                         aria-valuemax="100">
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="row g-3">
                    <div class="col-6 col-sm-4">
                        <div class="rounded-4 p-3 text-center"
                             style="background:rgba(76,175,130,0.08); border:1px solid rgba(76,175,130,0.18);">
                            <small class="d-block mb-1" style="color:var(--text-muted);">Surat Lulus</small>
                            <h4 class="fw-bold m-0" style="color:var(--accent-dark);">
                                <?= $progress['surat_lulus'] ?> <span class="text-muted fs-6">/ <?= $progress['total_surat'] ?></span>
                            </h4>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4">
                        <div class="rounded-4 p-3 text-center"
                             style="background:rgba(220,53,69,0.06); border:1px solid rgba(220,53,69,0.15);">
                            <small class="d-block mb-1" style="color:var(--text-muted);">Surat Mengulang</small>
                            <h4 class="fw-bold m-0 text-danger"><?= $mengulangCount ?></h4>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="rounded-4 p-3 text-center"
                             style="background:rgba(76,175,130,0.08); border:1px solid rgba(76,175,130,0.18);">
                            <small class="d-block mb-1" style="color:var(--text-muted);">Setoran Terakhir</small>
                            <strong class="d-block text-truncate" style="color:var(--accent-dark); font-size:0.92rem;">
                                <?= $progress['last_setoran'] ? htmlspecialchars($progress['last_setoran']['nama_surat']) : 'Belum Ada' ?>
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Motivational Quote Card ── -->
        <div class="col-12">
            <div class="glass-card p-4"
                 style="background: linear-gradient(135deg, rgba(27,138,78,0.06), rgba(76,175,130,0.10)); border: 1px solid rgba(76,175,130,0.20);">
                <div class="d-flex align-items-start gap-4">

                    <!-- Quote Icon -->
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:52px; height:52px; background: linear-gradient(135deg,#1B8A4E,#4CAF82); box-shadow: 0 4px 14px rgba(27,138,78,0.30);">
                        <i class="bi bi-chat-quote-fill text-white" style="font-size:1.4rem;"></i>
                    </div>

                    <!-- Quote Content -->
                    <div class="flex-grow-1 min-width-0">
                        <h6 class="fw-bold mb-2" style="color:var(--accent-dark);">
                            <i class="bi bi-star-fill me-1" style="color:#F4C430; font-size:0.8rem;"></i>
                            Mutiara Hikmah
                        </h6>
                        <p class="fst-italic mb-2" style="color:var(--text); line-height:1.7; font-size:0.95rem;">
                            "<?= htmlspecialchars($randomQuote['text']) ?>"
                        </p>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:24px; height:2px; background:var(--accent); border-radius:2px;"></div>
                            <span class="fw-semibold font-monospace" style="color:var(--accent); font-size:0.78rem;">
                                <?= htmlspecialchars($randomQuote['source']) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
