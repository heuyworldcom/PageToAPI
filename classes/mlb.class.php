<?php 
// Class MLB - Created On: 2020-09-22 at 17:09:41 by www.programmers-pal.com 
	if(!class_exists('MLB')){
		class MLB extends PageToAPI {

			private $propertyName;
			private $propertValue;
			private $dbOps;
			private $ret;
			private $cnnprops;

			// Class specific private variables - declaration

			private $StartingTerm;
			private $EndingTerm;
			private $StartingPos;
			private $EndingPos;
            private $InsertStati;
            private $RawData;
            private $OutputSQL;
            private $Columns;
            private $OutputXML;
			private $OutputCSV;
            private $OutputJSON;
            private $is_local;     

			function __construct(){
				$this::Initialize();
			} 

			private function Initialize(){
				$this->propertyName = '';
				$this->propertValue = '';
				$this->dbOps = new clsDBOps();
				$this->ret = new clsRetval();
				

				// Class specific private variables - initialize

				$this->StartingTerm = '';
				$this->EndingTerm = '';
				$this->StartingPos = 0;
                $this->EndingPos = 0;
                // Array for INSERT stati
                $this->InsertStati = array();
                $this->RawData = array();
                $this->OutputSQL = '';
                $this->OutputXML = '';
				$this->OutputCSV = '';
				$this->OutputJSON = '';                
                $this->Columns = explode(',','player_name,team,games_played,at_bats,runs,hits,doubles,triples,home_runs,runs_batted_in,walks,strike_outs,stolen_bases,caught_stealing,batting_average,on_base_percentage,slugging_percentage,on_base_and_slugging');
                $this->is_local = ( $_SERVER['HTTP_HOST'] == 'localhost:81') ? true : false;

			}

			public function  __destruct() {
			
			}

			// Methods
            
            public function ScrapePage()
            {
                
                // CURL the live mlb stats page
                //$this->Html = $this::GetWebPage( $this::get_Url() );

                // Load a local copy of the html that would be scraped on the live Url
                if($this->is_local === true)
                {
                    $this->Html = file_get_contents( "C:\\xampp\\htdocs\\xampp\\pagetoapi\\offline_player_stats_data.html", true );    
                }else{
                    $this->Html = file_get_contents( "/var/www/html/phpdeveloperpro.com/public_html/streamers/offline_player_stats_data.html", true );
                }
                
            }

            public function GetRawData()
            { 
                $this::ScrapePage();

                // Pull block of html containing player stats
                
                $StartingPos = stripos( $this->Html, '/player/' );
                $EndingPos = stripos( $this->Html, '<script', $StartingPos + 10 );
                $Data = substr( $this->Html, $StartingPos, $EndingPos + 10 );
                $Record = '';
                
                // Create an array of individual Players
                $Players = explode( '/player/', $Data );
                $RawData = '';

                foreach($Players AS $k=>$v){
                    if(strlen(trim($v))>0)
                    {
                        
                        $iPosBeg = stripos( $v, 'aria-label=' ) + strlen( 'aria-label=' ) + 1;
                        $iPosEnd = stripos( $v, '>', $iPosBeg );
                        
                        $PlayerName = substr( $v, $iPosBeg, ( $iPosEnd - $iPosBeg - 1 ) );
                        $Record = $PlayerName.",";

                        // Create an array of columnar data
                        $headers = explode( 'headers=', $v );

                        // Iterate columnar data and pull stats data while building the rest of the INSERT statement
                        for($row = 1; $row<18; $row++)
                        {
                            $hiPosBeg = stripos( $headers[$row], '>' ) + 1;
                            $hiPosEnd = stripos( $headers[$row], '<' );
                            $Record .= substr( $headers[$row], $hiPosBeg, ( $hiPosEnd - $hiPosBeg ) ).",";
                            $RawData .= substr( $headers[$row], $hiPosBeg, ( $hiPosEnd - $hiPosBeg ) ).'|';
                        
                            
                            if( $row == 16 ){ // Next to Last column
                                $hiPosBeg = stripos( $headers[17], '>' ) + 1;
                                $hiPosEnd = stripos( $headers[17], '<' );
                                
                                $Record .= substr( $headers[17], $hiPosBeg, ( $hiPosEnd - $hiPosBeg ) ).",";
    
                                $RawData = substr( $Record, 0, strlen( $Record ) - 1 );
                                $this->RawData[] = explode( ',', $RawData );
                                $RawData = '';
                            }
                        } // End of for $row

                    } // End of strlen v
    
                } // End of foreach Players
                
            } // End of ExtractData()

        public function GetSQL( $ExecuteSQL = false )
        { 
                $sqlStart = "INSERT INTO `player_stats`(`player_name`, `team`,`games_played`, `at_bats`, `runs`, 
                `hits`, `doubles`, `triples`, `home_runs`, `runs_batted_in`, `walks`, `strike_outs`, `stolen_bases`, 
                `caught_stealing`, `batting_average`, `on_base_percentage`, `slugging_percentage`, `on_base_and_slugging`) 
                VALUES (";

                $sqlMiddle = '';
                $sqlEnd = '';
                
                foreach( $this->RawData AS $k => $v )
                {
                    for( $x=0; $x<=17; $x++ ){
                        $sqlMiddle .= $v[$x].",";
                    }
                    
                    $sqlMiddle = substr( $sqlMiddle, 0, strlen( $sqlMiddle ) - 1 ).");";
                    $sqlEnd .= $sqlStart.$sqlMiddle.PHP_EOL;
                    $sqlMiddle = '';
                
                } // End of this->RawData

                if( $ExecuteSQL === true )
                    {
                        // Execute the INSERT sql
                        // $ret is an array returned by the Insert() command
                        // InsertStati is an array of stati on whether the Insert() command succeeded or not
                        $this->ret = $this->dbOps->Insert( $sqlStart.$sqlMiddle );
                            if( $this->ret->retval['status'] == 'OK' )
                                {
                                    $this->InsertStati[] = array('Player'=>$PlayerName,'Inserted'=>'Yes');
                                }else{
                                    $this->InsertStati = array('ALERT'=>'ERROR','Player'=>$PlayerName,'Inserted'=>'No');
                            }
                } // End of if ExecuteSQL

                $this->OutputSQL = $sqlEnd;

            } // End of GetSQL()

            private function debugSave( $filename, $data ){
                $myfile = fopen("Outputs\\".$filename, "w") or die("Unable to open file!");
                fwrite($myfile, $data);
                fclose($myfile);
            }
    
            public function GetXML()
			{
                $xml = "<?xml version='1.0' standalone='yes'?>".PHP_EOL.'<Players>'.PHP_EOL;

                foreach( $this->RawData AS $k => $v )
                {
                    $xml .= '  <Player>'.PHP_EOL;
                    for( $x=0; $x<=17; $x++ ){
                        $xml .= '    <'.$this->Columns[$x].'>'.str_replace("'","",$v[$x]).'</'.$this->Columns[$x].'>'.PHP_EOL;
                    }
                    $xml .= '  </Player>'.PHP_EOL;
                }
                $xml .= '</Players>';
                
                $this->set_OutputXML($xml);
                
			} // End of GetXML()

			public function GetJSON()
			{
                $aryJson = array();
                $aryPlayer = array();

                foreach( $this->RawData AS $k => $v )
                {

                    for( $x=0; $x<=17; $x++ ){
                        $aryPlayer[$this->Columns[$x]] = str_replace("'","",$v[$x]);
                    }

                    $aryJson[] = $aryPlayer;
                }

                $this->set_OutputJSON( json_encode( $aryJson ) );
                
			} // End of GetJSON()

			public function GetCSV()
			{
                $csv = '';
                $rec = '';

                foreach($this->Columns AS $k=>$ColName){
                    $csv .= $ColName.',';
                }

                $csv = substr($csv,0,strlen($csv)-1).PHP_EOL;

                foreach( $this->RawData AS $k => $v )
                {
                    for( $x=0; $x<=17; $x++ ){
                        $rec .= str_replace("'","",$v[$x]).',';
                    }
                    $rec = substr($rec,0,strlen($rec)-1).PHP_EOL;
                }

                $this->set_OutputCSV( $csv.$rec );	
                
            } // End of GetCSV()
            
            // GETTER / SETTER Functions
			
			// OutputJSON - String
			public function set_OutputJSON( $m_outputjson ){
				$this->OutputJSON = $m_outputjson;
			}

			public function get_OutputJSON(){
				return $this->OutputJSON;
			}

			// OutputXML - String
			public function set_OutputXML( $m_outputxml ){
                
				$this->OutputXML = $m_outputxml;
			}

			public function get_OutputXML(){
				return $this->OutputXML;
			}
			
			// OutputCSV - String
			public function set_OutputCSV( $m_outputcsv ){
				$this->OutputCSV = $m_outputcsv;
			}

			public function get_OutputCSV(){
				return $this->OutputCSV;
            }
            			
			// OutputSQL - String
			public function set_OutputSQL( $m_sqlinsert ){
				$this->OutputSQL = $m_sqlinsert;
			}

			public function get_OutputSQL(){
				return $this->OutputSQL;
			}
            
            // InsertStati - Array()
            public function set_InsertStati( $m_insertstati ){
                $this->InsertStati = $m_insertstati;
            }

            public function get_InsertStati(){
                return $this->InsertStati;
            }			

			// StartingTerm - String
			public function set_StartingTerm( $m_startingterm ){
				$this->StartingTerm = $m_startingterm;
			}

			public function get_StartingTerm(){
				return $this->StartingTerm;
			}
			
			// EndingTerm - String
			public function set_EndingTerm( $m_endingterm ){
				$this->EndingTerm = $m_endingterm;
			}

			public function get_EndingTerm(){
				return $this->EndingTerm;
			}
			
			// StartingPos - Integer
			public function set_StartingPos( $m_startingpos ){
				$this->StartingPos = $m_startingpos;
			}

			public function get_StartingPos(){
				return $this->StartingPos;
			}
			
			// EndingPos - Integer
			public function set_EndingPos( $m_endingpos ){
				$this->EndingPos = $m_endingpos;
			}

			public function get_EndingPos(){
				return $this->EndingPos;
			}

		}  // Class MLB()
	} // if(!class_exists('MLB')
?>