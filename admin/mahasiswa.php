<?php
// admin/mahasiswa.php
// CRUD Mahasiswa (Ustad Role)

require_once __DIR__ . '/../models/MahasiswaModel.php';
$mhsModel = new MahasiswaModel();
$mahasiswas = $mhsModel->getAll();
?>

<div class="container-fluid py-2">
    <!-- Header -->
    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="fw-bold text-accent-dark mb-1">Manajemen Mahasiswa</h2>
            <p class="text-muted mb-0">Kelola informasi akademik dan akun mahasiswa/santri.</p>
        </div>
        <div>
            <button class="btn btn-glass d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addMhsModal">
                <i class="bi bi-person-plus-fill"></i>
                <span>Tambah Mahasiswa</span>
            </button>
        </div>
    </div>

    <!-- Table Card Container -->
    <div class="glass-card p-4">
        <!-- Search -->
        <div class="row mb-3">
            <div class="col-12 col-md-4 ms-auto">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="border-radius: 12px 0 0 12px; border-color: var(--glass-border); color: var(--text-muted);">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control border-start-0 table-search" data-target="table-mahasiswa" placeholder="Cari NIM, Nama, atau Prodi..." style="border-radius: 0 12px 12px 0; border-color: var(--glass-border);">
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table align-middle table-glass" id="table-mahasiswa">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 15%;">NIM</th>
                        <th style="width: 25%;">Nama Lengkap</th>
                        <th style="width: 20%;">Program Studi</th>
                        <th style="width: 15%;">Angkatan</th>
                        <th style="width: 10%;">Username</th>
                        <th style="width: 10%; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($mahasiswas)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-info-circle fs-3 d-block mb-2"></i>
                                Belum ada data mahasiswa.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($mahasiswas as $index => $mhs): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td class="font-monospace fw-bold text-accent-dark"><?= htmlspecialchars($mhs['nim']) ?></td>
                                <td>
                                    <div class="fw-bold"><?= htmlspecialchars($mhs['nama']) ?></div>
                                </td>
                                <td><?= htmlspecialchars($mhs['prodi']) ?></td>
                                <td><?= htmlspecialchars($mhs['angkatan']) ?></td>
                                <td class="font-monospace text-muted">@<?= htmlspecialchars($mhs['username']) ?></td>
                                <td align="center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <!-- Edit Trigger -->
                                        <button class="btn btn-sm btn-glass-secondary border-0 p-2" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editMhsModal"
                                                data-id="<?= $mhs['id'] ?>"
                                                data-nim="<?= htmlspecialchars($mhs['nim']) ?>"
                                                data-nama="<?= htmlspecialchars($mhs['nama']) ?>"
                                                data-prodi="<?= htmlspecialchars($mhs['prodi']) ?>"
                                                data-angkatan="<?= $mhs['angkatan'] ?>"
                                                data-username="<?= htmlspecialchars($mhs['username']) ?>"
                                                title="Edit Mahasiswa">
                                            <i class="bi bi-pencil-square text-success"></i>
                                        </button>
                                        <!-- Delete Trigger -->
                                        <a href="index.php?page=mahasiswa&action=delete&id=<?= $mhs['id'] ?>" 
                                           class="btn btn-sm btn-glass-secondary border-0 p-2" 
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus data <?= htmlspecialchars($mhs['nama']) ?>? User login terkait juga akan terhapus.')"
                                           title="Hapus Mahasiswa">
                                            <i class="bi bi-trash-fill text-danger"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ==========================================
      MODAL TAMBAH MAHASISWA
     ========================================== -->
<div class="modal fade" id="addMhsModal" tabindex="-1" aria-labelledby="addMhsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0 shadow-lg p-3">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-accent-dark" id="addMhsModalLabel"><i class="bi bi-person-plus-fill me-2"></i>Tambah Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="index.php?page=mahasiswa&action=create" method="POST" class="needs-validation-mhs" novalidate>
                <div class="modal-body">
                    <!-- NIM -->
                    <div class="form-floating mb-3">
                        <input type="text" name="nim" class="form-control" id="add_nim" placeholder="NIM" required>
                        <label for="add_nim">Nomor Induk Mahasiswa (NIM)</label>
                        <div class="invalid-feedback">NIM wajib diisi.</div>
                    </div>
                    <!-- Nama -->
                    <div class="form-floating mb-3">
                        <input type="text" name="nama" class="form-control" id="add_nama" placeholder="Nama Lengkap" required>
                        <label for="add_nama">Nama Lengkap</label>
                        <div class="invalid-feedback">Nama wajib diisi.</div>
                    </div>
                    <!-- Prodi -->
                    <div class="form-floating mb-3">
                        <input type="text" name="prodi" class="form-control" id="add_prodi" placeholder="Program Studi" required>
                        <label for="add_prodi">Program Studi</label>
                        <div class="invalid-feedback">Program Studi wajib diisi.</div>
                    </div>
                    <!-- Angkatan -->
                    <div class="form-floating mb-3">
                        <input type="number" name="angkatan" class="form-control" id="add_angkatan" placeholder="Angkatan" value="<?= date('Y') ?>" required>
                        <label for="add_angkatan">Angkatan (Tahun)</label>
                        <div class="invalid-feedback">Angkatan wajib diisi.</div>
                    </div>
                    <hr class="my-3 opacity-25" style="color: var(--text);">
                    <p class="text-muted small mb-3">Konfigurasi akun login mahasiswa:</p>
                    <!-- Username -->
                    <div class="form-floating mb-3">
                        <input type="text" name="username" class="form-control" id="add_username" placeholder="Username" required>
                        <label for="add_username">Username</label>
                        <div class="invalid-feedback">Username wajib diisi.</div>
                    </div>
                    <!-- Password -->
                    <div class="form-floating mb-2">
                        <input type="password" name="password" class="form-control" id="add_password" placeholder="Password" required>
                        <label for="add_password">Password</label>
                        <div class="invalid-feedback">Password wajib diisi.</div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 d-flex gap-2 justify-content-end">
                    <button type="button" class="btn btn-glass-secondary py-2 border-0" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-glass py-2 px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==========================================
      MODAL EDIT MAHASISWA
     ========================================== -->
