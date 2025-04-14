<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // secure
    $role = $_POST['role']; // should be Student, Coordinator, or Admin

    if ($role === 'Student') {
        $sql = "INSERT INTO User (Name, Email, Address, PhoneNo, Password, Role) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $name, $email, $address, $phone, $password, $role);

    } elseif ($role === 'Coordinator') {
        $sql = "INSERT INTO Coordinator (Name, Email, Role, Password,address,Phone) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $fixedRole = 'Assigned Coordinator';
        $stmt->bind_param("ssssss", $name, $email, $fixedRole, $password, $address, $phone);

    } elseif ($role === 'Admin') {
        $sql = "INSERT INTO Admin (Name, Email, Role, Password,address,Phone) VALUES (?, ?, ?, ?, ?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $name, $email,$role, $password,$address,$phone);

    } else {
        echo "<script>alert('Invalid role selected.');</script>";
        exit;
    }

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful! You can now login.');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Registration</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    :root {
      --primary: #185a9d;
      --accent: #43cea2;
      --bg: #f2f7ff;
      --text-dark: #333;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: var(--bg);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .register-container {
      background: #fff;
      padding: 40px 30px;
      border-radius: 15px;
      box-shadow: 0 12px 28px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 500px;
      animation: fadeIn 0.6s ease-in-out;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .register-container h2 {
      text-align: center;
      margin-bottom: 25px;
      color: var(--primary);
    }

    form input[type="text"],
    form input[type="email"],
    form input[type="password"],
    form select {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 10px;
      font-size: 15px;
    }

    form button {
      width: 100%;
      padding: 14px;
      background: linear-gradient(to right, var(--accent), var(--primary));
      border: none;
      border-radius: 10px;
      font-size: 16px;
      color: white;
      cursor: pointer;
      margin-top: 20px;
      transition: all 0.3s ease;
    }

    form button:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    .footer-text {
      text-align: center;
      margin-top: 20px;
      font-size: 14px;
      color: #777;
    }

    .footer-text a {
      color: var(--primary);
      text-decoration: none;
    }

    .footer-text a:hover {
      text-decoration: underline;
    }
    .back-btn {
      width: 100%;
      padding: 14px;
      background: linear-gradient(to right, var(--accent), var(--primary));
      border: none;
      border-radius: 10px;
      font-size: 16px;
      color: white;
      cursor: pointer;
      margin-top: 20px;
      transition: all 0.3s ease;
    }
  </style>
</head>
<body>

  <div class="register-container">
    <h2>Registeration Page</h2>
<form action="" method="POST">
    <input type="text" name="name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="address" placeholder="Address" required>
    <input type="text" name="phone" placeholder="Phone Number" required>
    <input type="password" name="password" placeholder="Password" required>
    <select name="role" required>
        <option value="Student">Student</option>
        <option value="Coordinator">Coordinator</option>
        <option value="Admin">Admin</option>
    </select>
    <button type="submit">Register</button>
</form>
<div class="footer-text">
      Already registered? <a href="login.php">Login here</a>
</div>
<a href="landing_page.html">← Back to Landing page</a>
  </div>
  

</body>
</html>
