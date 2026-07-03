<?php
// admin/setoran.php
// CRUD Setoran (Ustad Role)

require_once __DIR__ . '/../models/SetoranModel.php';
require_once __DIR__ . '/../models/MahasiswaModel.php';
require_once __DIR__ . '/../models/SuratModel.php';

$setoranModel = new SetoranModel();
$mhsModel = new MahasiswaModel();
$suratModel = new SuratModel();

$setorans = $setoranModel->getAll();
$mahasiswas = $mhsModel->getAll();
$surats = $suratModel->getAll();

// Generate Surah verses mapping JSON for dynamic client-side limits
$suratMapping = [];
foreach ($surats as $s) {
    $suratMapping[$s['id']] = [
        'nama' => $s['nama_surat'],
        'ayat' => $s['jumlah_ayat']
    ];
}
?>

<div class="container-fluid py-2">
    <!-- Header -->
    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="fw-bold text-accent-dark mb-1">Catatan Setoran Hafalan</h2>
            <p class="text-muted mb-0">Kelola riwayat setoran hafalan Al-Qur'an santri secara real-time.</p>
        </div>
        <div>
            <button class="btn btn-glass d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addSetoranModal">
                <i class="bi bi-bookmark-plus-fill"></i>
                <span>Catat Setoran Baru</span>
            </button>
        </div>
    </div>

    <!-- Table Container -->
    <div class="glass-card p-4">
        <!-- Search and Filters -->
        <div class="row mb-3 align-items-center">
            <div class="col-12 col-md-4 ms-auto">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="border-radius: 12px 0 0 12px; border-color: var(--glass-border); color: var(--text-muted);">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control border-start-0 table-search" data-target="table-setoran" placeholder="Cari nama mahasiswa, surat, status..." style="border-radius: 0 12px 12px 0; border-color: var(--glass-border);">
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table align-middle table-glass" id="table-setoran">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 18%;">Mahasiswa</th>
                        <th style="width: 15%;">Surat</th>
                        <th style="width: 13%;">Rentang Ayat</th>
                        <th style="width: 10%;">Tanggal</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 18%;">Catatan Ustad</th>
                        <th style="width: 8%;">Ustad</th>
                        <th style="width: 8%; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($setorans)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="bi bi-info-circle fs-3 d-block mb-2"></i>
                                Belum ada catatan setoran hafalan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($setorans as $index => $s): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td>
                                    <div class="fw-bold text-accent-dark"><?= htmlspecialchars($s['mahasiswa_nama']) ?></div>
                                    <small class="text-muted font-monospace small"><?= htmlspecialchars($s['nim']) ?></small>
                                </td>
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
                                </td>
                                <td>
                                    <?php if (!empty($s['catatan'])): ?>
                                        <div style="cursor: help;" 
                                             data-bs-toggle="tooltip" 
                                             data-bs-placement="top" 
                                             title="<?= htmlspecialchars($s['catatan']) ?>">
                                            <span class="d-flex align-items-start gap-1">
                                                <i class="bi bi-chat-quote-fill text-success mt-1" style="font-size: 0.75rem; flex-shrink:0;"></i>
                                                <span class="text-muted small" style="max-width:160px; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; line-height:1.4;"><?= htmlspecialchars($s['catatan']) ?></span>
                                            </span>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small fst-italic">&mdash;</span>
                                    <?php endif; ?>
                                </td>
                                <td class="small text-muted"><?= htmlspecialchars($s['ustad_nama'] ? explode(' ', $s['ustad_nama'])[0] : 'N/A') ?></td>
                                <td align="center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <!-- Edit trigger -->
                                        <button class="btn btn-sm btn-glass-secondary border-0 p-2" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editSetoranModal"
                                                data-id="<?= $s['id'] ?>"
                                                data-mhs="<?= $s['id_mahasiswa'] ?>"
                                                data-surat="<?= $s['id_surat'] ?>"
                                                data-awal="<?= $s['ayat_awal'] ?>"
                                                data-akhir="<?= $s['ayat_akhir'] ?>"
                                                data-tanggal="<?= $s['tanggal'] ?>"
                                                data-status="<?= $s['status'] ?>"
                                                data-catatan="<?= htmlspecialchars($s['catatan']) ?>"
                                                title="Edit Catatan Setoran">
                                            <i class="bi bi-pencil-square text-success"></i>
                                        </button>
                                        <!-- Delete link -->
                                        <a href="index.php?page=setoran&action=delete&id=<?= $s['id'] ?>" 
                                           class="btn btn-sm btn-glass-secondary border-0 p-2" 
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus data setoran ini?')"
                                           title="Hapus Catatan Setoran">
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

<!-- DATASTORE JSON FOR JS VALIDATION -->
<script id="surat-datastore" type="application/json">
    <?= json_encode($suratMapping) ?>
</script>

<!-- ==========================================
      MODAL TAMBAH SETORAN
     ========================================== -->
