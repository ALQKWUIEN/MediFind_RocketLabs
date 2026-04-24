<?php
session_start();
include_once '../00_Config/config.php';

// Only process the form if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize inputs
    $fname = trim($_POST['Fname'] ?? '');
    $mname = trim($_POST['Mname'] ?? '');
    $lname = trim($_POST['Lname'] ?? '');
    $contact = trim($_POST['ContactNo'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $birth_date = trim($_POST['birth_date'] ?? '');
    $province = trim($_POST['Province'] ?? '');
    $city = trim($_POST['City'] ?? '');
    $barangay = trim($_POST['Barangay'] ?? '');
    $street = trim($_POST['Street'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmpass = $_POST['confirmpass'] ?? '';

    // Validate required fields
    if (!$fname || !$lname || !$contact || !$gender || !$birth_date || !$province || !$city || !$barangay || !$street || !$email || !$username || !$password || !$confirmpass) {
        $_SESSION['error'] = 'Please fill in all required fields.';
        header('Location: signup.php');
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Invalid email format.';
        header('Location: signup.php');
        exit();
    }

    // Validate password match
    if ($password !== $confirmpass) {
        $_SESSION['error'] = 'Passwords do not match.';
        header('Location: signup.php');
        exit();
    }

    // Check if email already exists
    $sql_check_email = "SELECT User_ID FROM `01_user_users` WHERE Email = ?";
    if ($stmt = $conn->prepare($sql_check_email)) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $_SESSION['error'] = 'This email is already registered.';
            $stmt->close();
            header('Location: signup.php');
            exit();
        }
        $stmt->close();
    }

    // Check if username already exists
    $sql_check_username = "SELECT User_ID FROM `01_user_users` WHERE Username = ?";
    if ($stmt = $conn->prepare($sql_check_username)) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $_SESSION['error'] = 'This username is already taken.';
            $stmt->close();
            header('Location: signup.php');
            exit();
        }
        $stmt->close();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the address into the database
    $sql_insert_address = "INSERT INTO `05_Address_User_Address` (Barangay, Street) VALUES (?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql_insert_address)) {
        $stmt->bind_param('ssss', $province, $city, $barangay, $street);
        if ($stmt->execute()) {
            $address_id = $stmt->insert_id; // Get the inserted address ID
            $stmt->close();
        } else {
            $_SESSION['error'] = 'Failed to save address. Please try again.';
            header('Location: signup.php');
            exit();
        }
    }

    // Insert the user into the database
    $sql_insert_user = "INSERT INTO `01_user_users` (Username, First_name, Middle_name, Last_name, Email, Phone, Address_ID, Role_ID, User_Status_ID, Profilepic_url, Password_hash, DateCreated, Date_Approved) 
                        VALUES (?, ?, ?, ?, ?, ?, 2, NULL, NULL, ?, ?, NOW(), NULL)";
    if ($stmt = $conn->prepare($sql_insert_user)) {
        $stmt->bind_param('sssssis', $username, $fname, $mname, $lname, $email, $contact, $address_id, $hashed_password);
        if ($stmt->execute()) {
            // Account created successfully
            $_SESSION['success'] = 'Account created successfully! You can now log in.';
            header('Location: login.php'); // Redirect to login page
            exit();
        } else {
            $_SESSION['error'] = 'Failed to create account. Please try again.';
            header('Location: signup.php');
            exit();
        }
    } else {
        $_SESSION['error'] = 'Database error. Please try again.';
        header('Location: signup.php');
        exit();
    }
}
?>