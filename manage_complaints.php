<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'Coordinator') {
    header("Location: login.php");
    exit();
}
include 'db_connect.php';

// Fetch complaints from the database
$sql = "SELECT c.CompID, u.Name AS StudentName, cat.Cat_Name AS CategoryName, 
               c.Description, c.Status, c.DateSubmitted 
        FROM Complaints c
        JOIN User u ON c.UserID = u.UserID
        JOIN Category cat ON c.CatID = cat.CatID
        ORDER BY c.DateSubmitted DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Manage Complaints</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
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
      width: 85%;
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
    .btn {
      padding: 8px 14px;
      background-color: #27ae60;
      color: white;
      border: none;
      text-decoration: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background 0.3s ease;
    }
    .btn:hover {
      background-color: #1e8449;
    }
    .back-link {
      display: inline-block;
      margin-top: 25px;
      text-decoration: none;
      color: #2980b9;
      font-weight: bold;
    }
    .back-link:hover {
      text-decoration: underline;
    }
    .btn-update {
  background-color: skyblue;
  padding: 8px 14px;
  border-radius: 5px;
  color: white;
  text-decoration: none;
  font-weight: bold;
  transition: background-color 0.3s ease;
}
.btn-update:hover {
  background-color:red;
}
  </style>
</head>
<body>

  <h2>Manage Complaints</h2>

  <table>
    <tr>
      <th>Complaint ID</th>
      <th>Student ID</th>
      <th>Description</th>
      <th>Status</th>
      <th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $row['CompID'] ?></td>
      <td><?= $row['StudentName'] ?></td>
      <td><?= $row['Description'] ?></td>
      <td><?= $row['Status'] ?></td>
      <td>
      <a href="update_status.php?compid=<?= $row['CompID'] ?>" class="btn btn-update">Update Status</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>

  <a href="coordinator_dashboard.php" class="back-link">← Back to Dashboard</a>

</body>
</html>