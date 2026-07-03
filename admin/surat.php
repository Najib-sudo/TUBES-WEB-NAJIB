<?php
// admin/surat.php
// CRUD Surat (Ustad Role)

require_once __DIR__ . '/../models/SuratModel.php';
$suratModel = new SuratModel();
$surats = $suratModel->getAll();
?>

<div class="container-fluid py-2">
    <!-- Header -->
    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="fw-bold text-accent-dark mb-1">Daftar Surat Al-Qur'an</h2>
            <p class="text-muted mb-0">Kelola daftar nama surat dan jumlah ayat untuk setoran.</p>
        </div>
        <div>
            <button class="btn btn-glass d-flex align-items-center gap-2" id="btnTambahSurat" type="button">
                <i class="bi bi-plus-circle-fill"></i>
                <span>Tambah Surat</span>
            </button>
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
                    <input type="text" class="form-control border-start-0 table-search" data-target="table-surat" placeholder="Cari nama surat..." style="border-radius: 0 12px 12px 0; border-color: var(--glass-border);">
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table align-middle table-glass" id="table-surat">
                <thead>
                    <tr>
                        <th style="width: 10%;">No</th>
                        <th style="width: 50%;">Nama Surat</th>
                        <th style="width: 25%;">Jumlah Ayat</th>
                        <th style="width: 15%; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($surats)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                <i class="bi bi-info-circle fs-3 d-block mb-2"></i>
                                Belum ada data surat.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($surats as $index => $surat): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td class="fw-bold text-accent-dark"><?= htmlspecialchars($surat['nama_surat']) ?></td>
                                <td><span class="badge bg-light text-black border border-success-subtle px-3 py-1 font-monospace fs-6"><?= $surat['jumlah_ayat'] ?> Ayat</span></td>
                                <td align="center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <!-- Edit trigger -->
                                        <button class="btn btn-sm btn-glass-secondary border-0 p-2 btn-edit-surat" 
                                                data-id="<?= $surat['id'] ?>"
                                                data-nama="<?= htmlspecialchars($surat['nama_surat']) ?>"
                                                data-ayat="<?= $surat['jumlah_ayat'] ?>"
                                                title="Edit Surat">
                                            <i class="bi bi-pencil-square text-success"></i>
                                        </button>
                                        <!-- Delete link -->
                                        <a href="index.php?page=surat&action=delete&id=<?= $surat['id'] ?>" 
                                           class="btn btn-sm btn-glass-secondary border-0 p-2" 
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus surat <?= htmlspecialchars($surat['nama_surat']) ?>? Semua data setoran terkait surat ini juga akan terhapus.')"
                                           title="Hapus Surat">
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
      MODAL TAMBAH SURAT
     ========================================== -->
<div class="modal fade" id="addSuratModal" tabindex="-1" aria-labelledby="addSuratModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0 shadow-lg p-3">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-accent-dark" id="addSuratModalLabel"><i class="bi bi-plus-circle-fill me-2"></i>Tambah Surat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="index.php?page=surat&action=create" method="POST" class="needs-validation-surat" novalidate>
                <div class="modal-body">
                    <!-- Nama Surat -->
                    <div class="form-floating mb-3">
                        <input type="text" name="nama_surat" class="form-control" id="add_nama_surat" placeholder="Nama Surat" required>
                        <label for="add_nama_surat">Nama Surat (Contoh: An-Naba')</label>
                        <div class="invalid-feedback">Nama surat wajib diisi.</div>
                    </div>
                    <!-- Jumlah Ayat -->
                    <div class="form-floating mb-2">
                        <input type="number" name="jumlah_ayat" class="form-control" id="add_jumlah_ayat" placeholder="Jumlah Ayat" min="1" required>
                        <label for="add_jumlah_ayat">Jumlah Ayat</label>
                        <div class="invalid-feedback">Jumlah ayat harus berupa angka positif.</div>
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
      MODAL EDIT SURAT
     ========================================== -->
<div class="modal fade" id="editSuratModal" tabindex="-1" aria-labelledby="editSuratModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0 shadow-lg p-3">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-accent-dark" id="editSuratModalLabel"><i class="bi bi-pencil-square me-2"></i>Edit Surat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSuratForm" action="index.php?page=surat&action=update" method="POST" class="needs-validation-surat-edit" novalidate>
                <div class="modal-body">
                    <!-- Nama Surat -->
                    <div class="form-floating mb-3">
                        <input type="text" name="nama_surat" class="form-control" id="edit_nama_surat" placeholder="Nama Surat" required>
                        <label for="edit_nama_surat">Nama Surat</label>
                        <div class="invalid-feedback">Nama surat wajib diisi.</div>
                    </div>
                    <!-- Jumlah Ayat -->
                    <div class="form-floating mb-2">
                        <input type="number" name="jumlah_ayat" class="form-control" id="edit_jumlah_ayat" placeholder="Jumlah Ayat" min="1" required>
                        <label for="edit_jumlah_ayat">Jumlah Ayat</label>
                        <div class="invalid-feedback">Jumlah ayat harus berupa angka positif.</div>
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
    // Gunakan window.load agar Bootstrap JS dipastikan sudah dimuat
    window.addEventListener('load', function() {
        // Pindahkan modal ke body agar bebas dari stacking context
        ['addSuratModal', 'editSuratModal'].forEach(function(id) {
            var el = document.getElementById(id);
            if (el && el.parentElement !== document.body) {
                document.body.appendChild(el);
            }
        });

        // Inisialisasi modal Bootstrap
        var addModalEl  = document.getElementById('addSuratModal');
        var editModalEl = document.getElementById('editSuratModal');
        var addModal  = addModalEl  ? new bootstrap.Modal(addModalEl,  { keyboard: true, backdrop: true }) : null;
        var editModal = editModalEl ? new bootstrap.Modal(editModalEl, { keyboard: true, backdrop: true }) : null;

        // Tombol Tambah Surat
        var addBtn = document.getElementById('btnTambahSurat');
        if (addBtn && addModal) {
            addBtn.addEventListener('click', function() {
                addModal.show();
            });
        }

        // Tombol Edit Surat
        document.querySelectorAll('.btn-edit-surat').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id   = this.getAttribute('data-id');
                var nama = this.getAttribute('data-nama');
                var ayat = this.getAttribute('data-ayat');

                var form = document.getElementById('editSuratForm');
                if (form) form.action = 'index.php?page=surat&action=update&id=' + id;

                var nameField = document.getElementById('edit_nama_surat');
                var ayatField = document.getElementById('edit_jumlah_ayat');
                if (nameField) nameField.value = nama;
                if (ayatField) ayatField.value = ayat;

                if (editModal) editModal.show();
            });
        });

        // Tombol Close di dalam modal
        document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(function(closeBtn) {
            closeBtn.addEventListener('click', function() {
                if (addModal) addModal.hide();
                if (editModal) editModal.hide();
            });
        });

        // Validasi form
        function validateForms(selector) {
            document.querySelectorAll(selector).forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    if (!form.checkValidity()) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }

        validateForms('.needs-validation-surat');
        validateForms('.needs-validation-surat-edit');
    });
</script>
