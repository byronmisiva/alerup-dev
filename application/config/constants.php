<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/* End of file constants.php */
/* Location: ./application/config/constants.php */

/* SAMSUNG SANTA APP MENSAJES */
define('SANTA_MESSAGE', ' envió su lista de regalos a Santa y está participando por un');
define('SANTA_LINK', 'https://apps.facebook.com/samsung_santa');
define('SANTA_PICTURE', 'http://appss.misiva.com.ec/imagenes/samsung_santa/75x75.jpg?referhs=123456789');
define('SANTA_NAME', 'La Lista de Santa');
define('SANTA_DESCRIPTION', 'Esta Navidad envía tu lista de regalos a Santa para participar por el sorteo de una GALAXY Tab 2 7.0, un GALAXY S4 Zoom o una GALAXY Note 8.0');
define('SANTA_TITLE', 'La Lista de Santa');
