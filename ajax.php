<?php
/**
 * Created by PhpStorm.
 * User: pleaz
 * Date: 07/05/2017
 * Time: 18:47
 */

require_once ('init.php');

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

if(!empty($_POST)) {

    if(!empty($_POST['q'])){

        $q = $db->escape($_POST['q']);
        if(strlen($q)<9) die('введите больше 9 символов');
        $gg = $db->where ('phone', '%'.$q.'%', 'like');
        $results = $db->get ('users');
        if($results==null) die('номер телефона не найден');
        $qu = str_replace('+', '', $q);
        header('Content-Type: application/json');
        echo json_encode(['tel' => $qu, 'st' => true]);
        die();

    }

    if(!empty($_POST['k'])) {

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
        $obB24App->setAccessToken('sc1fz2ytzjucvhj5fuwqqrm0mmu85aqk');
        $obB24App->setRefreshToken('e0pjs9hxo5bli9x01q6dmc71duik1yb1');

        $obB24App->setRedirectUri('http://main.local');

        // get information about current user from bitrix24
        //$obB24User = new \Bitrix24\User\User($obB24App);
        //$arCurrentB24User = $obB24User->current();

        if ($obB24App->isAccessTokenExpire() === true) {
            $obB24Arr = $obB24App->getNewAccessToken();
            $obB24App->setAccessToken($obB24Arr['access_token']);
            $obB24App->setRefreshToken($obB24Arr['refresh_token']);
        };


        $lead = new \Bitrix24\CRM\Lead($obB24App);
        $lead->add([
            'TITLE' => 'Возврат клиента',
            'NAME' => $_POST['n'],
            'COMMENTS' => $_POST['k'],
            'PHONE' => [['VALUE' => $_POST['p'], 'VALUE_TYPE' => 'WORK']],
            'STATUS_ID' => 'NEW'
        ], ['REGISTER_SONET_EVENT' => 'Y']);

        $arr = json_decode($obB24App->getRawResponse());
        if(is_numeric($arr->result)===true) {
            $db->where ('id', $db->escape($_POST['i']));
            $results = $db->get('users');
            $db->where ('id', $results['0']['id']);
            $db->update ('users', ['form_count' => $results['0']['form_count']+1]);
        }

        //$sms = new Jurager\Sender\Sender();
        //$gg = $sms->sendOne('321321', 'dsdsadasdsd ds das');
        //print_r($gg);


        header('Content-Type: application/json');
        echo $obB24App->getRawResponse();
        die();

    }

}

// https://b24-n590e10d6722a3.bitrix24.com/oauth/authorize/?response_type=code&client_id=local.590e600bacb1f2.81971446&redirect_uri=http://main.local

// https://b24-n590e10d6722a3.bitrix24.com/oauth/token/?grant_type=authorization_code&client_id=local.590e600bacb1f2.81971446&client_secret=2MgOovr9hgknm7gfkD1pQZRdC2P52AojDv2TbM8cZZfGng7yOt&redirect_uri=http://main.local&scope=crm,user&code=dedckhhl2fpxqeiymyrt5pvmjzzst7xb

// {"access_token":"sc1fz2ytzjucvhj5fuwqqrm0mmu85aqk","expires_in":3600,"scope":"crm,user","domain":"b24-n590e10d6722a3.bitrix24.com","server_endpoint":"https:\/\/oauth.bitrix.info\/rest\/","status":"L","client_endpoint":"https:\/\/b24-n590e10d6722a3.bitrix24.com\/rest\/","member_id":"7c2338b5e537bae48c8a2fccd59cb36c","user_id":"1","refresh_token":"e0pjs9hxo5bli9x01q6dmc71duik1yb1"}