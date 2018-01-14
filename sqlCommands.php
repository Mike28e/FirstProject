<?php
	//SQL Commands Class
	class sqlCommands {
		var $sqlStr;
		protected $db;
		
		function __construct() {
			$this->db = new sqlCon();
			$this->db->sqlConnect();
		}
		
		function newTask($task, $dueDate){
			$dueDate = date("Y-m-d", strtotime($dueDate));
			$this->sqlStr = "INSERT INTO tasks (task) VALUES ('$task'); 
					  INSERT INTO duedates (date_id, due_date) VALUES (LAST_INSERT_ID(), CAST('". $dueDate ."' AS DATE));
					  UPDATE tasks SET status_id = 4 WHERE id IN (SELECT date_id FROM duedates WHERE due_date < CURDATE());";
			mysqli_multi_query($this->db->connection, $this->sqlStr);
		}
		
		function getTasks($stat = "") {
			if (!(empty($stat)))
				$str = "WHERE a.status_id =";
			else
				$str = "";
			$tasks = mysqli_query($this->db->connection, "SELECT * FROM tasks a LEFT JOIN statuses b ON a.status_id = b.status_id LEFT JOIN duedates c ON a.id = c.date_id $str ". $stat ." ORDER BY id;");
			return $tasks;
		}
		
		function getCount($stat = "") {
			if (!(empty($stat)))
				$str = "WHERE status_id =";
			else
				$str = "";
			$count = mysqli_query($this->db->connection, "SELECT COUNT(*) as total FROM tasks $str ".$stat); 
			$count = mysqli_fetch_array($count);
			$count = $count['total'];
			return $count;
		}
		
		function delTask($id) {
			mysqli_multi_query($this->db->connection, "DELETE FROM tasks WHERE id=". $id ." ; DELETE FROM duedates WHERE date_id=". $id ." ;");
		}
		
		function updateStat($stat, $id) {
			mysqli_query($this->db->connection, "UPDATE tasks SET status_id = ". $stat ." WHERE id=".$id);
		}
		
		function updateLate() {
			mysqli_query($this->db->connection, "UPDATE tasks SET status_id = 4 WHERE id IN (SELECT date_id FROM duedates WHERE due_date < CURDATE()) AND status_id != 3;");
		}
	}

?>