<?php
include 'db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cat_name = trim($_POST['cat_name']);
    $description = trim($_POST['description']);

    if (!empty($cat_name) && !empty($description)) {
        $stmt = $conn->prepare("INSERT INTO Category (Cat_Name, Description) VALUES (?, ?)");
        $stmt->bind_param("ss", $cat_name, $description);

        if ($stmt->execute()) {
            echo "<script>alert('Category added successfully!'); window.location='add_category.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
    } else {
        echo "<script>alert('Please fill in all fields.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Complaint Category</title>
    <style>
    body {
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        margin: 0;
        min-height: 100vh;
        background: #f0f2f5;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding-top: 2rem;
    }

    form {
        background: white;
        padding: 2rem 2.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 500px;
        transition: transform 0.2s ease;
    }

    form:hover {
        transform: translateY(-2px);
    }

    h2 {
        color: #1a1a1a;
        margin: 0 0 2rem 0;
        font-size: 1.8rem;
        text-align: center;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #007bff;
        display: inline-block;
    }

    label {
        font-weight: 600;
        color: #404040;
        margin: 1.2rem 0 0.5rem;
        display: block;
    }

    input, textarea {
        width: 100%;
        padding: 0.8rem;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
        margin-top: 0;
    }

    input:focus, textarea:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.15);
        outline: none;
    }

    button {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(to right, #0066ff, #0051ff);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        margin-top: 1.5rem;
        transition: all 0.2s ease;
    }

    button:hover {
        background: linear-gradient(to right, #0051ff, #0040ff);
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
    }

    a {
        display: inline-block;
        margin: 1.5rem 0;
        padding: 0.8rem 1.5rem;
        background: #f0f0f0;
        color: #404040;
        text-decoration: none;
        border-radius: 6px;
        transition: all 0.2s ease;
        font-weight: 500;
    }

    a:hover {
        background: #e0e0e0;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 480px) {
        body {
            padding: 1rem;
        }
        
        form {
            padding: 1.5rem;
        }
    }
</style>
</head>
<body>

<h2>Add New Complaint Category</h2>

<form method="POST">
    <label for="cat_name">Category Name:</label>
    <input type="text" name="cat_name" id="cat_name" required>

    <label for="description">Description:</label>
    <textarea name="description" id="description" rows="4" required></textarea>

    <button type="submit">Add Category</button>
</form>

<a href="admin_dashboard.php">← Back to Dashboard</a>

</body>
</html>
