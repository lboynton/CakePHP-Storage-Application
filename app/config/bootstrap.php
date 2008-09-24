<?php
/* SVN FILE: $Id: bootstrap.php 6311 2008-01-02 06:33:52Z phpnut $ */
/**
 * Short description for file.
 *
 * Long description for file
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2008, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Cake Software Foundation, Inc.
 * @link				http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package			cake
 * @subpackage		cake.app.config
 * @since			CakePHP(tm) v 0.10.8.2117
 * @version			$Revision: 6311 $
 * @modifiedby		$LastChangedBy: phpnut $
 * @lastmodified	$Date: 2008-01-01 22:33:52 -0800 (Tue, 01 Jan 2008) $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 *
 * This file is loaded automatically by the app/webroot/index.php file after the core bootstrap.php is loaded
 * This is an application wide file to load any function that is not used within a class define.
 * You can also use this to include or require any files in your application.
 *
 */
/**
 * The settings below can be used to set additional paths to models, views and controllers.
 * This is related to Ticket #470 (https://trac.cakephp.org/ticket/470)
 *
 * $modelPaths = array('full path to models', 'second full path to models', 'etc...');
 * $viewPaths = array('this path to views', 'second full path to views', 'etc...');
 * $controllerPaths = array('this path to controllers', 'second full path to controllers', 'etc...');
 *
 */
define('BACKUP_ROOT_DIR', $_SERVER['DOCUMENT_ROOT'] . DS . 'backups' . DS);

/**
 * Function for getting the file extension of a filename
 * @param filename The name of the file with the extension
 * @return The extension of the filename
 */
function getFileExtension($filename, $keepDot = false)
{
	$filename = strtolower($filename);
	$exts = split("[/\\.]", $filename);
	$n = count($exts)-1;
	if($n == 0) return '';
	$exts = $exts[$n];
	if($keepDot) $exts = '.' . $exts;
	return $exts;
}

function stripFileExtension($filename, $extension)
{
	if(strlen($extension) == 0) return $filename;

	// check if the dot in the extension is present, if so remove it
	if(substr($extension, 0, 1) == '.')
	{
		return substr($filename, 0, strlen($filename) - strlen($extension));
	}
	else
	{
		return substr($filename, 0, strlen($filename) - strlen($extension) - 1);
	}
}

/**
 * @description Remove recursively. (Like `rm -r`)
 * @see Comment by davedx at gmail dot com on { http://us2.php.net/manual/en/function.rmdir.php }
 * @param file {String} The file or folder to be deleted.
 **/
function rmRecursive($file) 
{
	if (is_dir($file) && !is_link($file)) 
	{
		foreach(glob($file.'/*') as $sf) 
		{
			if ( !$this->rmRecursive($sf) ) 
			{
				$this->log("Failed to remove $sf\n");
				return false;
			}
		}
		return rmdir($file);
	} 
	else 
	{
		return unlink($file);
	}
}

/**
 * Checks if the supplied filename is that of a directory by checking if the last character of the filename is a forward or back slash
 * @return True if the supplied filename looks like that of a directory, false otherwise
 */
function isDirectoryName($filename)
{
	$lastCharacter = substr($filename, strlen($filename) - 1, strlen($filename));
	
	return $lastCharacter == '\\' || $lastCharacter == '/';
}

/**
 * Gets the directory separator that is used in the given filename.
 * @return Either a back slash or forward slash if either are present in the filename. Otherwise, returns the CakePHP constant DS.
 */
function getDirectorySeparator($filename)
{
	if(strstr($filename, '\\')) return '\\';
	if(strstr($filename, '/')) return '/';
	return DS;
}
?>