<div class="modal fade" id="addSetoranModal" tabindex="-1" aria-labelledby="addSetoranModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0 shadow-lg p-3">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-accent-dark" id="addSetoranModalLabel"><i class="bi bi-bookmark-plus-fill me-2"></i>Catat Setoran Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="index.php?page=setoran&action=create" method="POST" class="needs-validation-setoran" novalidate>
                <div class="modal-body">
                    <!-- Mahasiswa -->
                    <div class="form-floating mb-3">
                        <select name="id_mahasiswa" class="form-select bg-white bg-opacity-70" id="add_id_mahasiswa" required>
                            <option value="">Pilih Mahasiswa...</option>
                            <?php foreach ($mahasiswas as $m): ?>
                                <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nim']) ?> - <?= htmlspecialchars($m['nama']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label for="add_id_mahasiswa">Mahasiswa / Santri</label>
                        <div class="invalid-feedback">Silakan pilih mahasiswa.</div>
                    </div>
                    <!-- Surat -->
                    <div class="form-floating mb-3">
                        <select name="id_surat" class="form-select bg-white bg-opacity-70" id="add_id_surat" required>
                            <option value="">Pilih Surat...</option>
                            <?php foreach ($surats as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['nama_surat']) ?> (<?= $s['jumlah_ayat'] ?> Ayat)</option>
                            <?php endforeach; ?>
                        </select>
                        <label for="add_id_surat">Surat Al-Qur'an</label>
                        <div class="invalid-feedback">Silakan pilih surat.</div>
                    </div>

                    <!-- Range Ayat -->
                    <div class="row g-2 mb-3">
                        <div class="col">
                            <div class="form-floating">
                                <input type="number" name="ayat_awal" class="form-control" id="add_ayat_awal" placeholder="Ayat Awal" min="1" required>
                                <label for="add_ayat_awal">Ayat Awal</label>
                                <div class="invalid-feedback">Ayat awal tidak valid.</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating">
                                <input type="number" name="ayat_akhir" class="form-control" id="add_ayat_akhir" placeholder="Ayat Akhir" min="1" required>
                                <label for="add_ayat_akhir">Ayat Akhir</label>
                                <div class="invalid-feedback">Ayat akhir tidak valid.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Tanggal & Status -->
                    <div class="row g-2 mb-3">
                        <div class="col">
                            <div class="form-floating">
                                <input type="date" name="tanggal" class="form-control" id="add_tanggal" value="<?= date('Y-m-d') ?>" required>
                                <label for="add_tanggal">Tanggal</label>
                                <div class="invalid-feedback">Silakan pilih tanggal.</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating">
                                <select name="status" class="form-select bg-white bg-opacity-70" id="add_status" required>
                                    <option value="Belum Dinilai">Belum Dinilai</option>
                                    <option value="Lulus">Lulus</option>
                                    <option value="Mengulang">Mengulang</option>
                                </select>
                                <label for="add_status">Status Penilaian</label>
                            </div>
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div class="form-floating mb-2">
                        <textarea name="catatan" class="form-control" id="add_catatan" placeholder="Catatan Tambahan (Tajwid, makhraj, kelancaran...)" style="height: 100px;"></textarea>
                        <label for="add_catatan">Catatan / Keterangan</label>
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
      MODAL EDIT SETORAN
     ========================================== -->
