<?php
/**
 * Created by PhpStorm.
 * User: pleaz
 * Date: 07/05/2017
 * Time: 18:46
 */

require __DIR__ . '/vendor/autoload.php';

$db = new MysqliDb('localhost', 'bit24', 'Hl5snRHsCYmDs04n', 'bit24');

$inputFileName = "users.xlsx";