<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'Coordinator') {
    header("Location: login.php");
    exit();
}

include 'db_connect.php';

// Check if a complaint ID is provided
if (!isset($_GET['compid']) || empty($_GET['compid'])) {
    die("Complaint ID is missing!");
}

$compid = $_GET['compid'];

// Fetch complaint details
$sql = "SELECT c.CompID, u.Name AS StudentName, cat.Cat_Name AS CategoryName, 
               c.Description, c.Status, c.DateSubmitted 
        FROM Complaints c
        JOIN User u ON c.UserID = u.UserID
        JOIN Category cat ON c.CatID = cat.CatID
        WHERE c.CompID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $compid);
$stmt->execute();
$result = $stmt->get_result();
$complaint = $result->fetch_assoc();

if (!$complaint) {
    die("Complaint not found!");
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_status = $_POST['status'];

    $update_sql = "UPDATE Complaints SET Status = ? WHERE CompID = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $new_status, $compid);

    if ($update_stmt->execute()) {
        echo "<script>alert('Status updated successfully!'); window.location.href='manage_complaints.php';</script>";
    } else {
        echo "<script>alert('Error updating status!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Update Complaint Status</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f5f8fa;
      padding: 50px;
      display: flex;
      justify-content: center;
    }

    .container {
      background-color: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 600px;
    }

    h2 {
      text-align: center;
      color: #2c3e50;
      margin-bottom: 25px;
    }

    p {
      font-size: 16px;
      margin: 10px 0;
    }

    strong {
      color: #2c3e50;
    }

    label {
      display: block;
      font-weight: bold;
      margin: 20px 0 8px;
    }

    select {
      width: 100%;
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 16px;
    }

    button {
      margin-top: 20px;
      width: 100%;
      padding: 12px;
      background-color: #27ae60;
      color: white;
      font-size: 16px;
      font-weight: bold;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    button:hover {
      background-color: #1e8449;
    }

    .back-link {
      display: inline-block;
      margin-top: 20px;
      text-align: center;
      text-decoration: none;
      color: #3498db;
      font-weight: bold;
      transition: color 0.3s;
    }

    .back-link:hover {
      color: #21618c;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Update Complaint Status</h2>
  <p><strong>Complaint ID:</strong> <?= $complaint['CompID'] ?></p>
  <p><strong>Student:</strong> <?= $complaint['StudentName'] ?></p>
  <p><strong>Category:</strong> <?= $complaint['CategoryName'] ?></p>
  <p><strong>Description:</strong> <?= $complaint['Description'] ?></p>
  <p><strong>Current Status:</strong> <strong><?= $complaint['Status'] ?></strong></p>

  <form method="post">
    <label for="status">Change Status:</label>
    <select name="status" id="status">
      <option value="Pending" <?= $complaint['Status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
      <option value="In Progress" <?= $complaint['Status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
      <option value="Resolved" <?= $complaint['Status'] == 'Resolved' ? 'selected' : '' ?>>Resolved</option>
      <option value="Rejected" <?= $complaint['Status'] == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
    </select>
    <button type="submit">Update Status</button>
  </form>

  <button class="back-btn" onclick="goBack()">← Back</button>

  <script>
    function goBack() {
      window.history.back();
    }
  </script>

</div>

</body>
</html>
