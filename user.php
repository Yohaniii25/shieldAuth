<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

<div class="bg-green-600 text-white p-4 flex justify-between items-center">
    <h1 class="text-xl font-bold">Welcome, <?= $_SESSION['username']; ?></h1>
    <a href="logout.php" class="bg-red-500 hover:bg-red-700 px-3 py-1 rounded">Logout</a>
</div>

<div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

    <div class="bg-white shadow rounded p-6">
        <h2 class="text-gray-700 font-semibold mb-2">My Profile</h2>
        <p><strong>Username:</strong> <?= $_SESSION['username']; ?></p>
        <p><strong>Role:</strong> <?= $_SESSION['role']; ?></p>
    </div>

    <div class="bg-white shadow rounded p-6">
        <h2 class="text-gray-700 font-semibold mb-2">Recent Activity</h2>
        <p>—</p>
    </div>

</div>

</body>
</html>