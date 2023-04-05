<?php
    define('protocol', 'http://');
    define('ver', 'Ver.0.0.1');
    date_default_timezone_set('Asia/Chita');
    define('root_dir',protocol.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT']);
    define('developer', 0);
    define('start', microtime(true));

    //db config
    define ( 'db_user', "root");
    define ( 'db_pass', "0000" );
    define ( 'db_server', "localhost");
    define ( 'db_db', "fotosalon");

?>