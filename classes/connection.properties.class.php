<?php
/**
 *
 * class ConnectionProperties
 *    Connection properties needed to access several MySQL databases.
 *
 *    Author: Kevin J Brosnahan
 *    Date: July 2019
 */

if(!class_exists('ConnectionProperties')){
	final class ConnectionProperties{
		private $servername;
		private $username;
		private $password;
		private $dbname;
		private $is_local;

		function __construct(){

			$this->is_local = ( $_SERVER['HTTP_HOST'] == 'localhost:81') ? true : false;
			$args = func_get_args(); 
			$num_args = func_num_args();
			
			$this->dbname = 'pagetoapidb';
			$this->servername = '';
			
			if($this->is_local===true){
				$this->username = 'root';
				$this->password = '';
			}else{
				$this->dbname = '__DBNAME__';
				$this->servername = 'localhost';
				$this->username = '__DB_USERNAME__';
				$this->password = '__DB_PASSWORD__';
			}
				
		}

		public function get_dbname(){
			return $this->dbname;
		}

		public function get_username(){
			return $this->username;
		}

		public function get_servername(){
			return $this->servername;
		}

		public function get_password(){
			return $this->password;
		}

	}
}
?>