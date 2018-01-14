<?php

	class textRtn{
		public $textArr;
		
		function firstTableHeaders() {
			$this->textArr = array("#","Tasks","Due","Status","Start","Done","Delete");
		}
		
		function secondTableStats() {
			$this->textArr = array("","Pending","Started","Completed","Late"); 
		}
	}
	
?>