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

// Get the profile ID from the URL
if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
    $query = "SELECT * FROM student_profiles WHERE id = ?"; // Get specific profile
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $profile = $result->fetch_assoc();
    } else {
        echo "Profile not found.";
        exit();
    }
} else {
    echo "No profile ID provided. Please ensure the URL includes the profile ID.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            padding: 20px;
            text-align: left; /* Align text to the left */
        }
        .profile-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            max-width: 600px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="profile-card">
            <h2>Profile Details</h2>
            <table>
                <tr>
                    <th>User ID</th>
                    <td><?php echo htmlspecialchars($profile['user_id']); ?></td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td><?php echo htmlspecialchars($profile['name']); ?></td>
                </tr>
                <tr>
                    <th>Nickname</th>
                    <td><?php echo htmlspecialchars($profile['nickname']); ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo htmlspecialchars($profile['email']); ?></td>
                </tr>
                <tr>
                    <th>Phone Number</th>
                    <td><?php echo htmlspecialchars($profile['phone_number']); ?></td>
                </tr>
                <tr>
                    <th>Mailing Address</th>
                    <td><?php echo htmlspecialchars($profile['mailing_address']); ?></td>
                </tr>
                <tr>
                    <th>Date of Birth</th>
                    <td><?php echo htmlspecialchars($profile['date_of_birth']); ?></td>
                </tr>
                <tr>
                    <th>Gender</th>
                    <td><?php echo htmlspecialchars($profile['gender']); ?></td>
                </tr>
                <tr>
                    <th>Degree</th>
                    <td><?php echo htmlspecialchars($profile['degree']); ?></td>
                </tr>
                <tr>
                    <th>Graduation Date</th>
                    <td><?php echo htmlspecialchars($profile['graduation_date']); ?></td>
                </tr>
                <tr>
                    <th>Institution</th>
                    <td><?php echo htmlspecialchars($profile['institution']); ?></td>
                </tr>
                <tr>
                    <th>Current Occupation</th>
                    <td><?php echo htmlspecialchars($profile['current_occupation']); ?></td>
                </tr>
                <tr>
                    <th>Current Employer</th>
                    <td><?php echo htmlspecialchars($profile['current_employer']); ?></td>
                </tr>
                <tr>
                    <th>Academic Achievements</th>
                    <td><?php echo htmlspecialchars($profile['academic_achievements']); ?></td>
                </tr>
                <tr>
                    <th>Work Experience</th>
                    <td><?php echo htmlspecialchars($profile['work_experience']); ?></td>
                </tr>
                <tr>
                    <th>Career Achievements</th>
                    <td><?php echo htmlspecialchars($profile['career_achievements']); ?></td>
                </tr>
            </table>



        </div>
    </div>
</body>
</html>
