<?php

$id = "5993f2e6";//$_GET['id'];
//print($ggg);
//if($ggg){
//    echo "dfdfd";
//}
require_once("src/ClientbaseAPI.php");


    //Укажите URL вашей "Клиентской Базы"
    $url = 'https://crm149992.clientbase.ru';

    //Укажите токен, который вы создали для работы с API в настройках "Клиентской Базы"
    $token = "PclOu9GzdkwyPO4e1wCLwX3vKqCmdq4Heaoul7YMPgC2uPIX";

    //Создаем объект для работы с API
    $cbAPI = new ClientbaseAPI($url, $token);
    $tableId = 980;
    $filter = array('f17940' => 1, 'status' => 0); // state is to send

    //getDataList(int $tableId, int $offset=0, int $limit=0, mixed $filter='') - получить список записей таблицы c ограничением количества и выборкой по фильтру *$tableId ID таблицы *$offset Отступ от начала списка *$limit Количество результатов выборки *$filter Условие выборки в формате массива или строки
    $aData = $cbAPI->getDataList($tableId, 0, 0, $filter);
    //print_r($aData);
    //$aData = array_shift($aData);
    //$aData = $aData->attributes->f18000;
    $arReceivers=[];

    foreach ($aData as $key => $value) {
      $tgids = json_decode($value->attributes->f18000);
      $rowid = strval($value->id);

      if (is_array($tgids)) {
        if (count($tgids) > 0) {
          //print_r($tgids);
          foreach($tgids as $tkey => $tgid) {
            //print($tgid);
            $arReceivers[] = ["tgid" => $tgid, 'report' => $value->attributes->f17840];
          }
        }
      }
      $data = array('f17940' => 2);

      $aData = $cbAPI->updateData($tableId, $rowid, $data);

    }

//test from user $url = "https://api.telegram.org/bot27784843:be6d6f7387304fd375db22c6088ec411";

$url = "https://api.telegram.org/bot5387320064:AAHkEKZdjZoohYssufLH3GDOl8m0r24N9nQ";
$command = "/sendMessage";
//test $params = "?chat_id=5246525120&text=tests";

foreach($arReceivers as $key => $value) {

  $text = 'Здравствуйте. Отчет по прогрессу ребенка можно просмотреть по <a href="http://91.239.26.167/report_childprogress.php?id='.$value['tgid'].'">';
  $params = "?chat_id=".$value['tgid']."&text=".$text;

  $request = $url.$command;

  if ($params) $request .= $params;

  //print($request);

    if( $curl = curl_init() ) {
      curl_setopt($curl, CURLOPT_URL, $request);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
      $out = curl_exec($curl);
      echo $out;
      curl_close($curl);
    }

    $log = date('Y-m-d H:i:s').$value['tgid'].' '.$text;
    file_put_contents(__DIR__ . '/recs', $log . PHP_EOL, FILE_APPEND);

}


?>
