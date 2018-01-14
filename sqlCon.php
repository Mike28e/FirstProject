<?php
	//SQL Connection Class
	class sqlCon {
		private $host = "localhost";
		private $user = "root";
		// Add pass here
		private $pass = "";
		private $dataB = "todo";
		var $connection;

		function sqlConnect() {
			$con = mysqli_connect($this->host, $this->user, $this->pass, $this->dataB);
			if (!$con) {
				die('Cannot connect to database!');
			} else 
				$this->connection = $con;
		}

		function sqlClose() {
			mysqli_close($connection);
		}
	}

?>