<?php
// Includes/sidebar.php
$currentPage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$userRole = isset($_SESSION['user']['role']) ? $_SESSION['user']['role'] : '';
?>
<aside class="sidebar-glass" id="sidebar">

    <!-- Sidebar Brand Header with Logo -->
    <div class="sidebar-brand-header px-3 py-3" style="border-bottom: 1px solid rgba(76,175,130,0.15);">
        <div class="d-flex align-items-center gap-2">
            <img src="assets/images/logo.png" alt="Logo"
                 style="width:42px; height:42px; object-fit:contain; border-radius:50%; flex-shrink:0; background:#fff;"
                 onerror="this.onerror=null; this.style.display='none'; document.getElementById('sb-icon-fallback').style.display='flex';">
            <!-- Fallback icon -->
            <div id="sb-icon-fallback" class="d-none align-items-center justify-content-center rounded-3 flex-shrink-0"
                 style="width:36px;height:36px;background:linear-gradient(135deg,#1B8A4E,#4CAF82);color:#fff;font-size:1rem;">
                <i class="bi bi-journal-bookmark-fill"></i>
            </div>
            <div class="sidebar-text">
                <div class="fw-bold lh-1" style="color:var(--accent-dark);font-size:0.82rem;">Setoran Hafalan</div>
                <div style="color:var(--text-muted);font-size:0.65rem;letter-spacing:0.03em;">Al-Qur'an Tracker</div>
            </div>
        </div>
    </div>

    <div class="d-flex flex-column justify-content-between pb-4" style="min-height: calc(100% - 80px);">
        <!-- Navigation Links -->
        <ul class="nav flex-column px-2 pt-2">
            <?php if ($userRole === 'ustad'): ?>
                <!-- Ustad Navigation -->
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'dashboard' ? 'active' : '' ?>" href="index.php?page=dashboard">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'mahasiswa' ? 'active' : '' ?>" href="index.php?page=mahasiswa">
                        <i class="bi bi-people-fill"></i>
                        <span class="sidebar-text">Data Mahasiswa</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'surat' ? 'active' : '' ?>" href="index.php?page=surat">
                        <i class="bi bi-book-half"></i>
                        <span class="sidebar-text">Data Surat</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'setoran' ? 'active' : '' ?>" href="index.php?page=setoran">
                        <i class="bi bi-journal-check"></i>
                        <span class="sidebar-text">Setoran Hafalan</span>
                    </a>
                </li>

            <?php elseif ($userRole === 'mahasiswa'): ?>
                <!-- Mahasiswa Navigation -->
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'dashboard' ? 'active' : '' ?>" href="index.php?page=dashboard">
                        <i class="bi bi-house-door-fill"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'riwayat' ? 'active' : '' ?>" href="index.php?page=riwayat">
                        <i class="bi bi-clock-history"></i>
                        <span class="sidebar-text">Riwayat Setoran</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'profil' ? 'active' : '' ?>" href="index.php?page=profil">
                        <i class="bi bi-person-circle"></i>
                        <span class="sidebar-text">Profil Saya</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>

        <!-- Bottom: Role badge + Logout -->
        <div class="px-2">
            <!-- Role Badge -->
            <div class="sidebar-text px-3 mb-2">
                <small class="d-flex align-items-center gap-1 px-2 py-1 rounded-3"
                       style="background:rgba(76,175,130,0.12); color:var(--accent); font-size:0.72rem; font-weight:600;">
                    <i class="bi bi-shield-check-fill"></i>
                    <span><?= ucfirst($userRole) ?></span>
                </small>
            </div>
            <hr class="mx-3 my-1 opacity-25" style="color: var(--text);">
            <a class="nav-link d-flex align-items-center gap-3 py-3 rounded-3 mx-2"
               href="logout.php"
               style="color: #dc3545; font-weight: 500; transition: var(--transition-smooth);"
               onmouseover="this.style.background='rgba(220,53,69,0.08)'"
               onmouseout="this.style.background='transparent'">
                <i class="bi bi-box-arrow-left fs-5"></i>
                <span class="sidebar-text">Keluar</span>
            </a>
        </div>
    </div>
</aside>
