<?php
	/**
	 * Autoloads a class
	 * @param (string) the class
	 * @return (int) the time elapsed in seconds
	 */
	function autoload($class) {
		//$startTime = microtime();
	    $paths = explode(PATH_SEPARATOR, get_include_path());
	    $paths[] = '..';
	    $flags = PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE;
	    $file = str_replace('\\', DIRECTORY_SEPARATOR, 
			trim($class, "\\")) . ".class.php";
	        
	    foreach($paths as $path) {
	    	$combined = $path . DIRECTORY_SEPARATOR . $file;
	    	
	    	if (file_exists($combined)) {
	    		include($combined);
	    		//$endTime = microtime();
	    		//echo $file . ": " .($endTime - $startTime) . "m <br />";
	    		return;
	    	}
	    }
	    throw new Exception("{$class} not found");
	    
	}
	
	class Autoloader {
		public static function autoload($class) {
			autoload($class);
		}
	}
	
	spl_autoload_register('autoload');
	spl_autoload_register(array('autoloader', 'autoload'));
	
	// these can only be called within a class context...
	// spl_autoload_register(array($this, 'autoload'));
	// spl_autoload_register(__CLASS__.'::load');

?>
