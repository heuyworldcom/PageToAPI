<?php 
// Class PageToAPI - Created On: 2020-03-29 at 19:03:23

	if(!class_exists('PageToAPI')){
		class PageToAPI{

			private $propertyName;
			private $propertValue;
			private $dbOps;
			private $ret;
			private $cnnprops;

			// Class specific private variables.
			private $Url;
			private $Data;

			function __construct(){
				$this::Initialize();
			} 

			private function Initialize(){
				$this->propertyName = '';
				$this->propertValue = '';
				$this->Url = '';
				$this->Data = '';
			}

			public function  __destruct() {
			
            }

            public function GetWebPage( $Url )
            {
                $user_agent='Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';
        
                $options = array(
        
                    CURLOPT_CUSTOMREQUEST  =>"GET",        	//set request type post or get
                    CURLOPT_POST           =>false,        	//set to GET
                    CURLOPT_USERAGENT      => $user_agent, 	//set user agent
                    CURLOPT_COOKIEFILE     =>"cookie.txt", 	//set cookie file
                    CURLOPT_COOKIEJAR      =>"cookie.txt", 	//set cookie jar
                    CURLOPT_RETURNTRANSFER => true,     	// return web page
                    CURLOPT_HEADER         => false,    	// don't return headers
                    CURLOPT_FOLLOWLOCATION => false,     	// follow redirects
                    CURLOPT_ENCODING       => "",       	// handle all encodings
                    CURLOPT_AUTOREFERER    => true,     	// set referer on redirect
                    CURLOPT_CONNECTTIMEOUT => 120,      	// timeout on connect
                    CURLOPT_TIMEOUT        => 120,      	// timeout on response
                    CURLOPT_MAXREDIRS      => 10,       	// stop after 10 redirects
                );
        
                $ch      = curl_init( $Url );
                curl_setopt_array( $ch, $options );
				$content = curl_exec( $ch );
				
                $err     = curl_errno( $ch );
                $errmsg  = curl_error( $ch );
                $header  = curl_getinfo( $ch );
                curl_close( $ch );
        
                $header['errno']   = $err;
                $header['errmsg']  = $errmsg;
				$header['content'] = $content;
				
				return $content;
            }
                        

			// GETTER / SETTER Functions
			
			// Url
			public function set_Url( $m_url ){
				$this->Url = $m_url;
			}

			public function get_Url(){
				return $this->Url;
			}
			
			// Data
			public function set_Data( $m_data ){
				$this->Data = $m_data;
			}

			public function get_Data(){
				return $this->Data;
			}

		}  // Class PageToAPI()
	} // if(!class_exists('PageToAPI')
?>