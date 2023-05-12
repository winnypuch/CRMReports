<?php
if ($_REQUEST['date1'])
    $date1 = date("d.m.Y", strtotime(form_eng_time($_REQUEST['date1'])));
else
    $date1 = date("d.m.Y", mktime(0, 0, 0, date("m"), date("d") - 7, date("Y")));

if ($_REQUEST['date2'])
    $date2 = date("d.m.Y", strtotime(form_eng_time($_REQUEST['date2'])));
else
    $date2 = date("d.m.Y");

$date_zero = '0000-00-00 00:00:00';
$date_now = form_eng_time(date("d.m.Y", mktime(0, 0, 0, date("m"), date("d"), date("Y"))). ' 00:00:00');



$interval = date_interval_create_from_date_string('1 day');
$daterange = new DatePeriod(new DateTime($date1), date_interval_create_from_date_string('1 day'), date_add(new DateTime($date2), $interval));

$dateformate = [];
foreach ($daterange as $dater) {
    array_push($dateformate, $dater->format('d.m.y'));
}

$aDays = array('Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота');
$vLines =[];

foreach ($daterange as $ondate) {
    $datesearch = $ondate->format('Y-m-d 00:00:00');
    $sSqlQueryGroup = "SELECT
        Groups.id AS GroupId
        , Groups.f11090 AS GroupName
        , Groups.f11130 AS ChildrenInGroup
         , Groups.f16300 AS MaxChildrenInGroup
     FROM
        ".DATA_TABLE.get_table_id(700)." AS Groups
            INNER JOIN ".DATA_TABLE.get_table_id(790)." AS Schedule
                ON Groups.Id= Schedule.f13550
     WHERE
         Schedule.f13580 = 'онлайн'
        AND ((Groups.f11240 <='".$datesearch ."' AND Groups.f11240 <>'".$date_zero."' AND (Groups.f11270 IS NULL OR Groups.f11270 = '".$date_zero."'))
            OR (Groups.f11240 <='".$datesearch ."' AND Groups.f11240 <>'".$date_zero."' AND Groups.f11270 >= '".$datesearch."' AND Groups.f11270 <> '".$date_zero."' AND NOT Groups.f11270 IS NULL))
         AND Groups.`status` = 0
     ORDER BY
        GroupName";

    $vGroupData = sql_query($sSqlQueryGroup);

    while ($vGroupRow = sql_fetch_assoc($vGroupData)) {
                              // $sSqlQueryTest = "SELECT
                            // Students.f11460 AS ID
                         // FROM
                            // ".DATA_TABLE.get_table_id(720)." AS Students
                         // WHERE
                             // Students.f11480= '287'
                             // AND ((Students.f11580 <='".$datesearch."' AND Students.f11590 IS NULL)
                                // OR (Students.f11580 <='".$datesearch."' AND Students.f11590 = '".$date_zero."')
                                // OR (Students.f11580 <='".$datesearch."' AND Students.f11590 >= '".$datesearch."' AND Students.f11590 <> '".$date_zero."' AND NOT Students.f11590 IS NULL ))
                             // AND Students.`status` = 0";
                             // $vTestData = sql_query($sSqlQueryTest);
                             // while ($vTestRow = sql_fetch_assoc($vTestData )) {
                                 // echo "___---".$vTestRow ['ID'] ."____<br>";
                             // }

//        echo $aDays[date("w", $ondate)];
        // Проверка на день недели
        $sSqlQueryWeekDay = "SELECT
            Schedule.id
            , Schedule.f13560 AS fff
         FROM
            ".DATA_TABLE.get_table_id(790)." AS Schedule
                INNER JOIN ".DATA_TABLE.get_table_id(600)." AS DaysWeek
                    ON Schedule.f13560 = DaysWeek.id
         WHERE
            Schedule.f13550 = '".$vGroupRow['GroupId']."'
            AND DaysWeek.f10330 = '".$aDays[date("w", strtotime($datesearch))]."'
             AND Schedule.`status` = 0
             AND DaysWeek.`status` = 0";

        if($vResultWeekDay = sql_query($sSqlQueryWeekDay)) {

            if($vRowWeekDay = sql_fetch_assoc($vResultWeekDay)){
                //echo $sSqlQueryWeekDay."---".$vRowWeekDay['fff'] ."____<br>";
                $sGroupKey = "g".$vGroupRow['GroupId'];

                if (array_key_exists($sGroupKey, $vLines)) {
                    $vTableData = $vLines[$sGroupKey];
                } else {
                    $vTableData['GroupId'] = $vGroupRow['GroupId'];
                    $vTableData['GroupKey'] = $sGroupKey;
                    $vTableData['GroupName'] = form_display($vGroupRow['GroupName']);
                    $vTableData['ChildrenInGroup'] = $vGroupRow['ChildrenInGroup'];
                    $vTableData['MaxChildrenInGroup'] = $vGroupRow['MaxChildrenInGroup'];
                    $vTableData['WorkingOff'] = [];
                }

                $iWorkingOff = 0;

                if($result2 = data_select_field(820, "COUNT(*) AS WorkingOffCount", "f14960='", $vGroupRow['GroupId'], "' AND f15070='", $datesearch, "' AND status='0'")) {
                    if($row2 = sql_fetch_assoc($result2)){
                        $iWorkingOff = $row2['WorkingOffCount'];
                    }
                }

                $sSqlQueryMisses = "SELECT IFNULL(COUNT(*), 0) AS MissesCount
                 FROM
                    ".DATA_TABLE.get_table_id(820)." AS WorkingOff
                 WHERE
                     WorkingOff.f14920= '".$vGroupRow['GroupId']."'
                    AND WorkingOff.f14890 IN (
                        SELECT
                            Students.f11460
                         FROM
                            ".DATA_TABLE.get_table_id(720)." AS Students
                         WHERE
                             Students.f11480= '".$vGroupRow['GroupId']."'
                             AND ((Students.f11580 <='".$datesearch."' AND Students.f11590 IS NULL)
                                OR (Students.f11580 <='".$datesearch."' AND Students.f11590 = '".$date_zero."')
                                OR (Students.f11580 <='".$datesearch."' AND Students.f11590 >= '".$datesearch."' AND Students.f11590 <> '".$date_zero."' AND NOT Students.f11590 IS NULL ))
                             AND Students.`status` = 0
                         )
                      AND WorkingOff.f14900 = '".$datesearch."'
                      AND WorkingOff.`status` = 0";
                if($vResultMisses = sql_query($sSqlQueryMisses )) {
                    if($vRowMisses = sql_fetch_assoc($vResultMisses)){
                    //echo $vGroupRow['GroupId']."___".$iWorkingOff."---".$vRowMisses['MissesCount']."---<br>";
                       $iWorkingOff = $iWorkingOff - $vRowMisses ['MissesCount'];
                    }
                }


                $vTableData['WorkingOff'][$ondate->format('d.m.y')] =  $iWorkingOff;
                //echo $sGroupKey."---".$ondate->format('d.m.y')."--".$vTableData['WorkingOff'][$ondate->format('d.m.y')]."<br>";
                $vLines[$sGroupKey] = $vTableData;
            }
        }
    }

}
//echo var_dump($vLines);
//print_r($vLines);
$result = sql_select_field("" . SCHEMES_TABLE . "", "color3", "active='1'");
$row = sql_fetch_assoc($result);
$smarty->assign("color3", $row['color3']);
$smarty->assign("date1", $date1);
$smarty->assign("date2", $date2);
$smarty->assign("lines", $vLines);
$smarty->assign("dateformate", $dateformate);