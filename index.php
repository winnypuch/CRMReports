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

//���������� ����� ��� ������ � API
require_once "ClientbaseAPI.php";

//������� URL ����� "���������� ����"
$url = 'https://crm149992.clientbase.ru';

//������� �����, ������� �� ������� ��� ������ � API � ���������� "���������� ����"
$token = "UqQRUXoE6sLwNsZeoAD1w4mvToIsFhcd8DLMXv37yxrJiYr0";

//������� ������ ��� ������ � API
//$cbAPI = new ClientbaseAPI($url, $token);
//$cbAPI->getDataList()

//print_r($_REQUEST);
//echo phpinfo();

echo "4";
