<?php

	require_once('sqlCon.php');
	require_once('sqlCommands.php');
	require_once('textRtn.php');
    // Variable used to produce error message
	$fault = "";

	// Sql variable for commands and connection		
	$sqlCom = new sqlCommands();
	
	//Variable used to return string arrays
	$tabText = new textRtn();

	// Update task buttons clicked
	if (isset($_GET['delete'])) {
		$id = $_GET['delete'];
		//Delete task based on id
		$sqlCom->delTask($id);
		header('location: todolist.php');
	}
	elseif (isset($_GET['completed'])) {
		$id = $_GET['completed'];
		//Update task to completed
		$sqlCom->updateStat(3,$id);
		header('location: todolist.php');
	}
	elseif (isset($_GET['started'])) {
		$id = $_GET['started'];
		//Update task to started
		$sqlCom->updateStat(2,$id);
		header('location: todolist.php');
	}
	
		// Submit button clicked
	if (isset($_POST['taskInp'])) {
		if (empty($_POST['task'])) //If task is empty mark as invalid
			$fault = "Please enter a task";
		elseif (preg_match('/^[^a-zA-Z]*$/',$_POST['task'])) //If task does not include any letters mark as invalid
			$fault = "Invalid entry";
		elseif (empty($_POST['dueDate'])) //If due date is empty mark as invalid
			$fault = "Please enter a due date";
		else{
			$task = $_POST['task'];
			$dueDate = $_POST['dueDate'];
			
			//Add new task to sql database
			$sqlCom->newTask($task, $dueDate);	
			//Reload original page
			header('location: todolist.php');
		}
	}
	
	// Update late tasks
	$sqlCom->updateLate();
	
?>
	
	
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="todolist.css">
		<title>To-Do List</title>	
	</head>
	
	<body>

		<h1>
			To-Do List
		</h1>
		
		<form method="post" action="todolist.php" class="userForm">
			<p><?php echo $fault; ?></p>
			Task:
			<textarea type="text" name="task" class="taskBox"></textarea>
			Due date:
			<input type="date" name="dueDate"></input>
			<button type="submit" name="taskInp" class="addTask">Add Task</button>
		</form>

		<table class="taskTable">
		
				<tr>
				<?php $i = 0;
				$tabText->firstTableHeaders();
				while ($i < 7) { ?>
					<th><?php echo $tabText->textArr[$i]; ?></th>
				<?php $i++; } ?>
				</tr>

				<?php 
				// Displays tasks based on sort 
				if (isset($_POST['pendB']))
					$tasks = $sqlCom->getTasks(1);
				elseif (isset($_POST['startB']))
					$tasks = $sqlCom->getTasks(2);
				elseif (isset($_POST['compB']))
					$tasks = $sqlCom->getTasks(3);
				elseif (isset($_POST['lateB']))
					$tasks = $sqlCom->getTasks(4);
				else
					$tasks = $sqlCom->getTasks();
				
				$i = 1; 
				while ($record = mysqli_fetch_array($tasks)) { ?>
					<tr>
						<td> <?php echo $i; ?> </td>
						<td class="centerCol"> <?php echo $record['task']; ?> </td>
						<td class="centerCol"> <?php echo $record['due_date']; ?> </td>	
						<td class="centerCol"> <?php echo $record['status']; ?> </td>
						<td class="start"> 
							<a href="todolist.php?started=<?php echo $record['id'] ?>">&#10000</a> 
						</td>
						<td class="complete"> 
							<a href="todolist.php?completed=<?php echo $record['id'] ?>">&#10004</a> 
						</td>
						<td class="delete"> 
							<a href="todolist.php?delete=<?php echo $record['id'] ?>">X</a> 
						</td>
					</tr>
				<?php $i++; } ?>	
		
		</table>
		
		<table class="countTable">
			<tr>
				<th>Status</th>
				<th>Count</th>
			</tr>
			<?php
			$i = 1;
			$tabText->secondTableStats();
			$butArr = array("","pendB","startB","compB","lateB");
			while ($i < 5) { ?>
				<tr>
					<td><?php echo $tabText->textArr[$i]; ?></td>			
					<td align="center">
						<form method="post" action="todolist.php" class="cForm">
							<button type="submit" name="<?php echo $butArr[$i]; ?>">	
							<?php echo $sqlCom->getCount($i); ?></button>
						</form>
					</td>
				</tr>
			<?php $i++; } ?>
			<tr>
				<td style="background: grey;">Total</td>
				<td style="background: grey;"  align="center">
					<form method="post" action="todolist.php" class="cForm">
						<button type="submit" name="totalB">	
						<?php echo $sqlCom->getCount(); ?></button>
					</form>
				</td>
			</tr>
		</table>
	</body>
</html>