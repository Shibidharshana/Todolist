<?php

$servername = "localhost";
$username = "root";
$password = "Atom#12345";
$dbname = "todo_app";



$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_POST['add'])) {
    $task = trim($_POST['task']);
    if (!empty($task)) {
        $stmt = $conn->prepare("INSERT INTO todos (TASK_DONE) VALUES (?)");
        $stmt->bind_param("s", $task);
        if ($stmt->execute() === false) {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Task cannot be empty.";
    }
}


if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM todos WHERE TASK_ID = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute() === false) {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}


if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $task_name = trim($_POST['TASK_DONE']);
    if (!empty($task_name)) {
        $stmt = $conn->prepare("UPDATE todos SET TASK_DONE = ? WHERE TASK_ID = ?");
        $stmt->bind_param("si", $task_name, $id);
        if ($stmt->execute() === false) {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Task name cannot be empty.";
    }
}


$result = $conn->query("SELECT * FROM todos ORDER BY CREATED_AT DESC");
if (!$result) {
    die("Error retrieving tasks: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>To-Do List</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        table { width: 50%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        input[type="text"] { width: 80%; padding: 8px; }
        button { padding: 8px 12px; cursor: pointer; }
    </style>
</head>
<body>
    <h2>To-Do List</h2>
    <form method="post" action="">
        <input class="text-bg-primary" type="text" name="task" placeholder="Enter new task">
        <button type="submit" name="add">Add</button>
    </form>

    <table>
        <tr class="table-success">
            <th>Task</th>
            <th>Created Date</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['TASK_DONE'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['CREATED_AT'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td>
                <a href="edit.php?id=<?php echo $row['TASK_ID']; ?>">Edit</a> |
                <a href="index.php?delete=<?php echo $row['TASK_ID']; ?>" onclick="return confirm('Are you sure you want to delete this task?');">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>

<?php $conn->close(); ?>

