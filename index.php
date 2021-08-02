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
        "host" => "nickel.f2logistics.com.ph",
        "username" => "f2csuser",
        "password" => "123454321",
        "dbname" => "f2nt"
    ));
});

$di->set('db_f2nt_itd', function () {
    return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
        // "host" => "localhost",
        // "username" => "root",
        // "password" => "",
        // "dbname" => "test"

        "host" => "nickel.f2logistics.com.ph",
        "username" => "f2csuser",
        "password" => "123454321",
        "dbname" => "f2nt_itd"
    ));
});


// Response variable
$response = array();
// Dependecy Injector (App)
$app = new Micro($di);


/*
 * Routes definitions
 */
$app->post('/api/login', function() use ($app, $response) {

    // Get data from user input
    $username = $app->request->getPost('username');
    $password = md5($app->request->getPost('password'));

    // Search account in database based from user input
    $user = Users::findFirst("username = '{$username}' AND password = '{$password}'");


    $escalated = Tickets::findFirst("escalated_to = '{$username}'");
    $created = Tickets::find("created_by = '{$username}'");

    if($user){
        $response['user'] = $user->toArray();

        $response['assigned_tickets'] = array();

        $tickets = $user->assigned_tickets;
        foreach ($tickets as $ticket) {
            $response['assigned_tickets'][$ticket->ticket_no]= $ticket->toArray();
        }
        echo json_encode($response);
    }
    else{
        echo json_encode(array('message'=>'User not found'));
    }

    // foreach($users as $user){
    //     echo "{$user->firstname} {$user->lastname}<br>";
    // }
});


$app->get('/', function () {
    require_once 'index.html';
});

$app->get('/tickets', function () {
    require 'template/head.html';
    require 'tickets.html';
});

$app->get('/api/tickets', function () use ($app, $response) {
    header('Content-Type: application/json');

    $tickets = Tickets::find('escalated_to = "19-779"');
    foreach($tickets as $ticket){
        $tix_arr = array(
            $ticket->ticket_no,
            $ticket->email_subject,
            $ticket->issue_name,
            $ticket->category,
            $ticket->originator,
            $ticket->severity,
            $ticket->status,
            $ticket->escalated_to,
            $ticket->date_escalated,
            $ticket->date_closed,
        );
        $response['tickets'][] = $tix_arr;
    }

    echo json_encode($response);
});




$app->handle();
