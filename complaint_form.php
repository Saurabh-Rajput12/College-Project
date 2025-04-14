<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'Student') {
    header("Location: login.php");
    exit();
}
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id']; // Get logged-in student's ID
    $category = $_POST['category']; // Complaint category
    $description = $_POST['description']; // Complaint description
    $status = 'Pending'; // Default status when submitted

    // Retrieve the CatID for the selected category
    $category_query = "SELECT CatID FROM category WHERE Cat_Name = ?";
    $stmt = mysqli_prepare($conn, $category_query);
    mysqli_stmt_bind_param($stmt, "s", $category);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $cat_id);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($cat_id) {
        // Insert the complaint into the complaints table
        $sql = "INSERT INTO complaints (UserID, CatID, Description, Status, DateSubmitted) 
                VALUES (?, ?, ?, ?, NOW())";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iiss", $user_id, $cat_id, $description, $status);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Complaint submitted successfully!'); window.location.href='student_dashboard.php';</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Invalid category selected.');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Submit Complaint</title>
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
    form {
      width: 60%;
      margin: auto;
      padding: 30px;
      background-color: #ffffff;
      box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
      border-radius: 10px;
    }
    label {
      display: block;
      margin-bottom: 10px;
      text-align: left;
      font-weight: bold;
      color: #34495e;
    }
    textarea, select {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 6px;
      resize: vertical;
    }
    .btn {
      padding: 10px 20px;
      background-color: #3498db;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 16px;
      transition: background 0.3s ease;
    }
    .btn:hover {
      background-color: #2980b9;
    }
    .back-link {
      display: inline-block;
      margin-top: 20px;
      text-decoration: none;
      color: #2980b9;
      font-weight: bold;
    }
    .back-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <h2>Submit a New Complaint</h2>

  <form action="complaint_form.php" method="post">
    <input type="hidden" name="student_id" value="<?= $studentId ?>">

    <label for="category">Category</label>
    <select name="category" id="category" required>
      <option value="">-- Select Category --</option>
      <option value="Academic">Academic</option>
      <option value="Administration">Administration</option>
      <option value="Facilities">Facilities</option>
      <option value="Hostel">Hostel</option>
      <option value="Library">Library</option>
      <option value="Transport">Transport</option>
    </select>

    <label for="description">Complaint Description</label>
    <textarea name="description" id="description" rows="5" required></textarea>

    <button type="submit" class="btn">Submit Complaint</button>
  </form>

  <button class="back-btn" onclick="goBack()">← Back</button>
  <script>
    function goBack() {
      window.history.back();
    }
  </script>

</body>
</html>