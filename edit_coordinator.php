<?php
include 'db_connect.php';
session_start();

// Get Coordinator ID from URL
$coord_id = $_GET['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cat_id = $_POST['cat_id'];

    $stmt = $conn->prepare("UPDATE Coordinator SET CatID = ? WHERE CoordID = ?");
    $stmt->bind_param("ii", $cat_id, $coord_id);

    if ($stmt->execute()) {
        echo "<script>alert('Category changed successfully.'); window.location='view_coordinator.php';</script>";
    } else {
        echo "<script>alert('Error: {$stmt->error}');</script>";
    }
}

// Fetch Coordinator details
$stmt = $conn->prepare("SELECT Name, Email, Role, CatID FROM Coordinator WHERE CoordID = ?");
$stmt->bind_param("i", $coord_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Coordinator Category</title>
    <style>
    body {
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        margin: 0;
        padding: 40px;
        background: #f3f6fb;
        color: #2d3748;
        min-height: 100vh;
    }

    .container {
        max-width: 600px;
        margin: 0 auto;
        background: white;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    h2 {
        color: #2b6cb0;
        font-size: 2rem;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e2e8f0;
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    label {
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 0.5rem;
        display: block;
    }

    input, select {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.2s ease;
        background: #f8fafc;
    }

    input:read-only {
        background-color: #edf2f7;
        cursor: not-allowed;
    }

    input:focus, select:focus {
        outline: none;
        border-color: #63b3ed;
        box-shadow: 0 0 0 3px rgba(99, 179, 237, 0.2);
    }

    select {
        appearance: none;
        
    }

    button[type="submit"] {
        background: #4299e1;
        color: white;
        padding: 14px 24px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        align-self: flex-start;
    }

    button[type="submit"]:hover {
        background: #3182ce;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(66, 153, 225, 0.3);
    }

    a {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 25px;
        color: #4299e1;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s ease;
    }

    a:hover {
        color: #2b6cb0;
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        body {
            padding: 20px;
        }
        
        .container {
            padding: 25px;
        }
        
        h2 {
            font-size: 1.75rem;
        }
        
        input, select {
            padding: 10px 14px;
        }
    }
</style>
</head>
<body>
<div class="container">

<h2>Change Coordinator's Category</h2>

<form method="POST">
    <label>Name:</label>
    <input type="text" value="<?= $data['Name'] ?>" readonly>

    <label>Email:</label>
    <input type="text" value="<?= $data['Email'] ?>" readonly>

    <label>Current Role:</label>
    <input type="text" value="<?= $data['Role'] ?>" readonly>

    <label>Assign New Category:</label>
    <select name="cat_id" required>
        <option value="">-- Select Category --</option>
        <?php
        $cat_result = $conn->query("SELECT CatID, Cat_Name FROM Category");
        while ($row = $cat_result->fetch_assoc()) {
            $selected = ($row['CatID'] == $data['CatID']) ? "selected" : "";
            echo "<option value='{$row['CatID']}' $selected>{$row['Cat_Name']}</option>";
        }
        ?>
    </select>

    <button type="submit">Change Category</button>
</form>

<br>
<a href="view_coordinator.php">← Back to Coordinator List</a>
</div>
</body>
</html>
