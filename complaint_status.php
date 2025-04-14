<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'Student') {
    header("Location: login.php");
    exit();
}
include 'db_connect.php';

$user_id = $_SESSION['user_id']; // Get the logged-in student's ID

// Fetch complaints submitted by the student
$sql = "SELECT c.CompID, cat.Cat_Name, c.Description, c.Status, c.DateSubmitted 
        FROM complaints c
        JOIN category cat ON c.CatID = cat.CatID
        WHERE c.UserID = '$user_id' 
        ORDER BY c.DateSubmitted DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Complaint Status</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 50px;
      text-align: center;
      background-color: #f5f8fa;
    }
    h2 {
      color: #2c3e50;
      margin-bottom: 30px;
    }
    table {
      width: 80%;
      margin: auto;
      border-collapse: collapse;
      box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
    }
    th, td {
      border: 1px solid #ddd;
      padding: 12px;
      text-align: left;
    }
    th {
      background-color: #3498db;
      color: white;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    tr:hover {
      background-color: #eaf2f8;
    }
    .back-link {
      display: inline-block;
      margin-top: 30px;
      text-decoration: none;
      color: #2980b9;
      font-weight: bold;
      transition: 0.3s ease;
    }
    .back-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <h2>Your Complaint Status</h2>

  <table>
    <tr>
      <th>Complaint ID</th>
      <th>Description</th>
      <th>Status</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['CompID'] ?></td>
        <td><?= $row['Description'] ?></td>
        <td><?= $row['Status'] ?></td>
      </tr>
    <?php endwhile; ?>
  </table>

  <a href="student_dashboard.php" class="back-link">← Back to Dashboard</a>

</body>
</html>