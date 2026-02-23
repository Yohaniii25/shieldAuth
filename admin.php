<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

<!-- Top Bar -->
<div class="bg-blue-600 text-white p-4 flex justify-between items-center">
    <h1 class="text-xl font-bold">Admin Dashboard</h1>
    <a href="logout.php" class="bg-red-500 hover:bg-red-700 px-3 py-1 rounded">Logout</a>
</div>

<!-- Content -->
<div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">

    <!-- Example Card 1 -->
    <div class="bg-white shadow rounded p-6">
        <h2 class="text-gray-700 font-semibold mb-2">Total Users</h2>
        <p class="text-3xl font-bold text-blue-600">
            <?php
            require_once __DIR__ . '/config/connection.php';
            $conn = Database::getInstance()->getConnection();
            $result = $conn->query("SELECT COUNT(*) as total FROM users");
            $count = $result->fetch_assoc();
            echo $count['total'];
            ?>
        </p>
        <a href="user_list.php" class="text-blue-500 hover:underline">View Users</a>
    </div>

    <!-- Example Card 2 -->
    <div class="bg-white shadow rounded p-6">
        <h2 class="text-gray-700 font-semibold mb-2">Login Attempts</h2>
        <p class="text-3xl font-bold text-red-500">
            <?php
            $result = $conn->query("SELECT COUNT(*) as total FROM login_attempts");
            $count = $result->fetch_assoc();
            echo $count['total'];
            ?>
        </p>
        <a href="login_attempts.php" class="text-red-500 hover:underline">View Logs</a>
    </div>

    <!-- Example Card 3 -->
    <div class="bg-white shadow rounded p-6">
        <h2 class="text-gray-700 font-semibold mb-2">Other Info</h2>
        <p class="text-3xl font-bold text-green-500">—</p>
        <a href="#" class="text-green-500 hover:underline">View More</a>
    </div>

</div>

</body>
</html>