<div class="modal fade" id="editSetoranModal" tabindex="-1" aria-labelledby="editSetoranModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0 shadow-lg p-3">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-accent-dark" id="editSetoranModalLabel"><i class="bi bi-pencil-square me-2"></i>Edit Catatan Setoran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSetoranForm" action="index.php?page=setoran&action=update" method="POST" class="needs-validation-setoran-edit" novalidate>
                <div class="modal-body">
                    <!-- Mahasiswa -->
                    <div class="form-floating mb-3">
                        <select name="id_mahasiswa" class="form-select bg-white bg-opacity-70" id="edit_id_mahasiswa" required>
                            <option value="">Pilih Mahasiswa...</option>
                            <?php foreach ($mahasiswas as $m): ?>
                                <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nim']) ?> - <?= htmlspecialchars($m['nama']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label for="edit_id_mahasiswa">Mahasiswa / Santri</label>
                        <div class="invalid-feedback">Silakan pilih mahasiswa.</div>
                    </div>
                    <!-- Surat -->
                    <div class="form-floating mb-3">
                        <select name="id_surat" class="form-select bg-white bg-opacity-70" id="edit_id_surat" required>
                            <option value="">Pilih Surat...</option>
                            <?php foreach ($surats as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['nama_surat']) ?> (<?= $s['jumlah_ayat'] ?> Ayat)</option>
                            <?php endforeach; ?>
                        </select>
                        <label for="edit_id_surat">Surat Al-Qur'an</label>
                        <div class="invalid-feedback">Silakan pilih surat.</div>
                    </div>

                    <!-- Range Ayat -->
                    <div class="row g-2 mb-3">
                        <div class="col">
                            <div class="form-floating">
                                <input type="number" name="ayat_awal" class="form-control" id="edit_ayat_awal" placeholder="Ayat Awal" min="1" required>
                                <label for="edit_ayat_awal">Ayat Awal</label>
                                <div class="invalid-feedback">Ayat awal tidak valid.</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating">
                                <input type="number" name="ayat_akhir" class="form-control" id="edit_ayat_akhir" placeholder="Ayat Akhir" min="1" required>
                                <label for="edit_ayat_akhir">Ayat Akhir</label>
                                <div class="invalid-feedback">Ayat akhir tidak valid.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Tanggal & Status -->
                    <div class="row g-2 mb-3">
                        <div class="col">
                            <div class="form-floating">
                                <input type="date" name="tanggal" class="form-control" id="edit_tanggal" required>
                                <label for="edit_tanggal">Tanggal</label>
                                <div class="invalid-feedback">Silakan pilih tanggal.</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating">
                                <select name="status" class="form-select bg-white bg-opacity-70" id="edit_status" required>
                                    <option value="Belum Dinilai">Belum Dinilai</option>
                                    <option value="Lulus">Lulus</option>
                                    <option value="Mengulang">Mengulang</option>
                                </select>
                                <label for="edit_status">Status Penilaian</label>
                            </div>
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div class="form-floating mb-2">
                        <textarea name="catatan" class="form-control" id="edit_catatan" placeholder="Catatan Tambahan (Tajwid, makhraj, kelancaran...)" style="height: 100px;"></textarea>
                        <label for="edit_catatan">Catatan / Keterangan</label>
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
        // Load Quran surah verses count datastore
        const datastore = JSON.parse(document.getElementById('surat-datastore').textContent);

        // Helper to configure verse input limits based on Selected Surah
        function setupSurahLimitListener(surahSelectId, awalInputId, akhirInputId) {
            const selectEl = document.getElementById(surahSelectId);
            const awalEl = document.getElementById(awalInputId);
            const akhirEl = document.getElementById(akhirInputId);

            if (selectEl && awalEl && akhirEl) {
                selectEl.addEventListener('change', function() {
                    const surahId = selectEl.value;
                    if (surahId && datastore[surahId]) {
                        const maxAyat = datastore[surahId].ayat;
                        awalEl.max = maxAyat;
                        akhirEl.max = maxAyat;
                        awalEl.placeholder = "Max: " + maxAyat;
                        akhirEl.placeholder = "Max: " + maxAyat;
                    } else {
                        awalEl.removeAttribute('max');
                        akhirEl.removeAttribute('max');
                    }
                });
            }
        }

        // Initialize listeners
        setupSurahLimitListener('add_id_surat', 'add_ayat_awal', 'add_ayat_akhir');
        setupSurahLimitListener('edit_id_surat', 'edit_ayat_awal', 'edit_ayat_akhir');

        // Edit setoran modal population
        const editModal = document.getElementById('editSetoranModal');
        if (editModal) {
            editModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const mhsId = button.getAttribute('data-mhs');
                const suratId = button.getAttribute('data-surat');
                const awal = button.getAttribute('data-awal');
                const akhir = button.getAttribute('data-akhir');
                const tanggal = button.getAttribute('data-tanggal');
                const status = button.getAttribute('data-status');
                const catatan = button.getAttribute('data-catatan');

                const form = document.getElementById('editSetoranForm');
                form.action = 'index.php?page=setoran&action=update&id=' + id;

                document.getElementById('edit_id_mahasiswa').value = mhsId;
                document.getElementById('edit_id_surat').value = suratId;
                
                // Trigger change event to load limits
                const selectEl = document.getElementById('edit_id_surat');
                const eventChange = new Event('change');
                selectEl.dispatchEvent(eventChange);

                document.getElementById('edit_ayat_awal').value = awal;
                document.getElementById('edit_ayat_akhir').value = akhir;
                document.getElementById('edit_tanggal').value = tanggal;
                document.getElementById('edit_status').value = status;
                document.getElementById('edit_catatan').value = catatan;
            });
        }

        // JS validation
        const validateForms = (selector) => {
            const forms = document.querySelectorAll(selector);
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const startAyatInput = form.querySelector('[name="ayat_awal"]');
                    const endAyatInput = form.querySelector('[name="ayat_akhir"]');
                    
                    if (startAyatInput && endAyatInput) {
                        const start = parseInt(startAyatInput.value);
                        const end = parseInt(endAyatInput.value);
                        
                        if (end < start) {
                            endAyatInput.setCustomValidity('Ayat akhir tidak boleh lebih kecil dari ayat awal.');
                        } else {
                            endAyatInput.setCustomValidity('');
                        }
                    }

                    if (!form.checkValidity()) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        };

        validateForms('.needs-validation-setoran');
        validateForms('.needs-validation-setoran-edit');
    });
</script>
