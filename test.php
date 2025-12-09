<?php
include 'db_connection.php';
session_start();
if (!isset($_SESSION['admin_name'])) {
    header('Location: login system\login_form.php');
    exit();
}
// Query total users
$total_users_query = "SELECT COUNT(*) AS total_users FROM user";
$total_users_result = $conn->query($total_users_query);
$total_users = $total_users_result->fetch_assoc()['total_users'];

// Query new users (registered in the last 30 days)
$new_users_query = "SELECT COUNT(*) AS new_users FROM user WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
$new_users_result = $conn->query($new_users_query);
$new_users = $new_users_result->fetch_assoc()['new_users'];

// Query most played games (based on review count)
$popular_games_query = "SELECT g.nama_game, g.foto_game, COUNT(r.id_game) as play_count,
                       CASE 
                           WHEN g.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 'Coming Soon'
                           ELSE 'Completed'
                       END as status
                       FROM game g
                       LEFT JOIN review r ON g.id_game = r.id_game
                       GROUP BY g.id_game
                       ORDER BY play_count DESC
                       LIMIT 5";
$popular_games_result = $conn->query($popular_games_query);

// Query communities
$communities_query = "SELECT k.nama_komunitas, k.link_komunitas, k.foto_komunitas,
                     CASE 
                         WHEN k.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 'not-completed'
                         ELSE 'completed'
                     END as status
                     FROM komunitas k
                     ORDER BY k.created_at DESC
                     LIMIT 5";
$communities_result = $conn->query($communities_query);

// Get monthly user stats for chart
$monthly_stats_query = "SELECT DATE_FORMAT(created_at, '%b') as month, 
                       COUNT(*) as user_count 
                       FROM user 
                       GROUP BY MONTH(created_at) 
                       ORDER BY created_at 
                       LIMIT 9";
$monthly_stats = $conn->query($monthly_stats_query);
$months = [];
$user_counts = [];
while($row = $monthly_stats->fetch_assoc()) {
    $months[] = $row['month'];
    $user_counts[] = $row['user_count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- CSS -->
    <link rel="stylesheet" href="github.css?v=1">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand">
            <i class='bx bxs-game'></i>
            <span class="text">Pixel Playground Admin</span>
        </a>
        <ul class="side-menu top">
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                <a href="test.php">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                <a href="admin_game_page.php">
                    <i class='bx bxs-joystick'></i>
                    <span class="text">Games</span>
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                <a href="admin_komunitas_page.php">
                    <i class='bx bxs-message-dots'></i>
                    <span class="text">Community</span>
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>"> 
                <a href="admin_profile.php">
                    <i class='bx bxs-user'></i>
                    <span class="text">Profile</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="logout.php" class="logout">
                    <i class='bx bxs-log-out-circle'></i>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>
    </section>
    <!-- SIDEBAR -->

    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
        <nav>
            <i class='bx bx-menu'></i>
            <a href="#" class="nav-link">Categories</a>
            <form action="#">
                <div class="form-input">
                    <input type="search" placeholder="Search...">
                    <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
                </div>
            </form>
            <input type="checkbox" id="switch-mode" hidden>
            <label for="switch-mode" class="switch-mode"></label>
        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Dashboard</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Dashboard</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#">Home</a>
                        </li>
                    </ul>
                </div>
            </div>

            <ul class="box-info">
                <li>
                    <i class='bx bxs-group'></i>
                    <span class="text">
                        <h3><?php echo number_format($total_users); ?></h3>
                        <p>Total Users</p>
                    </span>
                </li>
                <li>
                    <i class='bx bxs-user-plus'></i>
                    <span class="text">
                        <h3><?php echo number_format($new_users); ?></h3>
                        <p>New Users</p>
                    </span>
                </li>
                <li>
                    <i class='bx bxs-game'></i>
                    <span class="text">
                        <?php
                        $total_games_query = "SELECT COUNT(*) as total FROM game";
                        $total_games = $conn->query($total_games_query)->fetch_assoc()['total'];
                        ?>
                        <h3><?php echo number_format($total_games); ?></h3>
                        <p>Total Games</p>
                    </span>
                </li>
            </ul>

            <div class="chart-container" style="margin: 50px auto; max-width: 700px;">
                <canvas id="areaChart"></canvas>
            </div>

            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Frequently played Games</h3>
                        
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Total Played</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($game = $popular_games_result->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <img src="<?php echo htmlspecialchars($game['foto_game']); ?>" onerror="this.src='img/default-game.png'">
                                    <p><?php echo htmlspecialchars($game['nama_game']); ?></p>
                                </td>
                                <td><?php echo number_format($game['play_count']); ?></td>
                                <td><span class="status <?php echo strtolower($game['status']); ?>"><?php echo $game['status']; ?></span></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <div class="todo">
                    <div class="head">
                        <h3>Our Community</h3>
                        <i class='bx bx-plus'></i>
                        <i class='bx bx-filter'></i>
                    </div>
                    <ul class="todo-list">
                        <?php while($community = $communities_result->fetch_assoc()): ?>
                        <li class="<?php echo $community['status']; ?>">
                            <img src="<?php echo htmlspecialchars($community['foto_komunitas']); ?>" onerror="this.src='img/default-community.png'">
                            <p><?php echo htmlspecialchars($community['nama_komunitas']); ?></p>
                            <a href="<?php echo htmlspecialchars($community['link_komunitas']); ?>">
                                <i class='bx bx-right-arrow-alt'></i>
                            </a>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->
    
    <script>
        // Chart configuration
        const ctx = document.getElementById('areaChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [{
                    label: 'New Users',
                    data: <?php echo json_encode($user_counts); ?>,
                    borderColor: '#4caf50',
                    backgroundColor: 'rgba(76, 175, 80, 0.2)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#4caf50',
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        enabled: true,
                    },
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                        },
                    },
                    y: {
                        beginAtZero: true,
                    },
                },
            },
        });

        // Sidebar toggle
        const allSideDivider = document.querySelectorAll('#sidebar .divider');
        const allSideBar = document.querySelectorAll('#sidebar .side-menu.top li a');
        
        allSideBar.forEach(item => {
            const li = item.parentElement;
            item.addEventListener('click', function () {
                allSideBar.forEach(i => {
                    i.parentElement.classList.remove('active');
                })
                li.classList.add('active');
            })
        });

        // Toggle Sidebar
        const menuBar = document.querySelector('#content nav .bx.bx-menu');
        const sidebar = document.getElementById('sidebar');

        menuBar.addEventListener('click', function () {
            sidebar.classList.toggle('hide');
        });

        // Dark mode toggle
        const switchMode = document.getElementById('switch-mode');
        switchMode.addEventListener('change', function () {
            document.body.classList.toggle('dark');
        });
    </script>
</body>
</html>