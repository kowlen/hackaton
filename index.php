<?php
  error_reporting(E_ALL);
  ini_set('error_reporting', E_ALL);
  ini_set("display_errors", 1);
  define('TMVC_ERROR_HANDLING',1);
  if(!defined('DS'))
    define('DS',DIRECTORY_SEPARATOR);
  if(!defined('TMVC_BASEDIR'))
    define('TMVC_BASEDIR',dirname(__FILE__) . DS . 'tinymvc' . DS);
  require(TMVC_BASEDIR . 'sysfiles' . DS . 'TinyMVC.php');
  session_start();
  $modules = array();
  $tmvc = new tmvc();
  $tmvc = tmvc::instance();
  $tmvc->main();
?>
