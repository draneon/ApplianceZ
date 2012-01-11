<?php

class Application_Model_ConfigMapperBase
{
    // TODO maybe add caching so that we don't have to load configs
    // multiple times when two pieces of code that shouldn't be passing config around
    // both have to load their own

	const CONFIG_BASE_PATH = '/home/ubuntu/bin/config';
	//const CONFIG_BASE_PATH = '/home/novarad/bin/config';

	public static function load() {
		$self = get_called_class();
		$file = $self::getFullConfigFileName();

		$values = parse_ini_file($file);
		$map = $self::getIniToObjectMap();
		$model = self::getModelName($self);
		$model = new $model();
		foreach ($values as $key => $value) {
			if (isset($map[$key])) {
				$key = $map[$key];
                $model->$key = $value;
			}			
		}

		return $model;
	}

	public static function save(Application_Model_ConfigBase $config) {
		$self = get_called_class();
		$values = $config->toArray();
		$map = $self::getObjectToIniMap();
		foreach ($values as $key => $value) {
			if (isset($map[$key])) {
				unset($values[$key]);
				$values[$map[$key]] = $value;
			}
		}
		write_ini_file($values, $self::getFullConfigFileName());
	}

	protected static function getConfigFileName() {
		throw new exception('abstract!');
	}

	protected static function getIniToObjectMap() {
		return array();
	}

	protected static function getObjectToIniMap() {
		$self = get_called_class();
		return array_flip($self::getIniToObjectMap());
	}

	protected static function getModelName($class) {
		return str_replace('Mapper', '', $class);
	}

	protected static function getFullConfigFileName() {
		$self = get_called_class();
		return self::CONFIG_BASE_PATH . '/' . $self::getConfigFileName();
	}

}

// http://stackoverflow.com/questions/1268378/create-ini-file-write-values-in-php
function write_ini_file($assoc_arr, $path, $has_sections=FALSE) { 
    $content = "";//# This file is used by bash scripts but needs to conform to PHP's 'ini' format\n";
    if ($has_sections) {
        foreach ($assoc_arr as $key=>$elem) { 
            $content .= "[".$key."]\n"; 
            foreach ($elem as $key2=>$elem2) { 
                if(is_array($elem2)) 
                { 
                    for($i=0;$i<count($elem2);$i++) 
                    { 
                        $content .= $key2."[] = \"".$elem2[$i]."\"\n"; 
                    } 
                } 
                else $content .= $key2."=\"".$elem2."\"\n"; 
            } 
        } 
    } 
    else { 
        foreach ($assoc_arr as $key=>$elem) { 
            if(is_array($elem)) 
            { 
                for($i=0;$i<count($elem);$i++) 
                { 
                    $content .= $key."[] = \"".$elem[$i]."\"\n"; 
                } 
            } 
            else $content .= $key."=\"".$elem."\"\n"; 
        } 
    } 

    return file_put_contents($path, $content) !== false;
}