<div class="modal fade" id="editMhsModal" tabindex="-1" aria-labelledby="editMhsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0 shadow-lg p-3">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-accent-dark" id="editMhsModalLabel"><i class="bi bi-pencil-square me-2"></i>Edit Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editMhsForm" action="index.php?page=mahasiswa&action=update" method="POST" class="needs-validation-mhs-edit" novalidate>
                <div class="modal-body">
                    <!-- NIM -->
                    <div class="form-floating mb-3">
                        <input type="text" name="nim" class="form-control" id="edit_nim" placeholder="NIM" required>
                        <label for="edit_nim">Nomor Induk Mahasiswa (NIM)</label>
                        <div class="invalid-feedback">NIM wajib diisi.</div>
                    </div>
                    <!-- Nama -->
                    <div class="form-floating mb-3">
                        <input type="text" name="nama" class="form-control" id="edit_nama" placeholder="Nama Lengkap" required>
                        <label for="edit_nama">Nama Lengkap</label>
                        <div class="invalid-feedback">Nama wajib diisi.</div>
                    </div>
                    <!-- Prodi -->
                    <div class="form-floating mb-3">
                        <input type="text" name="prodi" class="form-control" id="edit_prodi" placeholder="Program Studi" required>
                        <label for="edit_prodi">Program Studi</label>
                        <div class="invalid-feedback">Program Studi wajib diisi.</div>
                    </div>
                    <!-- Angkatan -->
                    <div class="form-floating mb-3">
                        <input type="number" name="angkatan" class="form-control" id="edit_angkatan" placeholder="Angkatan" required>
                        <label for="edit_angkatan">Angkatan (Tahun)</label>
                        <div class="invalid-feedback">Angkatan wajib diisi.</div>
                    </div>
                    <hr class="my-3 opacity-25" style="color: var(--text);">
                    <p class="text-muted small mb-3">Akun login mahasiswa (ubah password jika ingin menggantinya):</p>
                    <!-- Username -->
                    <div class="form-floating mb-3">
                        <input type="text" name="username" class="form-control" id="edit_username" placeholder="Username" required>
                        <label for="edit_username">Username</label>
                        <div class="invalid-feedback">Username wajib diisi.</div>
                    </div>
                    <!-- Password -->
                    <div class="form-floating mb-2">
                        <input type="password" name="password" class="form-control" id="edit_password" placeholder="Ubah Password (Kosongkan jika tetap)">
                        <label for="edit_password">Ubah Password (Kosongkan jika tetap)</label>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 d-flex gap-2 justify-content-end">
                    <button type="button" class="btn btn-glass-secondary py-2 border-0" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-glass py-2 px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Populate edit modal when triggered
        const editMhsModal = document.getElementById('editMhsModal');
        if (editMhsModal) {
            editMhsModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                
                // Extract info from data-* attributes
                const id = button.getAttribute('data-id');
                const nim = button.getAttribute('data-nim');
                const nama = button.getAttribute('data-nama');
                const prodi = button.getAttribute('data-prodi');
                const angkatan = button.getAttribute('data-angkatan');
                const username = button.getAttribute('data-username');
                
                // Update form action dynamically
                const form = document.getElementById('editMhsForm');
                form.action = 'index.php?page=mahasiswa&action=update&id=' + id;
                
                // Fill details in input elements
                document.getElementById('edit_nim').value = nim;
                document.getElementById('edit_nama').value = nama;
                document.getElementById('edit_prodi').value = prodi;
                document.getElementById('edit_angkatan').value = angkatan;
                document.getElementById('edit_username').value = username;
                document.getElementById('edit_password').value = ''; // clear password field
            });
        }

        // JS validation for modals
        const validateForms = (selector) => {
            const forms = document.querySelectorAll(selector);
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!form.checkValidity()) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        };

        validateForms('.needs-validation-mhs');
        validateForms('.needs-validation-mhs-edit');
    });
</script>
