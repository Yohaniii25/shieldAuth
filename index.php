<?php
session_start();
require_once __DIR__ . '/config/connection.php';

$conn = Database::getInstance()->getConnection();

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $ip_address = $_SERVER['REMOTE_ADDR'];

    // Prepare statement
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            // Log successful login
            $log = $conn->prepare("INSERT INTO login_attempts (username, ip_address, status) VALUES (?, ?, 'success')");
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $log->bind_param("ss", $username, $ip_address);
            $log->execute();

            $error = "Invalid password!";
            $error = "User not found!";

            // Store session
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect by role
            if ($user['role'] === 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: user.php");
            }
            exit();
        } else {

            // Failed login logging
            $log = $conn->prepare("INSERT INTO login_attempts (username, ip_address, status) VALUES (?, ?, 'failed')");
            $log->bind_param("ss", $username, $ip_address);
            $log->execute();

            $error = "Invalid password!";
        }
    } else {

        // Failed login logging (username not found)
        $log = $conn->prepare("INSERT INTO login_attempts (username, ip_address, status) VALUES (?, ?, 'failed')");
        $log->bind_param("ss", $username, $ip_address);
        $log->execute();

        $error = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen font-sans">

    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Login</h2>

        <!-- Error message -->
        <?php if (!empty($error)) echo "<p class='text-red-500 text-center mb-4'>$error</p>"; ?>

        <form action="index.php" method="POST" class="space-y-4">
            <div>
                <label for="username" class="block text-gray-700 font-medium mb-1">Username</label>
                <input type="text" id="username" name="username" required
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label for="password" class="block text-gray-700 font-medium mb-1">Password</label>
                <input type="password" id="password" name="password" required
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                Login
            </button>
        </form>

        <p class="text-center mt-4 text-gray-600">
            Don't have an account?
            <a href="register.php" class="text-blue-600 hover:underline">Register</a>
        </p>
    </div>

</body>
</html>