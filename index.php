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

        // "host" => "nickel.f2logistics.com.ph",
        // "username" => "f2csuser",
        // "password" => "123454321",
        // "dbname" => "f2nt",

       
    ));
});

$di->set('db_f2nt_itd', function () {
    return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
        "host" => "localhost",
        "username" => "root",
        "password" => "",
        "dbname" => "f2nt_itd"

        // "host" => "nickel.f2logistics.com.ph",
        // "username" => "f2csuser",
        // "password" => "123454321",
        // "dbname" => "f2nt_itd"
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

    if($user){
        echo json_encode($user->toArray());
    }else{
        echo json_encode(array('message'=>'User not found'));
    }

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



// Testing purposes ; you can delete it any time.
$app->get('/api/get_user', function () use ($app, $response) {

    $username = $app->request->get('username');

    $user = Users::findFirst("username = '{$username}'");

    echo json_encode($user);

});

$app->post('/api/create_user', function() use ($app, $response){

    $user = new Users();

    $user->username = "30-000";
    $user->password = md5("12345678");
    $user->email = "juan.delacruz@mail.com";
    $user->firstname = "Juan";
    $user->lastname = "Dela Cruz";

    $user->active = 1;
    $user->business_unit = "SS";
    $user->location = "MNL";
    $user->department = "IT";
    $user->rank = "STAFF";
    $user->position = "IT HELPDESK";

    
    if($user->save()){
        // Prompt for successful insert to db
        echo "User created";
    }else{
        // Display all error messages if any
        foreach($user->getMessages() as $message){
            echo $message . "<br>";
        }
    };

    


});

$app->post('/api/update_user', function () use ($app, $response) {

    $username = $app->request->getPost('username');
    
    $user = Users::findFirst("username = '{$username}'");

    $user->business_unit = $app->request->getPost('bu');
    $user->location = $app->request->getPost('loc');
    $user->department = $app->request->getPost('dept');


    if ($user->save()) {
        // Prompt for successful insert to db
        echo "User updated";
    } else {
        // Display all error messages if any
        foreach ($user->getMessages() as $message) {
            echo $message . "<br>";
        }
    };
});

$app->delete('/api/delete_user/{id}', function ($id) use ($app, $response) {
    
    $user = Users::findFirst("username = '{$id}'");

    if ($user->delete()) {
        // Prompt for successful insert to db
        echo "User deleted";
    } else {
        // Display all error messages if any
        foreach ($user->getMessages() as $message) {
            echo $message . "<br>";
        }
    };
});




$app->handle();


