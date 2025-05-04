<?php
include("../conn/conn.php"); // Include the database connection
session_start(); // Start the session

// Check if user is logged in and has admin privileges
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login/login.php'); // Redirect to login page if not authorized
    exit();
}

// Check if delete request is valid
if (isset($_GET['type']) && isset($_GET['id'])) {
    $type = $_GET['type'];
    $id = $_GET['id'];

    if ($type === 'student') {
        $delete_query = "DELETE FROM STUDENT WHERE student_id = ?";
    } elseif ($type === 'instructor') {
        $delete_query = "DELETE FROM INSTRUCTOR WHERE instructor_id = ?";
    }

    if (isset($delete_query)) {
        $stmt = $con->prepare($delete_query);
        $stmt->bind_param("s", $id); // Use parameterized query to prevent SQL injection

        if ($stmt->execute()) {
            echo "<script>
                alert('" . ucfirst($type) . " record deleted successfully!');
                window.location.href = '../admin/admin.php';
            </script>";
        } else {
            echo "<script>
                alert('Failed to delete " . ucfirst($type) . " record: " . htmlspecialchars($stmt->error) . "');
                window.location.href = '../admin/admin.php';
            </script>";
        }

        $stmt->close();
    }
} else {
    echo "<script>
        alert('Invalid request!');
        window.location.href = '../admin/admin.php';
    </script>";
}

$con->close();
?>
