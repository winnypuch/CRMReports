<?php

$pp = 2;
$pp++;
echo $pp;
$dStartDate = new DateTime("2023-10-21");

$dSearchDate = clone $dStartDate;

    $dSearchDate->modify('next tuesday');
    $dCheckDate = clone $dStartDate;
    $dCheckDate->modify('next friday');
    if($dCheckDate < $dSearchDate) {
        $dSearchDate = clone $dCheckDate;
    }

//Подключаем класс для работы с API
require_once "ClientbaseAPI.php";

//Укажите URL вашей "Клиентской Базы"
$url = 'https://crm149992.clientbase.ru';

//Укажите токен, который вы создали для работы с API в настройках "Клиентской Базы"
$token = "UqQRUXoE6sLwNsZeoAD1w4mvToIsFhcd8DLMXv37yxrJiYr0";

//Создаем объект для работы с API
//$cbAPI = new ClientbaseAPI($url, $token);
//$cbAPI->getDataList()

//print_r($_REQUEST);
//echo phpinfo();

echo "4";
