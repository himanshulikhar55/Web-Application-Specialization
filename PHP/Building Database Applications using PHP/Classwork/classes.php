<?php
	class Person{
		public $fullName = false;
		public $giveName = false;
		public $familyName = false;
		public $room = false;
		function getName(){
			if($this->fullName!== false)
				return $this->fullName;
			if($this->familyName !== false && $this->giveName !== false)
				return $this->giveName . ' ' . $this->familyName;
			return false;
		}
	}
	$himanshu = new Person();
	$himanshu->fullName = "Rahul Kumar";
	$himanshu->room = "12345";

	$sanskruti = new Person();
	$sanskruti->familyName = "Gupta";
	$sanskruti->giveName = "Neha";
	$sanskruti->room = "123143";

	print $himanshu->getName()."\n";
	print($sanskruti->getName() . "\n");
?>