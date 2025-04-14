<?php
include 'db_connect.php';
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Coordinators</title>
    <style>
    body { 
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; 
        margin: 40px auto;
        max-width: 1200px;
        color: #333;
        background-color: #f8f9fa;
    }
    .container {
        padding: 30px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    }
    h2 {
        text-align: center;
        color: #2c3e50;
        margin-bottom: 35px;
        padding-bottom: 10px;
        border-bottom: 3px solid #3498db;
        font-size: 2.2em;
    }
    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-top: 25px;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 10px rgba(0,0,0,0.05);
    }
    th, td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #ecf0f1;
    }
    th {
        background-color: #3498db;
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.9em;
        letter-spacing: 0.8px;
    }
    tr:hover {
        background-color: #f8f9fa;
    }
    tr:nth-child(even) {
        background-color: #f8fafb;
    }
    .back-button button {
        background: #3498db;
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1em;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .back-button button:hover {
        background: #2980b9;
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(52,152,219,0.25);
    }
    a[href^="edit_coordinator"] {
        background: #27ae60;
        color: white;
        padding: 8px 15px;
        border-radius: 4px;
        transition: all 0.2s ease;
        font-size: 0.9em;
    }
    a[href^="edit_coordinator"]:hover {
        background: #219a52;
        text-decoration: none;
        transform: translateY(-1px);
    }
    td:last-child {
        text-align: center;
    }
    tr:last-child td {
        border-bottom: none;
    }
    @media (max-width: 768px) {
        body {
            margin: 20px;
        }
        .container {
            padding: 20px;
        }
        th, td {
            padding: 12px;
        }
    }
</style>
</head>
<body>

<h2>List of Coordinators</h2>

<div class="back-button">
    <a href="admin_dashboard.php"><button>← Back to Dashboard</button></a>
</div>

<table>
    <thead>
        <tr>
            <th>Coordinator ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Category (Department)</th>
            <th>Action</th>


        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT c.CoordID, c.Name, c.Email, c.Role, c.Phone, c.Address, cat.Cat_Name 
                FROM Coordinator c
                LEFT JOIN Category cat ON c.CatID = cat.CatID";

        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['CoordID']}</td>
                        <td>{$row['Name']}</td>
                        <td>{$row['Email']}</td>
                        <td>{$row['Role']}</td>
                        <td>{$row['Phone']}</td>
                        <td>{$row['Address']}</td>
                        <td>{$row['Cat_Name']}</td>
                       <td>
                        <a href='edit_coordinator.php?id=" . $row['CoordID'] . "'>Edit</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No coordinators found.</td></tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>
