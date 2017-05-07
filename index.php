<?php

// https://b24-n590e10d6722a3.bitrix24.com/oauth/authorize/?response_type=code&client_id=local.590e600bacb1f2.81971446&redirect_uri=http://main.local

// https://b24-n590e10d6722a3.bitrix24.com/oauth/token/?grant_type=authorization_code&client_id=local.590e600bacb1f2.81971446&client_secret=2MgOovr9hgknm7gfkD1pQZRdC2P52AojDv2TbM8cZZfGng7yOt&redirect_uri=http://main.local&scope=crm,user&code=2jj1qauh8wtth7j66agse01r3eh4p70p

// {"access_token":"sj4if9ltxwxptncgc94o1rd47sa6pb6a","expires_in":3600,"scope":"crm,user","domain":"b24-n590e10d6722a3.bitrix24.com","server_endpoint":"https:\/\/oauth.bitrix.info\/rest\/","status":"L","client_endpoint":"https:\/\/b24-n590e10d6722a3.bitrix24.com\/rest\/","member_id":"7c2338b5e537bae48c8a2fccd59cb36c","user_id":"1","refresh_token":"5765p9gqfwm2bykmwj3bilzywmi2im4i"}

require __DIR__ . '/vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// create a log channel
$log = new Logger('bitrix24');
$log->pushHandler(new StreamHandler('deb.log', Logger::DEBUG));


// init lib
$obB24App = new \Bitrix24\Bitrix24(false, $log);
$obB24App->setApplicationScope(array('crm', 'user'));
$obB24App->setApplicationId('local.590e600bacb1f2.81971446');
$obB24App->setApplicationSecret('2MgOovr9hgknm7gfkD1pQZRdC2P52AojDv2TbM8cZZfGng7yOt');

// set user-specific settings
$obB24App->setDomain('b24-n590e10d6722a3.bitrix24.com');
$obB24App->setMemberId('7c2338b5e537bae48c8a2fccd59cb36c');
$obB24App->setAccessToken('sj4if9ltxwxptncgc94o1rd47sa6pb6a');
$obB24App->setRefreshToken('5765p9gqfwm2bykmwj3bilzywmi2im4i');

// get information about current user from bitrix24
$obB24User = new \Bitrix24\User\User($obB24App);
$arCurrentB24User = $obB24User->current();



$lead = new \Bitrix24\CRM\Lead($obB24App);
$lead->add(
    [
    'TITLE' => 'ИП Титов',
    'NAME' => 'Глеб',
    'SECOND_NAME' => 'Егорович',
    'LAST_NAME' => 'Титов',
    'STATUS_ID' => 'NEW',
    'OPENED' => 'Y',
    'ASSIGNED_BY_ID' => '1',
    'CURRENCY_ID' => 'USD',
    'OPPORTUNITY' => '12500',
    'PHONE' => ['VALUE' => '555888', 'VALUE_TYPE' => 'WORK']
    ],
    [
        'REGISTER_SONET_EVENT' => 'Y'
    ]
);

print_r($obB24App->getRawResponse());