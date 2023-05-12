<?php
$months = array(1 => 'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь');
if ($_REQUEST['year'] && $_REQUEST['month']) {
    $month = $_REQUEST['month'];
    $year = $_REQUEST['year'];
} else {
    $month = date("m");
    $year = date("Y");
}
if ($_REQUEST['iGroupId']) {
    $iGroupId = $_REQUEST['iGroupId'];
} else {
    $iGroupId = 0;
}

$sDateFirst = date("Y-m-d", mktime(0, 0, 0, $month, 1, $year));
$date_zero = '0000-00-00 00:00:00';

$dDateNext = new DateTime($sDateFirst);
$dDateNext->modify('1 month');
$sDateNext = $dDateNext->format('Y-m-d 00:00:00');

$date_now = date("d.m.Y");

$interval = date_interval_create_from_date_string('1 day');
$daterange = new DatePeriod(new DateTime($sDateFirst), date_interval_create_from_date_string('1 day'), $dDateNext);

$dateformate = [];
$ddateformate = [];
foreach ($daterange as $dater) {
    array_push($dateformate, $dater->format('j'));
    array_push($ddateformate, $dater);
    if ($date_now == $dater->format('d.m.Y'))
        break;
}

//print_r($dateformate);

$sSqlQueryGroup = "SELECT DISTINCT
    Groups.id AS GroupId
    , Groups.f11090 AS GroupName
    FROM
        " . DATA_TABLE . get_table_id(720) . " AS Students
        INNER JOIN " . DATA_TABLE . get_table_id(700) . " AS Groups
            ON Students.f11570 = Groups.id
    WHERE
    ((Groups.f11240 <'" . $sDateNext . "' AND Groups.f11240 <>'" . $date_zero . "' AND (Groups.f11270 IS NULL OR Groups.f11270 = '" . $date_zero . "'))
        OR (Groups.f11240 <'" . $sDateNext . "' AND Groups.f11240 <>'" . $date_zero . "' AND Groups.f11270 >= '" . $sDateFirst . "' AND Groups.f11270 <> '" . $date_zero . "' AND NOT Groups.f11270 IS NULL))
        AND Groups.`status` = 0
    ORDER BY
    GroupName";

$vGroupData = sql_query($sSqlQueryGroup);
$vGroups = [];

while ($vGroupRow = sql_fetch_assoc($vGroupData)) {
    if ($iGroupId == 0)
        $iGroupId = $vGroupRow['GroupId'];
    $vTableData['GroupId'] = $vGroupRow['GroupId'];
    $vTableData['GroupName'] = form_display($vGroupRow['GroupName']);
    $vGroups[] = $vTableData;
}

$vLines = [];

$sSqlQueryStudents = "SELECT ClienCards.id as StudentId
     , ClienCards.f9750 as StudentFIO
 FROM
    " . DATA_TABLE . get_table_id(720) . " AS Students
        INNER JOIN " . DATA_TABLE . get_table_id(530) . " AS ClienCards
            ON Students.f11460 = ClienCards.id
     WHERE
         Students.f11570 = '". $iGroupId."'
         AND ((Students.f11580 <'" . $sDateNext . "' AND Students.f11590 IS NULL AND Students.f11580 <>'" . $date_zero . "')
            OR (Students.f11580 <'" . $sDateNext . "' AND Students.f11590 = '" . $date_zero . "' AND Students.f11580 <>'" . $date_zero . "')
            OR (Students.f11580 <>'" . $date_zero . "' AND Students.f11580 <'" . $sDateNext . "' AND Students.f11590 >= '" . $sDateFirst . "' AND Students.f11590 <>'" . $date_zero . "'))
         AND Students.`status` = 0
     ORDER BY StudentFIO";
$vStudentsData = sql_query($sSqlQueryStudents);
while ($vStudentRow = sql_fetch_assoc($vStudentsData)) {
    $vTableData['StudentId'] = $vStudentRow['StudentId'];
    $vTableData['StudentFIO'] = form_display($vStudentRow['StudentFIO']);
    $vLines[] = $vTableData;
}

//$aDays = array('Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота');


//foreach ($daterange as $ondate) {
//    $sSqlQueryGroup = "SELECT
//        Groups.id AS GroupId
//        , Groups.f11090 AS GroupName
//     FROM
//        " . DATA_TABLE . get_table_id(700) . " AS Groups
//     WHERE
//        ((Groups.f11240 <'" . $sDateNext . "' AND Groups.f11240 <>'" . $date_zero . "' AND (Groups.f11270 IS NULL OR Groups.f11270 = '" . $date_zero . "'))
//            OR (Groups.f11240 <'" . $sDateNext . "' AND Groups.f11240 <>'" . $date_zero . "' AND Groups.f11270 >= '" . $sDateFirst . "' AND Groups.f11270 <> '" . $date_zero . "' AND NOT Groups.f11270 IS NULL))
//         AND Groups.`status` = 0
//     ORDER BY
//        GroupName";

//    $vGroupData = sql_query($sSqlQueryGroup);

