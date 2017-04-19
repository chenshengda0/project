<?php 
       /* *
	* Log 			A logger class which creates logs when an exception is thrown.
	* @author		Author: Vivek Wicky Aswal. (https://twitter.com/#!/VivekWickyAswal)
	* @git 			https://github.com/indieteq/PHP-MySQL-PDO-Database-Class
	* @version      0.1a
	*/
	class Switchlog {
			
		    # @string, Log directory name
	    	private $path = 'runtime/';
	    	
	    	/**
        	 * 设置记录日志标志
        	 * @param $bLogFlag ： true - 记录； 其他 - 不记录
        	 */
	    	private $bLogFlag = false;
			
		    # @void, Default Constructor, Sets the timezone and path of the log files.
			public function __construct($bLogFlag=false) {
				date_default_timezone_set('Asia/Shanghai');	
				$this->path  = SITE_PATH .'sdk/runtime/';	
				$this->bLogFlag = $bLogFlag;
			}
			
		   /**
		    *   @void 
		    *	Creates the log
		    *
		    *   @param string $message the message which is written into the log.
		    *	@description:
		    *	 1. Checks if directory exists, if not, create one and call this method again.
	            *	 2. Checks if log already exists.
		    *	 3. If not, new log gets created. Log is written into the logs folder.
		    *	 4. Logname is current date(Year - Month - Day).
		    *	 5. If log exists, edit method called.
		    *	 6. Edit method modifies the current log.
		    */	
			public function write($message, $logname='') {
			    //false不记录
			    if($this->bLogFlag == false){
			        return;
			    }
			    
				$date = new DateTime();
				if (empty($logname)){
				    $logname = 'db';
				}
                
                $log = $this->path.$logname.'/'.$date->format('Y-m-d').".txt";
				
				if(is_dir($this->path.$logname.'/')) {
					if(!file_exists($log)) {
						$fh  = fopen($log, 'a+') or die("Fatal Error !");
						$logcontent = "Time : " . $date->format('H:i:s')."\r\n" . $message ."\r\n";
						fwrite($fh, $logcontent);
						fclose($fh);
					} else {
						$this->edit($log,$date, $message);
					}
				} else {
                    if(mkdir($this->path.$logname.'/',0777) === true){
                        $this->write($message,$logname);  
                    }	
				}
			 }
			
			/** 
			 *  @void
			 *  Gets called if log exists. 
			 *  Modifies current log and adds the message to the log.
			 *
			 * @param string $log
			 * @param DateTimeObject $date
			 * @param string $message
			 */
		    private function edit($log,$date,$message) {
				$logcontent = "Time : " . $date->format('H:i:s')."\r\n" . $message ."\r\n\r\n";
				$logcontent = $logcontent . file_get_contents($log);
				file_put_contents($log, $logcontent);
		    }
		}
?>
