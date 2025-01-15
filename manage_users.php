<?php
require 'functions.php';
check_login();

// Database connection settings
$host = 'localhost';
$db = 'isp';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch all users
try {
    $users = $pdo->query("SELECT id, username, status FROM users")->fetchAll();
} catch (\PDOException $e) {
    echo "Failed to fetch users: " . $e->getMessage();
}

// Handle status update or deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['user_id']) && isset($_POST['status'])) {
        // Update user status
        $user_id = $_POST['user_id'];
        $status = $_POST['status'] == 1 ? 1 : 0;

        try {
            $stmt = $pdo->prepare("UPDATE users SET status = ? WHERE id = ?");
            $stmt->execute([$status, $user_id]);
            $_SESSION['message'] = "User status updated successfully.";
            header("Location: manage_users.php");
            exit();
        } catch (\PDOException $e) {
            echo "Failed to update user status: " . $e->getMessage();
        }
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'fetch_users') {
        try {
            // Fetch all users
            $users = $pdo->query("SELECT id, username, status FROM users")->fetchAll();
            echo json_encode(['status' => 'success', 'users' => $users]);
        } catch (\PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit();
    }
    if ($_POST['action'] === 'delete_user') {
        $delete_user_id = $_POST['delete_user_id'];
    
        try {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$delete_user_id]);
            echo json_encode(['status' => 'success', 'message' => 'User deleted successfully']);
        } catch (\PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit();
    }
    
    // Delete user
    if (isset($_POST['delete_user_id'])) {
        $delete_user_id = $_POST['delete_user_id'];

        try {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$delete_user_id]);
            $_SESSION['message'] = "User deleted successfully.";
            header("Location: manage_users.php");
            exit();
        } catch (\PDOException $e) {
            echo "Failed to delete user: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link rel="stylesheet" href="css/manage.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
    <style>
        /* Custom scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Status text colors */
        .status-active {
            color: green;
            font-weight: bold;
        }
        .status-inactive {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
<section class="sidebar">
    <a href="dashboard.php" class="logo">
      <img src="asset/logo.png" alt="Satria Net Logo" class="logo-img">
      <span class="text">SATRIA NET</span>
    </a>
    
    <ul class="side-menu top">
      <li><a href="./dashboard/index.php" class="nav-link"><i class="fas fa-border-all"></i><span class="text">Dashboard</span></a></li>
      <li><a href="dpelanggan.php" class="nav-link"><i class="fas fa-people-group"></i><span class="text">Data Pelanggan</span></a></li>
      <li><a href="datateknis.php" class="nav-link"><i class="fas fa-cog"></i><span class="sidebar-text">Data Teknis</span></a></li>
      <li><a href="pembayaran.php" class="nav-link"><i class="fas fa-money-bill"></i><span class="text">Pembayaran</span></a></li>
      <li><a href="inventori.php" class="nav-link"><i class="fas fa-box"></i><span class="text">Inventori</span></a></li>
      <li><a href="ticketing.php" class="nav-link"><i class="fas fa-ticket-alt"></i><span class="text">Ticketing</span></a></li>
      <li><a href="prt.php" class="nav-link"><i class="fas fa-tools"></i><span class="text">PRT</span></a></li>
    </ul>
    
    <ul class="side-menu">
      <li><a href="#" class="logout" id="logout-btn"><i class="fas fa-right-from-bracket"></i><span class="text">Logout</span></a></li>
    </ul>
  </section>         
  <section class="content">
    <nav>
      <i class="fas fa-bars menu-btn"></i>
      <a href="#" class="nav-link">Categories</a>
      <form action="#" method="GET">
        <div class="form-input">
          <input type="search" placeholder="search..." name="query">
          <button class="search-btn"><i class="fas fa-search search-icon"></i></button>
        </div>
      </form>
      <a href="profile.php" class="profile"><i class="fas fa-user"></i></a>
    </nav>
    
    <main>
      <div class="head-title">
        <div class="left">
          <h1>Manage Users</h1>
          <ul class="breadcrumb">
            <li><a href="#">Manage</a></li>
            <i class="fas fa-chevron-right"></i>
            <li><a href="./dashboard/index.php" class="active">Home</a></li>
          </ul>
        </div>
      </div>
      
      <div class="table-data">
        <div class="order">
          <div class="head">
            <h3>Manage Users</h3>
          </div>

        <!-- Display feedback message with dismissible alert -->
<?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $_SESSION['message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

          <table id="user-table">
              <thead>
                  <tr>
                      <th>No</th>
                      <th>Username</th>
                      <th>Status</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody>
    <?php foreach ($users as $index => $user): ?>
        <tr>
            <td class="row-number"><?= $index + 1 ?></td>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td>
                <span class="<?= $user['status'] ? 'status-active' : 'status-inactive' ?>">
                    <?= $user['status'] ? 'Active' : 'Inactive' ?>
                </span>
            </td>
            <td>
                <!-- Activate/Deactivate Button -->
                <form action="manage_users.php" method="POST" style="display:inline;">
                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                    <input type="hidden" name="status" value="<?= $user['status'] ? 0 : 1 ?>">
                    <button type="submit" class="btn <?= $user['status'] ? 'btn-blue' : 'btn-green' ?>">
                        <?= $user['status'] ? 'Deactivate' : 'Activate' ?>
                    </button>
                </form>

                <!-- Delete Button -->
                <form action="manage_users.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                    <input type="hidden" name="delete_user_id" value="<?= $user['id'] ?>">
                    <button type="submit" class="btn btn-red"><i class="fas fa-trash"></i></button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>


          </table>
        </div>
      </div>
    </main>
  </section>

  <!-- jQuery and Bootstrap Bundle (includes Popper) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <scrip>
<script>
$(document).ready(function() {
    // Initialize DataTables
    const dataTable = $('#user-table').DataTable({
        "pageLength": 10,
        "order": [[0, 'asc']], // Sort by the first column (No)
        "scrollY": "400px",
        "scrollCollapse": true,
        "paging": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/Indonesian.json",
            "paginate": {
                "previous": "<",
                "next": ">"
            }
        }
    });

    // Fetch Users Data
    const fetchUsers = () => {
        $.post('manage_users.php', { action: 'fetch_users' }, function(response) {
            if (response.status === 'success') {
                dataTable.clear();
                response.users.forEach((user, index) => {
                    const statusClass = user.status ? 'status-active' : 'status-inactive';
                    dataTable.row.add([
                        index + 1,
                        user.username,
                        `<span class="${statusClass}">${user.status ? 'Active' : 'Inactive'}</span>`,
                        `<button class="btn toggle-status-btn ${user.status ? 'btn-blue' : 'btn-green'}" 
                                    data-id="${user.id}" 
                                    data-status="${user.status ? 0 : 1}">
                            ${user.status ? 'Deactivate' : 'Activate'}
                        </button>
                        <button class="btn btn-danger delete-user-btn" 
                                    data-id="${user.id}">
                            <i class="fas fa-trash"></i>
                        </button>`
                    ]);
                });
                dataTable.draw();
                bindActionButtons();
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        }, 'json');
    };

    // Function to Bind Action Buttons
    const bindActionButtons = () => {
        // Toggle Status Button Logic
        $('.toggle-status-btn').on('click', function() {
            const userId = $(this).data('id');
            const newStatus = $(this).data('status');
            const actionText = newStatus ? 'Activated' : 'Deactivated';

            $.post('manage_users.php', { action: 'update_status', user_id: userId, status: newStatus }, function(response) {
                if (response.status === 'success') {
                    fetchUsers(); // Refresh data
                    Swal.fire({
                        title: 'Success!',
                        text: `User has been ${actionText} successfully.`,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            }, 'json');
        });

        // Delete User Button Logic
        $('.delete-user-btn').on('click', function() {
            const userId = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('manage_users.php', { action: 'delete_user', delete_user_id: userId }, function(response) {
                        if (response.status === 'success') {
                            fetchUsers(); // Refresh data
                            Swal.fire({
                                title: 'Deleted!',
                                text: 'User has been deleted successfully.',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    }, 'json');
                }
            });
        });
    };

    // Initial Fetch and Refresh Periodically
    fetchUsers();
    setInterval(fetchUsers, 3000); // Refresh every 3 seconds

    // Logout Logic
    document.querySelector('.logout').addEventListener('click', function(event) {
        event.preventDefault();
        Swal.fire({
            title: 'You have been logged out!',
            icon: 'success',
            timer: 2000,
            timerProgressBar: true,
            showConfirmButton: false,
            willClose: () => {
                window.location.href = 'logout.php';
            }
        });
    });
});

</script>

</body>
</html>

