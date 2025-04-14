<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'Student') {
    header("Location: login.php");
    exit();
}
require 'db_connect.php';
$user_id = $_SESSION['user_id'];

// Fetch complaints for the logged-in student
$query = "SELECT CompID, Description, Status FROM Complaints WHERE UserID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Dashboard</title>
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
  <h1>Welcome, <?php echo $_SESSION['user_name']; ?> (Student)</h1>
    <h1>Student Dashboard</h1>
    <div class="nav-links">
      <a href="submit_complaint.php">Submit Complaint</a>
      <a href="view_complaints.php">View Complaints</a>
      <a href="logout.php">Logout</a>
    </div>
  </nav>

  <!-- Content Section -->
  <div class="container">
<!-- Optional example cards if PHP is not loaded -->
<div class="card">
      <h3>Submit New Complaint</h3>
      <p>Have something to report? Submit a new complaint to the administration team.</p>
      <a href="complaint_form.php">Submit Now →</a>
    </div>

    <div class="card">
      <h3>My Complaints</h3>
      <p>Check your submitted complaints and track their current status.</p>
      <a href="complaint_status.php">View Complaints →</a>
    </div>

    

  </div>

  <!-- Back Button -->
  <button class="back-btn" onclick="goBack()">← Back</button>

  

  <script>
    function goBack() {
      window.history.back();
    }
  </script>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Dashboard</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f4f7fa;
      padding: 40px 20px;
      color: #333;
      text-align: center;
    }

    h2 {
      font-size: 32px;
      margin-bottom: 10px;
      color: #2c3e50;
    }

    h3 {
      font-size: 24px;
      margin: 30px 0 20px;
      color: #34495e;
    }

    .table-container {
      max-width: 900px;
      margin: auto;
      overflow-x: auto;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
      padding: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th, td {
      padding: 14px 18px;
      text-align: left;
      font-size: 15px;
    }

    th {
      background-color: #3498db;
      color: white;
    }

    tr:nth-child(even) {
      background-color: #f2f9ff;
    }

    tr:hover {
      background-color: #eaf4ff;
    }

    .btn {
      padding: 8px 14px;
      background-color: #2980b9;
      color: white;
      text-decoration: none;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      font-size: 14px;
    }

    .btn:hover {
      background-color: #1f618d;
    }

    .btn-feedback {
      background-color: #27ae60;
    }

    .btn-feedback:hover {
      background-color: #1e8449;
    }

    span {
      font-weight: bold;
      color: #7f8c8d;
    }
  </style>
</head>
<body>


  <h3>Your Complaints</h3>

  <div class="table-container">
    <table>
      <tr>
        <th>Complaint</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
      <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['Description'] ?></td>
        <td><?= $row['Status'] ?></td>
        <td>
          <?php if ($row['Status'] == 'Resolved'): ?>
              <a href="feedback.php?comp_id=<?= $row['CompID'] ?>" class="btn btn-feedback">Give Feedback</a>
          <?php else: ?>
              <span>N/A</span>
          <?php endif; ?>
        </td>
      </tr>
      <?php endwhile; ?>
    </table>
  </div>
  <footer>
    © 2025 College Complaint Management System
  </footer>

</body>
</html>



