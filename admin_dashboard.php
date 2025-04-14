
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'Admin') {
    header("Location: login.php");
    exit();
}
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
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
.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f9f9f9;
  min-width: 300px;
  padding: 20px;
  box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
  z-index: 1;
  display: grid;
  grid-template-columns: 1fr 1fr; /* Two-column layout */
  gap: 10px;
  border-radius: 8px;
}

/* Show dropdown on hover */
.dropdown:hover .dropdown-content {
  display: grid;
}

.dropdown a {
  color: black;
  padding: 8px;
  text-decoration: none;
  display: block;
  border-radius: 5px;
}

.dropdown a:hover {
  background-color: #ddd;
}

</style>
</head>
<body>

  <!-- Navbar -->
  <nav>
  <h1>Welcome, <?php echo $_SESSION['user_name']; ?> (Admin)</h1>
    <h1>Admin Dashboard</h1>
    <div class="nav-links">
      <a href="view_coordinator.php">View Coordinators</a>
      <a href="report_center.php">Report Center</a>
      <a href="download_complaints.php">Download Complaints</a>
      <a href="add_category.php">Add Category</a>
      <a href="add_coordinator.php">Add Coordinator</a>
      <a href="logout.php">Logout</a>
    </div>
  </nav>

  <!-- Content Section -->
  <div class="container">
<!-- Optional example cards if PHP is not loaded -->
<div class="card">
      <h3>View Coordinators</h3>
      <p>You can view Coordinator according to the complaint & Coordinator Can manage it</p>
      <a href="view_coordinator.php">View Coordinators →</a>
    </div>

    <div class="card">
      <h3>Download Complaints</h3>
      <p>Here You can Download Total Complaints that is generated.</p>
      <a href="download_complaints.php">Download Complaints →</a>
    </div>

    <div class="card">
      <h3>Add Category</h3>
      <p>Here You can Add category.</p>
      <a href="add_category.php">Add Category →</a>
    </div>

    <div class="card">
      <h3>Add Coordinator</h3>
      <p>Here You add Coordinator.</p>
      <a href="add_coordinator.php">Add Coordinator →</a>
    </div>

    <div class="card">
      <h3>Report Center</h3>
      <p>Here You See the Reports Of your Complaints</p>
      <a href="report_center.php"> Report Center →</a>
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

<?php
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'Admin') {
    header("Location: login.php");
    exit();
}
include 'db_connect.php';
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch filter and search values
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sort_order = isset($_GET['sort']) && $_GET['sort'] == 'oldest' ? 'ASC' : 'DESC';

// Build SQL query with filters
$sql = "SELECT c.CompID, u.UserID AS User_Name, cat.Cat_Name AS CategoryName, 
               c.Description, c.Status, c.DateSubmitted 
        FROM Complaints c
        JOIN User u ON c.UserID = u.UserID
        JOIN Category cat ON c.CatID = cat.CatID";
$result1 = mysqli_query($conn, $sql);
/*$where_clauses = [];
if ($status_filter) {
    $where_clauses[] = "c.Status = '$status_filter'";
}
if ($search) {
    $where_clauses[] = "(u.Name LIKE '%$search%' OR cat.Cat_Name LIKE '%$search%')";
}
if (!empty($where_clauses)) {
    $sql .= " WHERE " . implode(" AND ", $where_clauses);
}
$sql .= " ORDER BY c.DateSubmitted $sort_order";*/

$query = "SELECT f.FeedID, u.Name AS Student, c.Description, f.Rating, f.Comment, f.DateSubmitted 
          FROM Feedback f 
          JOIN Complaints c ON f.CompID = c.CompID 
          JOIN User u ON f.UserID = u.UserID
          ORDER BY f.DateSubmitted DESC";
