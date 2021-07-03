<?php 

include("functions.php");

session_start();


    // initialize errors variable
	$errors = "";

	// connect to database
	$db1 = mysqli_connect("localhost", "root", "", "login_sample_db");

	$user_data = check_login($db1);

	$currentId = $user_data['id'];


	// insert a quote if submit button is clicked
	if (isset($_POST['submit'])) {
		if (empty($_POST['task'])) {
			$errors = "You must fill in the task";
		}else{
			$task = $_POST['task'];
			$sql = "INSERT INTO tasks (task , users_id) VALUES ('$task' , '$currentId')";
			mysqli_query($db1, $sql);
			header('location: todo.php'); 
		}
	}
	// delete task
	if (isset($_GET['del_task'])) {
		$id = $_GET['del_task'];
		mysqli_query($db1, "DELETE FROM tasks WHERE users_id=$currentId AND id=$id");
		// header('location: index.php');
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>ToDo List Application PHP and MySQL</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div class="heading">
		<h2 style="font-style: 'Hervetica';">Task List</h2>
	</div>
	<form method="post" action="todo.php" class="input_form">
		<input type="text" name="task" class="task_input">
		<button type="submit" name="submit" id="add_btn" class="add_btn">Add Task</button>
	</form>
	<table>
	<thead>
		<tr>
			<th>N</th>
			<th>Task id</th>
			<th>Tasks</th>
			<th style="width: 60px;">Action</th>
		</tr>
	</thead>

	<tbody>
		<?php 
		// select all tasks if page is visited or refreshed
		$tasks = mysqli_query($db1, "SELECT * FROM tasks WHERE users_id=$currentId") or die( mysqli_error($db1));

		$i = 1; while ($row = mysqli_fetch_array($tasks)) { ?>
			<tr>
				<td> <?php echo $i; ?> </td>
				<td class="taskid"> <?php echo $row['id']; ?> </td>
				<td class="task"><?php echo $row['task']; ?> </td>
				<td class="delete"> 
			    <a href="todo.php?del_task=<?php echo $row['id']?>">x</a> 
				</td>
			</tr>
		<?php $i++; } ?>	
	</tbody>
	</table>
	<?php if (isset($errors)) { ?>
	<p><?php echo $errors; ?></p>
	<?php } ?>

<a href="logout.php">Logout</a><br><br>
<a href="index.php">Home Page</a><br><br>
<a href="changepass.php">Click here</a>
</body>
</html>
