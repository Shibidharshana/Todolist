<?php
$servername = "localhost";
$username = "root";
$password = "Atom#12345";
$dbname = "todo_app";


$conn = new mysqli($servername, $username, $password);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
} else {
    die("Invalid task ID.");
}


$stmt = $conn->prepare("SELECT * FROM todos WHERE TASK_ID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$task = $result->fetch_assoc();

if (!$task) {
    die("Task not found.");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    if (!empty($_POST['TASK_DONE'])) {
        $task_done = htmlspecialchars($_POST['TASK_DONE'], ENT_QUOTES, 'UTF-8'); // Escape input

        $update_stmt = $conn->prepare("UPDATE todos SET TASK_DONE = ? WHERE TASK_ID = ?");
        $update_stmt->bind_param("si", $task_done, $id);

        if ($update_stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "Task name cannot be empty.";
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Task</title>
</head>
<body>
    <h2>Edit Task</h2>
    <form method="post" action="">
        <input type="hidden" name="TASK_ID" value="<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="text" name="TASK_DONE" value="<?php echo htmlspecialchars($task['TASK_DONE'], ENT_QUOTES, 'UTF-8'); ?>" required>
        <button type="submit" name="update">Update</button>
    </form>
</body>
</html>



<?php $conn->close(); ?>
