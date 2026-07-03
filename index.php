<?php
// index.php
// Central Router and Front Controller

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/controllers/AuthController.php';

// 1. Enforce Authentication
AuthController::checkAuth();

$currentUser = $_SESSION['user'];
$role = $currentUser['role'];
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$action = isset($_GET['action']) ? $_GET['action'] : '';

// 2. Action Dispatcher (Handles POST/GET actions before layout loading)
if ($action !== '') {
    if ($role === 'ustad') {
        if ($page === 'mahasiswa') {
            require_once __DIR__ . '/controllers/MahasiswaController.php';
            $controller = new MahasiswaController();
            if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->store($_POST);
            } elseif ($action === 'update' && isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->update(intval($_GET['id']), $_POST);
            } elseif ($action === 'delete' && isset($_GET['id'])) {
                $controller->destroy(intval($_GET['id']));
            }
        } elseif ($page === 'surat') {
            require_once __DIR__ . '/controllers/SuratController.php';
            $controller = new SuratController();
            if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->store($_POST);
            } elseif ($action === 'update' && isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->update(intval($_GET['id']), $_POST);
            } elseif ($action === 'delete' && isset($_GET['id'])) {
                $controller->destroy(intval($_GET['id']));
            }
        } elseif ($page === 'setoran') {
            require_once __DIR__ . '/controllers/SetoranController.php';
            $controller = new SetoranController();
            if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->store($_POST);
            } elseif ($action === 'update' && isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->update(intval($_GET['id']), $_POST);
            } elseif ($action === 'delete' && isset($_GET['id'])) {
                $controller->destroy(intval($_GET['id']));
            }
        }
    } elseif ($role === 'mahasiswa') {
        if ($page === 'profil' && $action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/models/User.php';
            $userModel = new User();
            
            $nama = trim($_POST['nama']);
            $username = trim($_POST['username']);
            $password = $_POST['password'];

            if (empty($nama) || empty($username)) {
                $_SESSION['toast'] = ['title' => 'Gagal', 'message' => 'Nama dan Username wajib diisi!', 'type' => 'error'];
            } else {
                // Check username uniqueness (except own)
                $existing = $userModel->findByUsername($username);
                if ($existing && $existing['id'] !== $currentUser['id']) {
                    $_SESSION['toast'] = ['title' => 'Gagal', 'message' => 'Username sudah digunakan!', 'type' => 'error'];
                } else {
                    $userModel->updateProfile($currentUser['id'], $nama, $username);
                    $_SESSION['user']['nama'] = $nama;
                    $_SESSION['user']['username'] = $username;

                    if (!empty($password)) {
                        $userModel->updatePassword($currentUser['id'], $password);
                    }
                    $_SESSION['toast'] = ['title' => 'Sukses', 'message' => 'Profil berhasil diperbarui!', 'type' => 'success'];
                }
            }
            header('Location: index.php?page=profil');
            exit();
        }
    }
}

// 3. View Router
$viewFile = '';
if ($role === 'ustad') {
    switch ($page) {
        case 'dashboard':
            $viewFile = 'admin/dashboard.php';
            break;
        case 'mahasiswa':
            $viewFile = 'admin/mahasiswa.php';
            break;
        case 'surat':
            $viewFile = 'admin/surat.php';
            break;
        case 'setoran':
            $viewFile = 'admin/setoran.php';
            break;
        default:
            $viewFile = 'admin/dashboard.php';
            break;
    }
} elseif ($role === 'mahasiswa') {
    switch ($page) {
        case 'dashboard':
            $viewFile = 'mahasiswa/dashboard.php';
            break;
        case 'riwayat':
            $viewFile = 'mahasiswa/riwayat.php';
            break;
        case 'profil':
            $viewFile = 'mahasiswa/profil.php';
            break;
        default:
            $viewFile = 'mahasiswa/dashboard.php';
            break;
    }
}

// Ensure the view file exists
if (!file_exists(__DIR__ . '/' . $viewFile)) {
    die("Halaman tidak ditemukan.");
}

// 4. Load Layouts and View
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/includes/sidebar.php';

echo '<main class="main-content" id="main-content">';
require_once __DIR__ . '/' . $viewFile;
echo '</main>';

require_once __DIR__ . '/includes/footer.php';
