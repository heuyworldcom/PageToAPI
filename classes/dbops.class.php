<?php
if(!class_exists('clsDBOps')){
	final class clsDBOps{
		
		//var $retval;
		var $today;
		public $queryType='';
		public $mp = 'npa8WsqCpN7';
		private $cnnprops;
		private $is_local;
		
		function __construct(){
			$this->today = date( 'Y-m-d' );
			$this->mp = "npa8WsqCpN7";
			$this->is_local = ( $_SERVER['HTTP_HOST'] == 'localhost:81') ? true : false;
			$this->cnnprops = new ConnectionProperties( $this->is_local );
		}		
		
		
		public function Exists( $sqlSelect ){ // returns true/false
            $obj_query			= '';
            $obj_conn			= '';
            //$cnnprops = new ConnectionProperties();
            $blnExists = false;
            
            mysqli_report(MYSQLI_REPORT_STRICT); 
            
            // Create connection
            try{
				  $obj_conn = new mysqli( $this->cnnprops->get_servername(), $this->cnnprops->get_username(), $this->cnnprops->get_password(), $this->cnnprops->get_dbname() );
            }
            catch(mysqli_sql_exception $exConnect)
            {

			}

            // Execute sql statement
            $obj_query = $obj_conn->query(  $sqlSelect  );

            if(is_object($obj_query)){
                  if( $obj_query->num_rows >0){
                        $blnExists = true;
                  }
            }

            unset ( $cnnprops, $obj_conn, $obj_query );

            return $blnExists;
      }
			function ReturnOneValue( $sqlSelect ){ // returns array of data
			$retval   			= '';
			$obj_query			= '';
			$obj_conn			= '';
			
			mysqli_report( MYSQLI_REPORT_STRICT );
			
			// Create connection
			try{
				$obj_conn = new mysqli( $this->cnnprops->get_servername(), $this->cnnprops->get_username(), $this->cnnprops->get_password(), $this->cnnprops->get_dbname() );
			}
			catch(mysqli_sql_exception $exConnect)
			{
				$retval = "Fatal connection error.";
				return $retval;
			}
			
			// Check connection
			if ($obj_conn->connect_error) {
				$retval = "Connection failed: " . $obj_conn->connect_error;
			}
			
			// Execute sql statement
			$obj_query = $obj_conn->query(  $sqlSelect  );

			if(is_object($obj_query)){
				$num_rows = $obj_query->num_rows;
				
				if ( $num_rows > 0){
					while( $row = $obj_query->fetch_array(MYSQLI_ASSOC)){
						$ary_fetched[] = $row;
						$key = key($ary_fetched[0]);
						$retval = $ary_fetched[0][$key];
					}
				}
			}else{
			}
			
			$obj_conn->close();
			
			unset( $obj_conn, $obj_query );			
			
			return $retval;
		}
		public function UpdateSpecial( $sqlUpdate, $query_statement_type = _QUERY_STATEMENT_SINGLE, $return_true_false = false ){ // returns array of settings or true/false
			$retval = new clsRetval();
			$obj_query			= '';
			$obj_conn			= '';
			$blnCommitted		= false;
			$affected_rows 		= 0;
			$connection_error	= '';

			mysqli_report( MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT ); 
			
			// Create connection
			try{
				$obj_conn = new mysqli( $this->ConnProps->get_servername(), $this->ConnProps->get_username(), $this->ConnProps->get_password(), $this->ConnProps->get_dbname() );
			}
			catch(mysqli_sql_exception $exConnect)
			{
				//throw $exConnect;

				$retval->retval['msg'] .= 'LINE: '.__LINE__.', '.$exConnect->errorMessage();
				return $retval;
			}

			// Check connection
			if ($obj_conn->connect_error) {
				$retval->retval['msg'] .= 'LINE: '.__LINE__.', '."Connection failed: " . $obj_conn->connect_error;
			} 

			try{

				//$obj_conn->begin_transaction( MYSQLI_TRANS_START_READ_WRITE );
				//$obj_conn->autocommit(FALSE);

				$obj_query = ( $query_statement_type == _QUERY_STATEMENT_SINGLE ) ? $obj_conn->query( $sqlUpdate ) : $obj_conn->multi_query( $sqlUpdate );
				
				//$blnCommitted = $obj_conn->commit();
				$affected_rows = $obj_conn->affected_rows;
				$connection_error = $obj_conn->error;
				$obj_conn->close();

			}catch( mysqli_sql_exception $msi){
				$retval->retval['msg'] .= 'LINE: '.__LINE__.', '." ** MSI ** : " . $msi;
			}
			

			if( $obj_query === true ){
				$retval->retval['status'] = "OK";
				$retval->retval['affected_rows'] = $affected_rows;
				$retval->retval['msg'] = $retval->retval['affected_rows']." Record(s) Updated.";
			}else{
				$retval->retval['msg'] = 'LINE: '.__LINE__.', '.$connection_error;
				$retval->retval['status'] = 'notOK';
			}

			

			unset( $cnnprops, $obj_conn, $obj_query );			
			
			return $retval;
		}

		public function Insert( $sqlInsert ){ // returns array of settings
			$retval = new clsRetval();
			$obj_query			= '';
			$obj_conn			= '';
			
			mysqli_report(MYSQLI_REPORT_STRICT); 
			
			// Create connection
			try{
				$obj_conn = new mysqli( $this->cnnprops->get_servername(), $this->cnnprops->get_username(), $this->cnnprops->get_password(), $this->cnnprops->get_dbname() );
			}
			catch(mysqli_sql_exception $exConnect)
			{
				$retval->retval['msg'] = "Fatal connection error.";
				return $retval;
			}
			
			
			
			
			// Create connection
			//$obj_conn = new mysqli( $this->cnnprops->servername, $this->cnnprops->username, $this->cnnprops->password, $this->cnnprops->dbname);
			
			// Check connection
			if ($obj_conn->connect_error) {
				$retval->retval['msg'] = 'Connection failed: ' . $obj_conn->connect_error;
			} 
			
			// Execute sql statement
			//if ( $obj_conn->query(  $obj_conn->real_escape_string( $sqlInsert ) ) === TRUE ){
			if ( $obj_conn->query(  $sqlInsert  ) === TRUE ){
				$retval->retval['msg'] = "Record inserted.";
				$retval->retval['status'] = "OK";
				$retval->retval['insert_id'] = $obj_conn->insert_id;
			}else{
				$retval->retval['msg'] = $obj_conn->error;
			}
			$obj_conn->close();
			
			unset( $obj_conn, $obj_query);			
			
			return $retval;
		}

		public function Update( $sqlUpdate ){ // returns array of settings
			$retval = new clsRetval();
			$obj_query			= '';
			$obj_conn			= '';
			
			mysqli_report(MYSQLI_REPORT_STRICT); 
			
			// Create connection
			try{
				$obj_conn = new mysqli( $this->cnnprops->get_servername(), $this->cnnprops->get_username(), $this->cnnprops->get_password(), $this->cnnprops->get_dbname() );
			}
			catch(mysqli_sql_exception $exConnect)
			{
				$retval->retval['msg'] = "Fatal connection error.";
				return $retval;
			}

			// Check connection
			if ($obj_conn->connect_error) {
				$retval->retval['msg'] = "Connection failed: " . $obj_conn->connect_error;
			} 
			
			// Execute sql statement
			
			//$obj_query = $obj_conn->query( $obj_con->real_escape_string( $sqlUpdate ) );
			$obj_query = $obj_conn->query( $sqlUpdate  );

			//if ( $obj_conn->query( $obj_conn->real_escape_string( $sqlUpdate ) ) === TRUE ){
			if ( $obj_conn->query(  $sqlUpdate  ) === TRUE ){
				$retval->retval['status'] = "OK";
				$retval->retval['affected_rows'] = $obj_conn->affected_rows;
				$retval->retval['msg'] = $retval->retval['affected_rows']." Record(s) Updated.";
			}else{
				$retval->retval['msg'] = $obj_conn->error;
			}
			
			$obj_conn->close();
			
			unset( $obj_conn, $obj_query);			
			
			return $retval;
		}

		public function Select( $sqlSelect, $includeFieldNames = false, $prettyFieldNames = false){ // returns array of data
			$retval = new clsRetval();
			$obj_query			= '';
			$obj_conn			= '';
			$row				= '';
			$num_rows			= 0;
			$aryFieldNames		= array();
			
			mysqli_report(MYSQLI_REPORT_STRICT); 
			
			// Create connection
			
			try{
				$obj_conn = new mysqli( $this->cnnprops->get_servername(), $this->cnnprops->get_username(), $this->cnnprops->get_password(), $this->cnnprops->get_dbname() );
				
			}
			catch(mysqli_sql_exception $exConnect)
			{
				$retval->retval['msg'] = "Fatal connection error.";
				return $retval;
			}
			
			// Check connection
			if ($obj_conn->connect_error) {
				$retval->retval['msg'] = "Connection failed: " . $obj_conn->connect_error;
			} 
			
			// Execute sql statement

			//$obj_query = $obj_conn->query( $obj_conn->real_escape_string( $sqlSelect ) );
			$obj_query = $obj_conn->query(  $sqlSelect  );
			//$retval->retval['clean_sql'] = $obj_conn->real_escape_string( $sqlSelect );

			

			if(is_object($obj_query)){
				$num_rows = $obj_query->num_rows;
				
				if ( $num_rows > 0){
					$retval->retval['msg'] .= $num_rows." Records retrieved.";
					$retval->retval['status'] = "OK";
					$retval->retval['num_rows'] = $num_rows;
					
					while( $row = $obj_query->fetch_array(MYSQLI_ASSOC)){
						$ary_fetched[] = $row;
					}
					
					// Build array to return
					$retval->retval['record_set']= $ary_fetched;				
				}
				
				// Add sql field names to return array
				if($includeFieldNames){
					$finfo = $obj_query->fetch_fields();
					$fct=0;
				
					foreach ($finfo as $val) {
						$aryFieldNames['fields'][$fct] = $val->name;
						$fct++;
					}
				
					$retval->retval['field_names'] = $aryFieldNames;
				}
				
				
				// Make pretty field names out of those returned by sql
				$prettyArray = array();
				if($prettyFieldNames && is_array($retval->retval['field_names'])){
					foreach($retval->retval['field_names']['fields'] as $k=>$v){
						$prettyArray['pretyfields'][$v] = $this->make_pretty_fieldnames($v);
					}
						$retval->retval['pretty_field_names'] = $prettyArray;
				}
				
			}else{
				$retval->retval['msg'] .= $obj_conn->error;
				$err = new clsErrors();
					$err->AddError( __CLASS__.'->'.__FUNCTION__, __LINE__, 'SQL ERROR.', 0, 'KEVIN BROS');
					unset( $err );
			}
			
			$obj_conn->close();
			
			unset( $obj_conn, $obj_query);			
			
			return $retval;
		}
		
		
		public function AddNarrative(  $user_id, $readings_id, $narrative, $action, $narration_id, $user_ref ){
			$retval = new clsRetval();
			$clsDBOps = new clsDBOps();
			$retvalReadingExists = new clsRetval();
			$retvalNarrationAlreadyExists = new clsRetval();
			
			
			
			
			
			// Validate user before anything!
			$retval = $this::Select(  "SELECT ID FROM users WHERE user_hash='".$user_ref ."';" );
			if($retval->retval['status']=='OK'){
					//=@#%&*()-_ ]*$
					$narrative = $this->strip_quotes( $narrative, array( "<", ">", "`", "{", "}", "@", "(", ")", "//", "&", "=", "?", "+", ":", "[", "]", "$", "|" ) ); // Strips out single and double quotes + optional others
					
					if(strlen($narrative)>0){
						if($action=='Add'){
							$retvalReadingExists = $this::Select(  "SELECT readings_id FROM readings WHERE readings_id=".$readings_id ); // 1. IS READING_ID BEING SUBMITTED AVAILABLE IN READINGS TABLE?
							$retvalNarrationAlreadyExists = $this::Select(  "SELECT `narration_id`, `narration` FROM `reading_narrations` WHERE readings_id=".$readings_id.";");  // 2. DOES NARRATION ALREADY EXIST IN NARRATION TABLE?
							
							if($retvalReadingExists->retval['status'] == 'OK') // Yes. Valid readings_id
							{
								if($retvalNarrationAlreadyExists->retval['status'] == 'OK') // Yes. Narration already exists for this reading so Update it.
								{
									$mySql = "UPDATE `reading_narrations` SET `narration` = CONCAT(`narration`, '".$narrative."'),`last_updated`='".date('Y-m-d H:m:s')."' WHERE readings_id=".$readings_id." AND user_id=".$user_id;
									$retval = $this::Update(  $mySql );	
										if($retval->retval['affected_rows']==0){
											$retval->retval['msg']='DUPLICATE - Could not update record.';
										}
										else
										{
											$retval->retval['msg']='DUPLICATE - Narrative already existed so it was updated.';
										}
									
								}else { // No. Narration does not exist so we will add it.
									$mySql = "INSERT INTO `reading_narrations`(`created_date`, `readings_id`, `user_id`, `active`, `narration`) VALUES ('".date('Y-m-d H:m:s')."', '".$readings_id."','".$user_id."','1','".$narrative."')";	
									$retval = $this::Insert(  $mySql );

									if($retval->retval['status']=='OK'){
										$retval->retval['msg'] = 'Narrative added for your reading.';
									}
								}
							}else {
								$retval->retval['msg']='FATAL ERROR: Reading does not exist for this narrative.';
							}

						}
						
						
						if($action=='Update'){
							$mySql = "UPDATE `reading_narrations` SET `narration`='".$narrative."',`last_updated`='".date('Y-m-d H:m:s')."' WHERE readings_id=".$readings_id." AND narration_id=".$narration_id." AND user_id=".$user_id;
							$retval = $this::Update(  $mySql );	
							if($retval->retval['affected_rows']==0){
								$retval->retval['msg']='Could not update record.';
							}else{
								$retval->retval['msg']='Narrative was updated.';
							}
						}
						
					}else{
						$retval->retval['msg'] = 'No valid characters were found in Narrative.';
					}



				
			}else{
				$retval->retval['msg'] = 'FATAL ERROR: USER DOES NOT EXIST!';
				$err = new clsErrors();
				$err->AddError( __CLASS__.'->'.__FUNCTION__, __LINE__, 'FATAL ERROR: USER DOES NOT EXIST!', $user_id, $user_ref );
				unset( $err );
			}
			
			
			// Create and update user with new user_hash
			$user = new clsUser();
			$retval->retval['user_ref'] = $user->updateUserRef( $user_id, $user_ref );
			unset( $user );
			
			
			$retval->retval['inserted_narrative'] = $narrative;
			unset (  $clsDBOps, $retvalReadingExists, $retvalNarrationAlreadyExists );
			return $retval;
		}

		private function strip_quotes($strIn,$aryMoreToStrip=false){
			$strIn = str_replace("'","",$strIn);
			$strIn = str_replace('"',"",$strIn);
			if(is_array($aryMoreToStrip)){
				foreach($aryMoreToStrip as $symbol){
					$strIn = str_replace($symbol,"",$strIn);
				}
			}
			return $strIn;
		}
		
		private function make_pretty_fieldnames($strIn){
			$strIn = str_replace("_"," ",$strIn);
			return ucwords(strToLower($strIn));
		}
}
}