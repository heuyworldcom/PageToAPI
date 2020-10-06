<?php
if(!class_exists('clsErrors')){
  class clsErrors{
      var $today;

      function __construct(){
          $this->today = date( 'Y-m-d' );
      }
	  
	  public function AddError( $php_script, $php_line_num, $error_name, $user_id, $the_sql = "" ){
		  //INSERT INTO `procedure_errors`(`error_id`, `php_script`, `php_line_num`, `error_name`, `user_id`, `created_date`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6])
			try {
			}
			catch (exception $e) {
				
			}
			/*
			  $dbops = new clsDBOps();
			  $retval = new clsRetval();
			  $cnnprops = new ConnectionProperties();
			  $retval = $dbops->ExecuteInsertQuery($cnnprops, "INSERT INTO `procedure_errors`(`php_script`, `php_line_num`, `error_name`, `user_id`, `created_date`, `the_sql`) VALUES ('".$php_script."','".$php_line_num."','".$error_name."','".$user_id."','".date('Y-m-d H:m:s')."','".$the_sql."');");
			  unset( $dbops, $retval, $cnnprops );
			*/
	  }
  }
}
;
?>