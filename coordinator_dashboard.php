<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'Coordinator') {
    header("Location: login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Coordinator Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    :root {
      --primary: #185a9d;
      --accent: #43cea2;
      --light-bg: #f1f6fb;
      --text-dark: #333;
      --white: #ffffff;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
    }

    body {
      background: var(--light-bg);
      color: var(--text-dark);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* Navbar */
    nav {
      background: linear-gradient(to right, var(--accent), var(--primary));
      padding: 16px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: white;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    nav h1 {
      font-size: 24px;
    }

    .nav-links a {
      color: white;
      text-decoration: none;
      margin-left: 25px;
      font-weight: 500;
      transition: 0.3s ease;
    }

    .nav-links a:hover {
      color: #f1f1f1;
      text-decoration: underline;
    }

    .container {
      padding: 40px 20px;
      max-width: 1200px;
      margin: auto;
      display: grid;
      gap: 25px;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    }

    .Your_AC{
      padding: 20px;
      background: var(--white);
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }
      

    .card {
      background: var(--white);
      border-radius: 16px;
      padding: 30px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 25px rgba(0, 0, 0, 0.12);
    }

    .card h3 {
      margin-bottom: 12px;
      color: var(--primary);
    }

    .card p {
      font-size: 15px;
      line-height: 1.5;
    }

    .card a {
      display: inline-block;
      margin-top: 15px;
      text-decoration: none;
      font-weight: 500;
      color: var(--primary);
      transition: 0.3s;
    }

    .card a:hover {
      color: var(--accent);
      text-decoration: underline;
    }

    .back-btn {
      position: fixed;
      bottom: 30px;
      left: 30px;
      background: var(--primary);
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 14px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
      transition: background 0.3s ease;
    }

    .back-btn:hover {
      background: var(--accent);
    }

    footer {
      margin-top: auto;
      padding: 20px;
      text-align: center;
      color: #777;
      font-size: 14px;
    }

    @media(max-width: 600px) {
      nav {
        flex-direction: column;
        align-items: flex-start;
      }

      .nav-links {
        margin-top: 10px;
      }

      .nav-links a {
        display: block;
        margin: 8px 0 0 0;
      }
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav>
    <h1>Welcome, <?php echo $_SESSION['user_name']; ?> (Coordinator)</h1>
    <h1>Coordinator Dashboard</h1>
    <div class="nav-links">
      <a href="manage_complaints.php">Manage Complaint</a>
      <a href="logout.php">Logout</a>
    </div>
  </nav>

  <!-- Content Section -->
  <div class="container">
<!-- Optional example cards if PHP is not loaded -->
<div class="card">
      <h3>Manage Complaints</h3>
      <p>Here You Manage Complaints Of the Students and Update the Status of complaints</p>
      <a href="manage_complaints.php">Manage →</a>
    </div>
</div>

  <!-- Back Button -->
  <button class="back-btn" onclick="goBack()">← Back</button>

  

  <script>
    function goBack() {
      window.history.back();
    }
  </script>



<?php

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'Coordinator') {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "College_Complaint_DB");

$coordID = $_SESSION['user_id'];

// Get the coordinator's category
$stmt = $conn->prepare("SELECT CatID FROM Coordinator WHERE CoordID = ?");
$stmt->bind_param("i", $coordID);
$stmt->execute();
$stmt->bind_result($catID);
$stmt->fetch();
$stmt->close();

// Get complaints of this category that are In Progress or Pending
$complaints = $conn->prepare("SELECT c.CompID, u.Name AS StudentName, c.Description, c.Status FROM Complaints c JOIN User u ON c.UserID = u.UserID WHERE c.CatID = ?");
$complaints->bind_param("i", $catID);
$complaints->execute();
$result = $complaints->get_result();
?>
<div class = "Your_AC">
<h2>Your Assigned Complaints</h2>
<?php while ($row = $result->fetch_assoc()): ?>
    <div style="border:1px solid #aaa; padding:10px; margin:10px;">
        <p><strong>Complaint ID:</strong> <?= $row['CompID'] ?></p>
        <p><strong>From Student:</strong> <?= $row['StudentName'] ?></p>
        <p><strong>Status:</strong> <?= $row['Status'] ?></p>
        <p><strong>Description:</strong> <?= $row['Description'] ?></p>
    </div>
<?php endwhile; ?>
</div>
</body>
</html>