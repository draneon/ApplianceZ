<?php
// Util.php

class DICOM_Util {

    const Tag_NovaradSiteCode = '(0025,1011)';

    public static function toJPEG2000($in, $out) {
        self::exec("/home/novarad/bin/dcmcj2k ".escapeshellarg($in).' '.escapeshellarg($out));
        unlink($in);
    }

    public static function insertTag($file, $tag, $value) {
        self::exec('dcmodify -nrc -i "'.$tag.'='.$value.'" '.escapeshellarg($file));
        // remove backup file
        unlink($file.'.bak');
    }

	// Sends compressed image files to store server.
	// Returns collection of files that could not be sent.
    public static function moveJPEG2000DICOMDirectoryToSCP($dir, $host, $port, $AETitle) {
	
		// generate the dicom command to be executed to send out compressed images to store server
		$sendCmd = self::generateSendCommand( $dir, $host, $port, $AETitle ) ;
		$output = "";
		
		if ($sendCmd !== "") {
			$output = self::exec( sendCmd );
		}
		
        // $output = self::exec('storescu +tla -ic --timeout 10 -aet '.escapeshellarg($AETitle).' --propose-j2k-lossless +sd -nh '.escapeshellarg($host).' '.escapeshellarg($port).' '.
                // escapeshellarg($dir));

        preg_match_all('/E: Bad DICOM file: (.*):/', $output, $matches);
        $failures = array();
        foreach ($matches[1] as $failedFile) {
            $failures[$failedFile] = true;
        }
		// delete image files that have been sent successfully from disk
        foreach (glob($dir.'/*') as $file) {
            if (!isset($failures[$file])) {
                unlink($file);
            }
        }

        return array_keys($failures);
    }

    public static function pingSCU($host, $port) {
        self::exec('echoscu +tla -ic '.escapeshellarg($host).' '.escapeshellarg($port));
    }

    private static function exec($command) {
        $dirConfig = Application_Model_DirectoryConfigMapper::load();

        $return = -1;
        $command .= ' 2>&1';
        $output = null;
        exec($command, $output, $return);
	
	$outTrimmed = array();
	
	for ( $i=0; $i < count($output); $i++) {
		$temp = trim($output[$i]);
		if ($temp) {
			$outTrimmed[] = date("[m-d-Y H:i:s] ", time()).$temp;
		}
	}
	$output = implode("\n", $outTrimmed)."\n";
        //$output = implode("\n", $output) . "\n";
        file_put_contents($dirConfig->getErrorLog(), $output, FILE_APPEND);

        if ($return != 0) {
            throw new RuntimeException('Command execution failed: status code '.$return, $return);
        }

        return $output;
    }
	
	
	// check if a file is ready for compression
	public static function isFileLocked($file) {
	
		$islocked = false;
	
		// get access flag and lock flag of the image file
		$cmd = 'lsof -F al ' . $file;
		
		// outputs from executing command
		$shellOutput = shell_exec( $cmd );
		
		// find access mode(read, write) and lock status
		if ( $shellOutput !== "" ) {
			// output is in this format for each process
			// p'processID' newline  a'accessmode' newline l'lockstatus' 
			$results = explode(PHP_EOL, $shellOutput);			
			
			foreach ($results as $field){			
				$field = trim($field);
				$length = strlen($field);
				if ( $field !== "" && $length > 1 ){
					if ( $field[0] === "l" && $field[1] !== " " ) {
						// character following 'l' is space means file is not locked
						$islocked = true;
						break;
					}
				}
			}		
		}		
		return $islocked;
	}
	
	// get a collection of compressed files that will be sent to the store server	
	public static function getCompressedFiles() {
	
		$dirConfig = Application_Model_DirectoryConfigMapper::load();
		$readyFiles  = array()	;

		// go thru each file in 'compressed' folder and find out if it is being accessed by other processes
		// a file may be in the process of compression
		foreach (glob($dirConfig->getQueueCompressed().'/*') as $zippedFile) {

			if (DICOM_Util::isFileLocked($zippedFile)) {
				continue;
			}			
			$readyFiles[] = $zippedFile;			
		}
		
		return $readyFiles;		
	}
	
	// generate the dicom command to be executed to send out compressed images to store server
	public static function generateSendCommand( $dir, $host, $port, $AETitle) {
		
		$dirConfig = Application_Model_DirectoryConfigMapper::load();
		$cmd = 'storescu +tla -ic --timeout 10 -aet '.escapeshellarg($AETitle).' --propose-j2k-lossless +sd -nh '.escapeshellarg($host).' '.escapeshellarg($port);                
		$valid = false;
		
		// go thru each file in 'compressed' folder and find out if it is being accessed by other processes
		// an image file may be in the process of compression
		try {
			foreach (glob($dir . '/*') as $zFile) {

				if (DICOM_Util::isFileLocked($zFile)) {
					continue;
				}						
				$cmd = $cmd . ' ' . $zFile;
				$valid = true;
			}
		} 
		catch (Exception $ex) {					
			// log exception some how
		}
		if ($valid) {
			return $cmd;
		}
		// return empty if there are problems
		return '';
	}

}