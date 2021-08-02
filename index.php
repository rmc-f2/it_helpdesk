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
    $escalated = Tickets::findFirst("escalated_to = '{$username}'");
    $created = Tickets::find("created_by = '{$username}'");

    if($user){
        echo "Welcome "."{$user->firstname} {$user->lastname} you have an Access! <br>";
        if ($escalated) {
            // If logged in user is an ITD, get all assigned tickets
            echo "Please resolve all this Tickets as soon as Possible. Thank you. <br>";
            $tickets = $user->open_tickets;
            foreach ($tickets as $ticket) {
                var_dump($ticket->ticket_no);
                var_dump($ticket->email_subject);
            }
        } else {
            // Else get all created tickets
            echo "Here/'s All your Ticket Request! <br>";
            echo "List Example wala pa po ako nilagay kase sa nickel po ginawa ko sample para matest nio po agad pero working po ito sir. :) <br>
            <html>
                <body>
                <form>
                <center>			
                        <table style='height:20px;  font-size:13px;'>       
                            <tr>
                                <th  style='border:1px solid black;width:200px;height:20px;font-weight:bold;' >Ticket No.</th>
                                <th  style='border:1px solid black;width:200px;height:20px;font-weight:bold;' ><strong>Email Subject</strong></th>
                                <th  style='border:1px solid black;width:200px;height:20px;font-weight:bold;' >Issue Name</th>
                                <th  style='border:1px solid black;width:200px;height:20px;font-weight:bold;' ><strong>Category</strong></th>
                                <th  style='border:1px solid black;width:200px;height:20px;font-weight:bold;' >Origin</th>
                                <th  style='border:1px solid black;width:200px;height:20px;font-weight:bold;' ><strong>Severity</strong></th>
                                <th  style='border:1px solid black;width:200px;height:20px;font-weight:bold;' >Status</th>
                                <th  style='border:1px solid black;width:200px;height:20px;font-weight:bold;' ><strong>Escalated</strong></th>
                                <th  style='border:1px solid black;width:200px;height:20px;font-weight:bold;' >Date Escalated</th>
                                <th  style='border:1px solid black;width:200px;height:20px;font-weight:bold;' ><strong>Date Closed</strong></th>
                                </tr>
                            
                    </table><br><br>

                </center>
                </form>

                        
                </body>	
            </html>";

            foreach ($user->tickets as $ticket) {
                var_dump($ticket->ticket_no);
                var_dump($ticket->email_subject);
            }

        }
    }
    else{
        echo "Sorry you don't have an Access, please register first! <br>";
        //simple linking to Login Page
        echo "<html>
            <form method='POST' action='http://localhost/it_helpdesk/index.html'>
                <input type='submit'/>
            </form>
            </html>";
    }
    

    
    // foreach($users as $user){
    //     echo "{$user->firstname} {$user->lastname}<br>";
    // }
    
   
});


$app->get('/', function () {
    require_once 'index.html';
});


$app->handle();
