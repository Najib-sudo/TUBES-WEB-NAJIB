<?php
// Includes/navbar.php
$currentUser = isset($_SESSION['user']) ? $_SESSION['user'] : null;
?>
<nav class="navbar navbar-expand-lg navbar-light fixed-top navbar-glass py-2 px-4">
    <div class="container-fluid px-0">
        <!-- Sidebar Toggle Icon -->
        <button class="btn btn-glass-secondary border-0 p-2 me-3 flex-shrink-0" id="sidebar-toggle" type="button" aria-label="Toggle Sidebar">
            <i class="bi bi-justify-left fs-5" style="color: var(--accent);"></i>
        </button>

        <!-- Brand with Logo -->
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="index.php" style="color: var(--accent-dark); text-decoration:none;">
            <img src="assets/images/logo.png" alt="Logo Setoran Hafalan"
                 style="width:40px; height:40px; object-fit:contain; border-radius:50%; flex-shrink:0;"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <!-- Fallback icon if logo fails -->
            <div class="d-none align-items-center justify-content-center rounded-3 flex-shrink-0"
                 style="width:36px;height:36px;background:linear-gradient(135deg,#1B8A4E,#4CAF82);color:#fff;font-size:1.1rem;">
                <i class="bi bi-journal-bookmark-fill"></i>
            </div>
            <div class="d-none d-sm-block">
                <div class="fw-bold lh-1" style="color:var(--accent-dark); font-size:0.95rem;">Setoran Hafalan</div>
                <div style="color:var(--text-muted); font-size:0.65rem; font-weight:400; letter-spacing:0.04em;">Sistem Pencatatan Al-Qur'an</div>
            </div>
            <?php if ($currentUser): ?>
            <span class="badge border fw-semibold ms-1 px-2 py-1 d-none d-md-inline"
                  style="font-size:0.65rem; background:#F0FAF5; color:var(--accent); border-color:rgba(76,175,130,0.3)!important;">
                <?= ucfirst($currentUser['role']) ?>
            </span>
            <?php endif; ?>
        </a>

        <!-- User Profile Dropdown -->
        <div class="ms-auto d-flex align-items-center">
            <?php if ($currentUser): ?>
                <div class="dropdown">
                    <button class="btn btn-glass-secondary dropdown-toggle d-flex align-items-center gap-2 border-0 px-3 py-2"
                            type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                             style="width:34px; height:34px; font-size:0.95rem; background: linear-gradient(135deg, #1B8A4E, #4CAF82); box-shadow: 0 2px 8px rgba(27,138,78,0.3);">
                            <?= strtoupper(substr($currentUser['nama'], 0, 1)) ?>
                        </div>
                        <span class="d-none d-md-inline fw-semibold" style="color: var(--accent-dark); font-size: 0.9rem;">
                            <?= htmlspecialchars(explode(' ', $currentUser['nama'])[0]) ?>
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2 mt-2"
                        aria-labelledby="profileDropdown"
                        style="background: rgba(255,255,255,0.97); backdrop-filter: blur(16px); border-radius:16px; min-width:200px;">
                        <li>
                            <div class="px-3 py-2 mb-1" style="border-bottom: 1px solid rgba(76,175,130,0.15);">
                                <p class="mb-0 fw-bold text-truncate" style="color: var(--accent-dark); max-width:180px;">
                                    <?= htmlspecialchars($currentUser['nama']) ?>
                                </p>
                                <small class="font-monospace" style="color: var(--text-muted); font-size:0.72rem;">
                                    @<?= htmlspecialchars($currentUser['username']) ?>
                                </small>
                            </div>
                        </li>
                        <?php if ($currentUser['role'] === 'mahasiswa'): ?>
                        <li>
                            <a class="dropdown-item rounded-3 mt-1 py-2 d-flex align-items-center gap-2"
                               href="index.php?page=profil"
                               style="color: var(--accent-dark); font-size:0.88rem;">
                                <i class="bi bi-person-gear" style="color:var(--accent);"></i> Edit Profil
                            </a>
                        </li>
                        <?php endif; ?>
                        <li><hr class="dropdown-divider my-1" style="border-color: rgba(76,175,130,0.15);"></li>
                        <li>
                            <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2"
                               href="logout.php"
                               style="color: #dc3545; font-size:0.88rem;">
                                <i class="bi bi-box-arrow-right"></i> Keluar
                            </a>
                        </li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
</nav>
