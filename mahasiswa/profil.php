<?php
// mahasiswa/profil.php
// View for editing student profile

require_once __DIR__ . '/../models/MahasiswaModel.php';
$mhsModel = new MahasiswaModel();
$mhsId = $_SESSION['user']['id_mahasiswa'];
$mhsData = $mhsModel->findById($mhsId);
?>

<div class="container-fluid py-2">
    <!-- Header -->
    <div class="mb-4">
        <h2 class="fw-bold text-accent-dark mb-1">Pengaturan Profil Saya</h2>
        <p class="text-muted mb-0">Kelola data informasi personal dan kata sandi login Anda.</p>
    </div>

    <div class="row g-4">
        <!-- Profile Card Display (Left) -->
        <div class="col-12 col-lg-4">
            <div class="glass-card p-4 text-center">
                <div class="bg-success text-white d-flex align-items-center justify-content-center rounded-circle shadow mx-auto mb-3" style="width: 90px; height: 90px; font-weight: 700; font-size: 2.5rem;">
                    <?= strtoupper(substr($mhsData['nama'], 0, 1)) ?>
                </div>
                <h5 class="fw-bold text-accent-dark mb-1"><?= htmlspecialchars($mhsData['nama']) ?></h5>
                <span class="badge bg-light text-accent-dark border border-success-subtle px-3 py-1 font-monospace mb-4" style="font-size: 0.8rem;"><?= htmlspecialchars($mhsData['nim']) ?></span>
                
                <div class="text-start border-top border-light pt-3">
                    <div class="mb-3">
                        <label class="text-muted small fw-semibold d-block mb-1">Program Studi</label>
                        <strong class="text-accent-dark d-block"><?= htmlspecialchars($mhsData['prodi']) ?></strong>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small fw-semibold d-block mb-1">Angkatan Akademik</label>
                        <strong class="text-accent-dark d-block"><?= htmlspecialchars($mhsData['angkatan']) ?></strong>
                    </div>
                    <div>
                        <label class="text-muted small fw-semibold d-block mb-1">Username Login</label>
                        <strong class="text-accent-dark d-block font-monospace">@<?= htmlspecialchars($mhsData['username']) ?></strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Profile Form (Right) -->
        <div class="col-12 col-lg-8">
            <div class="glass-card p-4">
                <h5 class="fw-bold text-accent-dark mb-3"><i class="bi bi-person-gear me-2"></i>Ubah Detail Profil</h5>
                <p class="text-muted small mb-4">Gunakan form di bawah ini untuk memperbarui informasi profil dan sandi login.</p>

                <form action="index.php?page=profil&action=update" method="POST" class="needs-validation-profile" novalidate>
                    <!-- Academic Details (Read Only) -->
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-sm-6">
                            <div class="form-floating">
                                <input type="text" class="form-control bg-light bg-opacity-50" id="read_nim" value="<?= htmlspecialchars($mhsData['nim']) ?>" disabled>
                                <label for="read_nim">Nomor Induk Mahasiswa (NIM)</label>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-floating">
                                <input type="text" class="form-control bg-light bg-opacity-50" id="read_prodi" value="<?= htmlspecialchars($mhsData['prodi']) ?>" disabled>
                                <label for="read_prodi">Program Studi</label>
                            </div>
                        </div>
                    </div>

                    <!-- Editable Nama -->
                    <div class="form-floating mb-3">
                        <input type="text" name="nama" class="form-control" id="edit_nama" value="<?= htmlspecialchars($mhsData['nama']) ?>" placeholder="Nama Lengkap" required>
                        <label for="edit_nama">Nama Lengkap</label>
                        <div class="invalid-feedback">Nama lengkap tidak boleh kosong.</div>
                    </div>

                    <!-- Editable Username -->
                    <div class="form-floating mb-3">
                        <input type="text" name="username" class="form-control" id="edit_username" value="<?= htmlspecialchars($mhsData['username']) ?>" placeholder="Username" required>
                        <label for="edit_username">Username Login</label>
                        <div class="invalid-feedback">Username tidak boleh kosong.</div>
                    </div>

                    <!-- Editable Password -->
                    <div class="form-floating mb-4">
                        <input type="password" name="password" class="form-control" id="edit_password" placeholder="Ubah Password (Kosongkan jika tetap)">
                        <label for="edit_password">Kata Sandi Baru (Kosongkan jika tetap)</label>
                        <div class="form-text text-muted small mt-1">Isi kolom ini hanya jika Anda ingin mengubah sandi masuk akun Anda.</div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2 justify-content-end">
                        <button type="submit" class="btn btn-glass px-4 py-2">
                            <i class="bi bi-check2-circle me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // JS validation
        const form = document.querySelector('.needs-validation-profile');
        if (form) {
            form.addEventListener('submit', function(e) {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        }
    });
</script>