//    while ($vGroupRow = sql_fetch_assoc($vGroupData)) {
//        // $sSqlQueryTest = "SELECT
//        // Students.f11460 AS ID
//        // FROM
//        // ".DATA_TABLE.get_table_id(720)." AS Students
//        // WHERE
//        // Students.f11480= '287'
//        // AND ((Students.f11580 <='".$datesearch."' AND Students.f11590 IS NULL)
//        // OR (Students.f11580 <='".$datesearch."' AND Students.f11590 = '".$date_zero."')
//        // OR (Students.f11580 <='".$datesearch."' AND Students.f11590 >= '".$datesearch."' AND Students.f11590 <> '".$date_zero."' AND NOT Students.f11590 IS NULL ))
//        // AND Students.`status` = 0";
//        // $vTestData = sql_query($sSqlQueryTest);
//        // while ($vTestRow = sql_fetch_assoc($vTestData )) {
//        // echo "___---".$vTestRow ['ID'] ."____<br>";
//        // }

//        //        echo $aDays[date("w", $ondate)];
//        // Проверка на день недели
//        $sSqlQueryWeekDay = "SELECT
//            Schedule.id
//            , Schedule.f13560 AS fff
//         FROM
//            " . DATA_TABLE . get_table_id(790) . " AS Schedule
//                INNER JOIN " . DATA_TABLE . get_table_id(600) . " AS DaysWeek
//                    ON Schedule.f13560 = DaysWeek.id
//         WHERE
//            Schedule.f13550 = '" . $vGroupRow['GroupId'] . "'
//            AND DaysWeek.f10330 = '" . $aDays[date("w", strtotime($datesearch))] . "'
//             AND Schedule.`status` = 0
//             AND DaysWeek.`status` = 0";

//        if ($vResultWeekDay = sql_query($sSqlQueryWeekDay)) {

//            if ($vRowWeekDay = sql_fetch_assoc($vResultWeekDay)) {
//                //echo $sSqlQueryWeekDay."---".$vRowWeekDay['fff'] ."____<br>";
//                $sGroupKey = "g" . $vGroupRow['GroupId'];

//                if (array_key_exists($sGroupKey, $vLines)) {
//                    $vTableData = $vLines[$sGroupKey];
//                } else {
//                    $vTableData['GroupId'] = $vGroupRow['GroupId'];
//                    $vTableData['GroupKey'] = $sGroupKey;
//                    $vTableData['GroupName'] = form_display($vGroupRow['GroupName']);
//                    $vTableData['ChildrenInGroup'] = $vGroupRow['ChildrenInGroup'];
//                    $vTableData['MaxChildrenInGroup'] = $vGroupRow['MaxChildrenInGroup'];
//                    $vTableData['WorkingOff'] = [];
//                }

//                $iWorkingOff = 0;

//                if ($result2 = data_select_field(820, "COUNT(*) AS WorkingOffCount", "f14960='", $vGroupRow['GroupId'], "' AND f15070='", $datesearch, "' AND status='0'")) {
//                    if ($row2 = sql_fetch_assoc($result2)) {
//                        $iWorkingOff = $row2['WorkingOffCount'];
//                    }
//                }

//                $sSqlQueryMisses = "SELECT IFNULL(COUNT(*), 0) AS MissesCount
//                 FROM
//                    " . DATA_TABLE . get_table_id(820) . " AS WorkingOff
//                 WHERE
//                     WorkingOff.f14920= '" . $vGroupRow['GroupId'] . "'
//                    AND WorkingOff.f14890 IN (
//                        SELECT
//                            Students.f11460
//                         FROM
//                            " . DATA_TABLE . get_table_id(720) . " AS Students
//                         WHERE
//                             Students.f11480= '" . $vGroupRow['GroupId'] . "'
//                             AND ((Students.f11580 <='" . $datesearch . "' AND Students.f11590 IS NULL)
//                                OR (Students.f11580 <='" . $datesearch . "' AND Students.f11590 = '" . $date_zero . "')
//                                OR (Students.f11580 <='" . $datesearch . "' AND Students.f11590 >= '" . $datesearch . "' AND Students.f11590 <> '" . $date_zero . "' AND NOT Students.f11590 IS NULL ))
//                             AND Students.`status` = 0
//                         )
//                      AND WorkingOff.f14900 = '" . $datesearch . "'
//                      AND WorkingOff.`status` = 0";
//                if ($vResultMisses = sql_query($sSqlQueryMisses)) {
//                    if ($vRowMisses = sql_fetch_assoc($vResultMisses)) {
//                        //echo $vGroupRow['GroupId']."___".$iWorkingOff."---".$vRowMisses['MissesCount']."---<br>";
//                        $iWorkingOff = $iWorkingOff - $vRowMisses['MissesCount'];
//                    }
//                }


//                $vTableData['WorkingOff'][$ondate->format('d.m.y')] = $iWorkingOff;
//                //echo $sGroupKey."---".$ondate->format('d.m.y')."--".$vTableData['WorkingOff'][$ondate->format('d.m.y')]."<br>";
//                $vLines[$sGroupKey] = $vTableData;
//            }
//        }
//    }

//}

$result = sql_select_field("" . SCHEMES_TABLE . "", "color3", "active='1'");
$row = sql_fetch_assoc($result);
$smarty->assign("color3", $row['color3']);
$smarty->assign("date1", $date1);
$smarty->assign("months", $months);
$smarty->assign("month", $month);
$smarty->assign("year", $year);
$smarty->assign("vGroups", $vGroups);
$smarty->assign("iGroupId", $iGroupId);

$smarty->assign("lines", $vLines);
$smarty->assign("dateformate", $dateformate);