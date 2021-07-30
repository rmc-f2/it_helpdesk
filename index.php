<?php
// Comment ni Sir R-Jay
// Joren please do this task
// Todo:
// If logged in user is an ITD, get all assigned tickets
// Else get all created tickets

require_once 'models/Users.php';
require_once 'models/Tickets.php';

use Phalcon\Loader;
use Phalcon\Mvc\Micro;

$loader = new Loader();

$di = new \Phalcon\DI\FactoryDefault();

$di->set('response',function(){
    $response = new \Phalcon\Http\Response();
    $response->setHeader('Access-Control-Allow-Origin', '*');
});

/**
 * Database connection settings
 */
$di->set('db_f2nt', function () {
    return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
        "host" => "localhost",
        "username" => "root",
        "password" => "",
        "dbname" => "f2nt"
    ));
});

$di->set('db_f2nt_itd', function () {
    return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
        "host" => "localhost",
        "username" => "root",
        "password" => "",
        "dbname" => "f2nt_itd"
    ));
});


$app = new Micro($di);
/*
 * Routes definitions
 */

$app->post('/api/login', function() use ($app) {

    // Get data from user input
    $username = $app->request->getPost('username');
    $password = md5($app->request->getPost('password'));

    // Search account in database based from user input
    $user = Users::findFirst("username = '{$username}' AND password = '{$password}'");
    
    $tickets = $user->tickets;
    foreach($tickets as $ticket){
        var_dump($ticket->category);
    }

    
    // foreach($users as $user){
    //     echo "{$user->firstname} {$user->lastname}<br>";
    // }
    
   
});


$app->get('/', function () {

});


$app->handle();
