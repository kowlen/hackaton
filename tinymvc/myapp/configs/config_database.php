<?php

/**
 * database.php
 *
 * application database configuration
 *
 * @package		TinyMVC
 * @author		Monte Ohrt
 */

//$config['default']['plugin'] = 'TinyMVC_PDO'; // plugin for db access
$config['default']['plugin'] = ''; // plugin for db access
//$config['default']['type'] = 'mysql';      // connection type
$config['default']['type'] = '';      // connection type
//$config['default']['host'] = 'localhost';  // db hostname
$config['default']['host'] = '';  // db hostname
$config['default']['name'] = '';     // db name
$config['default']['user'] = '';     // db username
$config['default']['pass'] = '';     // db password
$config['default']['persistent'] = false;  // db connection persistence?

?>