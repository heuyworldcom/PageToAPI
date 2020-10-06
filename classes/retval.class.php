<?php
/**
 * Standard object returned by most other classes
 */
 
if(!class_exists('clsRetval')){
	class clsRetval{
		
		var $retval;

		function __construct(){

			$this->retval = array(
				'origin_info'=>'',
				'status'=>'notOK', 
				'msg'=>'', 
				'kev'=>'hey there',
				'record_set'=>array(), 
				'field_names'=>array(),
				'insert_id'=>0, 
				'affected_rows'=>0,
				'user_id'=>0,
				'user_login'=>'',
				'user_ref'=>'',
				'sql'=>'',
				'num_rows'=>0,
				'greeting'=>'',
				'html'=>'',
				'num_logins'=>0,
				'last_login'=>'',
				'report_quota_reached'=>'no'
			);

			
		}	public function get_retval(){
				return $this->retval;
			}	
	}
}
?>