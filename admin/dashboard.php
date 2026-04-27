<?php
/**
 * Admin Dashboard
 */

session_start();
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/UserController.php';
require_once __DIR__ . '/../app/controllers/AttendanceController.php';

use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Controllers\AttendanceController;

// Check authentication
AuthController::requireAdmin();
AuthController::checkSessionTimeout();

// Database connection
db = new Database();
$conn = $db->connect();

// Initialize controllers
$userCtrl = new UserController($conn);
$attendanceCtrl = new AttendanceController($conn);

// Get statistics
totalUsers = $userCtrl->countAll();
totalGurus = $userCtrl->countByRole('guru');
totalAdmins = $userCtrl->countByRole('admin');
totalAttendance = $attendanceCtrl->countAll();

// Get recent attendance
$recentAttendance = $attendanceCtrl->getAll(10, 0);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="/admin/dashboard.php" class="navbar-brand">📊 <?php echo APP_NAME; ?></a>
            <div class="navbar-menu">
                <a href="/admin/dashboard.php">Dashboard</a>
                <a href="/admin/users.php">Manajemen User</a>
                <a href="/admin/attendance.php">Absensi</a>
                <a href="/admin/reports.php">Laporan</a>
            </div>
            <div class="navbar-user">
                <span>👤 <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <a href="/logout.php" class="btn btn-sm btn-danger">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1>Dashboard Admin</h1>

        <!-- Statistics -->
        <div class="dashboard-grid">
            <div class="stat-card">
                <h3>Total User</h3>
                <div class="stat-value"><?php echo $totalUsers; ?></div>
            </div>
            <div class="stat-card" style="border-left-color: #27ae60;">
                <h3>Total Guru</h3>
                <div class="stat-value"><?php echo $totalGurus; ?></div>
            </div>
            <div class="stat-card" style="border-left-color: #f39c12;">
                <h3>Total Admin</h3>
                <div class="stat-value"><?php echo $totalAdmins; ?></div>
            </div>
            <div class="stat-card" style="border-left-color: #3498db;">
                <h3>Total Absensi</h3>
                <div class="stat-value"><?php echo $totalAttendance; ?></div>
            </div>
        </div>

        <!-- Recent Attendance -->
        <div class="card">
            <div class="card-header">
                <h2>Absensi Terbaru</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama Guru</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Catatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentAttendance)): ?>
                                <tr>
                                    <td colspan="5" style="text-align: center;">Belum ada data absensi</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recentAttendance as $attendance): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($attendance['name']); ?></td>
                                        <td><?php echo date('d-m-Y', strtotime($attendance['attendance_date'])); ?></td>
                                        <td>
                                            <?php
                                            $statusClass = match($attendance['status']) {
                                                'hadir' => 'badge-success',
                                                'alfa' => 'badge-danger',
                                                'sakit' => 'badge-warning',
                                                'izin' => 'badge-info',
                                                'terlambat' => 'badge-warning',
                                                'off' => 'badge-primary',
                                                default => 'badge-primary'
                                            };
                                            ?>
                                            <span class="badge <?php echo $statusClass; ?>">
                                                <?php echo ucfirst($attendance['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($attendance['notes']); ?></td>
                                        <td>
                                            <a href="/admin/attendance-edit.php?id=<?php echo $attendance['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h2>Aksi Cepat</h2>
            </div>
            <div class="card-body">
                <a href="/admin/users-add.php" class="btn btn-primary">➕ Tambah User</a>
                <a href="/admin/attendance-add.php" class="btn btn-success">➕ Tambah Absensi</a>
                <a href="/admin/reports.php" class="btn btn-info">📄 Lihat Laporan</a>
                <a href="/admin/attendance.php" class="btn btn-secondary">📋 Kelola Absensi</a>
            </div>
        </div>
    </div>

    <script src="/public/js/script.js"></script>
</body>
</html>