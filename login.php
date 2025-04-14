<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $found = false;

    // 1. Check User (Student)
    $sql = "SELECT UserID, Name, Email, Password FROM User WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['Password'])) {
            $_SESSION['user_id'] = $row['UserID'];
            $_SESSION['user_name'] = $row['Name'];
            $_SESSION['user_email'] = $row['Email'];
            $_SESSION['user_role'] = 'Student';
            header("Location: student_dashboard.php");
            exit;
        }
        $found = true;
    }

    // 2. Check Coordinator
    if (!$found) {
        $sql = "SELECT CoordID, Name, Email, Password FROM Coordinator WHERE Email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result2 = $stmt->get_result();

        if ($result2 && $result2->num_rows === 1) {
            $row = $result2->fetch_assoc();
            if (password_verify($password, $row['Password'])) {
                $_SESSION['user_id'] = $row['CoordID'];
                $_SESSION['user_name'] = $row['Name'];
                $_SESSION['user_email'] = $row['Email'];
                $_SESSION['user_role'] = 'Coordinator';
                header("Location: coordinator_dashboard.php");
                exit;
            }
            $found = true;
        }
    }

    // 3. Check Admin
    // 3. Check Admin
if (!$found) {
  $sql = "SELECT AdminID, Name, Email, Password FROM Admin WHERE Email = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result3 = $stmt->get_result();

  if ($result3 && $result3->num_rows === 1) {
      $row = $result3->fetch_assoc();

      if (password_verify($password, $row['Password'])) {
          session_regenerate_id(true); // ✅ optional, for security
          $_SESSION['user_id'] = $row['AdminID'];         // general user ID
          $_SESSION['user_name'] = $row['Name'];
          $_SESSION['user_email'] = $row['Email'];
          $_SESSION['user_role'] = 'Admin';
          $_SESSION['admin_id'] = $row['AdminID'];        // ✅ crucial for admin report generation

          header("Location: admin_dashboard.php");
          exit;
      }
      $found = true;
  }
}

    echo "<script>alert('Invalid email or password.');</script>";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - College Complaint Management</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #667eea, #764ba2);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-box {
      background: #ffffff;
      padding: 40px 30px;
      border-radius: 15px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
      width: 100%;
      max-width: 400px;
      animation: slideIn 0.6s ease-in-out;
    }

    @keyframes slideIn {
      from {
        transform: translateY(30px);
        opacity: 0;
      }
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    h2 {
      text-align: center;
      color: #333;
      margin-bottom: 30px;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 15px;
    }

    button[type="submit"] {
      width: 100%;
      padding: 14px;
      background: linear-gradient(to right, #43cea2, #185a9d);
      border: none;
      border-radius: 8px;
      font-size: 16px;
      color: white;
      cursor: pointer;
      margin-top: 15px;
      transition: all 0.3s ease;
    }

    button[type="submit"]:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .footer {
      text-align: center;
      font-size: 13px;
      color: #888;
      margin-top: 20px;
    }

   
a {
    text-decoration: none; 
    color: #007BFF; 
    font-size: 16px; 
    padding: 10px; 
    border: 1px solid transparent; 
    border-radius: 5px; 
}

a:focus {
    outline: none; 
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); 
}
  </style>
</head>
<body>

  <div class="login-box">
    <h2>Login</h2>
<form action="" method="POST">
    <input type="email" name="email" placeholder="Enter Email" required>
    <input type="password" name="password" placeholder="Enter Password" required>
    <button type="submit">Login</button>
</form>

<a href="register.php">← Back to register page</a>

</div>

</body>
</html>
