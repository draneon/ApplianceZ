<?php

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

    public static function moveJPEG2000DICOMDirectoryToSCP($dir, $host, $port, $AETitle) {
        $output = self::exec('storescu +tla -ic --timeout 10 -aet '.escapeshellarg($AETitle).' --propose-j2k-lossless +sd -nh '.escapeshellarg($host).' '.escapeshellarg($port).' '.
                escapeshellarg($dir));

        preg_match_all('/E: Bad DICOM file: (.*):/', $output, $matches);
        $failures = array();
        foreach ($matches[1] as $failedFile) {
            $failures[$failedFile] = true;
        }
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

}
