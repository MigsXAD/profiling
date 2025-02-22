<?php
session_start(); // Ensure session is started
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'user')) {
    header("Location: login.php");
    exit();
}

// Database connection variables
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "profiling";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function calculateAge($dob) {
    $today = new DateTime();
    $birthdate = new DateTime($dob);
    return $birthdate->diff($today)->y;
}

// Fetch one profile for display
$query = "SELECT * FROM student_profiles LIMIT 1"; // Limit to one profile
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .header {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            background-color: #2196F3;
            color: white;
        }
        .logo {
            height: 80px;
        }
        .logout-btn {
            background-color: #1976D2;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: auto;
        }
        .logout-btn i {
            color: white;
        }
        .nav-buttons {
            display: flex;
            gap: 10px;
            margin-left: 20px;
        }
        .nav-btn {
            background-color: transparent;
            border: 2px solid white;
            border-radius: 20px;
            color: white;
            padding: 8px 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .nav-btn:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        .view-profile-btn {
            background-color: #2196F3; /* Blue background for the View Profile button */
            color: white; /* White text color */
        }
        .view-profile-btn:hover {
            background-color: #1976D2; /* Darker blue on hover */
        }
        .container {
            padding: 20px;
            text-align: center;
        }
        .profile-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .admin-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .action-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.2s;
        }
        .action-card:hover {
            transform: translateY(-5px);
        }
        .action-card h3 {
            margin-top: 0;
        }
        .action-card a {
            text-decoration: none;
            color: inherit;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="images/icp_badge-removebg-preview.png" alt="Logo" class="logo">
        <div class="nav-buttons">
            <button class="nav-btn" onclick="window.location.href='home_admin.php'">Home</button>
            <button class="nav-btn" onclick="window.location.href='dashboard.php'">Dashboard</button>
            <button class="nav-btn" onclick="window.location.href='settings.php'">Settings</button>
            <button class="nav-btn view-profile-btn" onclick="window.location.href='view_profile.php'">View Profile</button>
            <?php if ($_SESSION['role'] == 'admin'): ?>
                <button class="nav-btn" onclick="window.location.href='create_profile.php'">Create New Profile</button>
            <?php endif; ?>
        </div>
        <button class="logout-btn" onclick="logout()"><i class="fas fa-sign-out-alt"></i></button>
    </div>

    <div class="container">
        <div style="display: flex;">
            <div style="flex: 1; margin-right: 20px;">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <div class="profile-card">
                            <h2>Profile</h2>
                            <p><strong>Name:</strong> <?php echo htmlspecialchars($row['name']); ?></p>
                            <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($row['date_of_birth']); ?></p>
                            <p><strong>Gender:</strong> <?php echo htmlspecialchars($row['gender'] ?? 'Not specified'); ?></p>
                            <?php if (isset($row['other_info'])): ?>
                                <p><strong>Other Info:</strong> <?php echo htmlspecialchars($row['other_info']); ?></p>
                            <?php endif; ?>
                            <?php if (!empty($row['date_of_birth'])): ?>
                                <p><strong>Age:</strong> <?php echo calculateAge($row['date_of_birth']); ?></p>
                            <?php endif; ?>
                            <a href="view_profile.php?id=<?php echo $row['id']; ?>">View Full Profile</a> <!-- Link to view profile -->
                        </div>
                        <?php
                    }
                } else {
                    echo '<div class="alert">No profiles found</div>';
                }
                ?>
            </div>
        </div>
    </div>

    <script>
        function logout() {
            window.location.href = "logout.php";
        }
    </script>
</body>
</html>