$result2 = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f0f4f8;
      color: #2c3e50;
      margin: 0;
      padding: 20px;
    }

    .container {
      max-width: 1200px;
      margin: 30px;
      background-color:rgb(255, 255, 255);
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
    }
    .Assign {
      width: 92%;
      margin: 27px;
      margin-left: 30px;
      background-color:rgb(255, 255, 255);
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
    }


    .header {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 25px;
    }

    .header h1 {
      font-size: 26px;
      color: #2c3e50;
      flex: 1 1 300px;
    }

    .filters form {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      justify-content: flex-end;
    }

    .filters select,
    .filters input,
    .filters button {
      padding: 8px 12px;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    .filters button {
      background-color: #3498db;
      color: white;
      border: none;
      cursor: pointer;
    }

    .filters a button {
      background-color: #7f8c8d;
    }

    .filters button:hover {
      opacity: 0.9;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      margin-bottom: 30px;
    }

    th, td {
      padding: 12px 15px;
      border-bottom: 1px solid #ddd;
      text-align: left;
      font-size: 14px;
    }

    th {
      background-color: #f4f7fb;
      color: #2c3e50;
    }

    tr:hover {
      background-color: #f9f9f9;
    }

    h3 {
      font-size: 22px;
      margin-bottom: 15px;
      color: #2c3e50;
      border-bottom: 2px solid #ddd;
      padding-bottom: 5px;
    }

    .pending { color: orange; font-weight: bold; }
    .in-progress { color: #2980b9; font-weight: bold; }
    .resolved { color: green; font-weight: bold; }
    .rejected { color: red; font-weight: bold; }

    @media (max-width: 768px) {
      .filters form {
        flex-direction: column;
        align-items: stretch;
      }

      .header {
        flex-direction: column;
        align-items: flex-start;
      }

      .header h1 {
        margin-bottom: 10px;
      }
    }
  </style>
</head>
<body>

<div class="container">

  <!-- Heading + Filter Section -->
  <div class="header">
    <h1>Admin Dashboard - Complaint Monitoring</h1>

    <div class="filters">
      <form method="GET">
        <select name="status">
          <option value="">All Status</option>
          <option value="Pending" <?= $status_filter == 'Pending' ? 'selected' : '' ?>>Pending</option>
          <option value="In Progress" <?= $status_filter == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
          <option value="Resolved" <?= $status_filter == 'Resolved' ? 'selected' : '' ?>>Resolved</option>
          <option value="Rejected" <?= $status_filter == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
        </select>

        <input type="text" name="search" placeholder="Search Student or Category" value="<?= htmlspecialchars($search) ?>">

        <select name="sort">
          <option value="newest" <?= $sort_order == 'DESC' ? 'selected' : '' ?>>Newest First</option>
          <option value="oldest" <?= $sort_order == 'ASC' ? 'selected' : '' ?>>Oldest First</option>
        </select>

        <button type="submit">Apply</button>
        <a href="admin_dashboard.php"><button type="button">Reset</button></a>
      </form>
    </div>
<!-- Complaint Table -->
<table>
    <thead>
      <tr>
        <th>Complaint ID</th>
        <th>User Name</th>
        <th>Category</th>
        <th>Description</th>
        <th>Status</th>
        <th>Date Submitted</th>
      </tr>
    </thead>
    
    <tbody>
      <?php while($row = mysqli_fetch_assoc($result1)): ?>
      <tr>
        <td><?= $row['CompID'] ?></td>
        <td><?= $row['User_Name'] ?></td>
        <td><?= $row['CategoryName'] ?></td>
        <td><?= $row['Description'] ?></td>
        <td class="<?= strtolower(str_replace(' ', '-', $row['Status'])) ?>"><?= $row['Status'] ?></td>
        <td><?= $row['DateSubmitted'] ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <!-- Feedback Section -->
  <h3>Student Feedback</h3>
  <table>
    <thead>
      <tr>
        <th>Student</th>
        <th>Complaint</th>
        <th>Rating</th>
        <th>Comments</th>
        <th>Date Submitted</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($result2)): ?>
      <tr>
        <td><?= $row['Student'] ?></td>
        <td><?= $row['Description'] ?></td>
        <td><?= $row['Rating'] ?>/5</td>
        <td><?= $row['Comment'] ?></td>
        <td><?= $row['DateSubmitted'] ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  </div>
</div>




</body>
</html>

