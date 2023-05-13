<?php
$months = array(1 => 'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь');
$aDays = array('Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота');
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
//f11150 Подразделение f11310 Стоимость за занятие f11320 Стоимость за месяц
//f9450 Подразделение f9580 Название школы
//f9530 Название школы f9540 ОКПО
$sSqlQueryGroup = "SELECT DISTINCT
    Groups.id AS GroupId
    , Groups.f11090 AS GroupName
    , if(Groups.f11310 IS NULL, 0, Groups.f11310) AS CostPerLesson
    , if(Groups.f11320 IS NULL, 0, Groups.f11320) AS CostPerMonth
    , if(Departments.f9450 IS NULL, '', Departments.f9450) AS DepartmentName
    , if(Schools.f9530 IS NULL, '', Schools.f9530) AS SchoolName
    , if(Schools.f9540 IS NULL, '', Schools.f9540) AS SchoolOKPO
    FROM
        " . DATA_TABLE . get_table_id(720) . " AS Students
        INNER JOIN " . DATA_TABLE . get_table_id(700) . " AS Groups
            ON Students.f11570 = Groups.id
        LEFT JOIN " . DATA_TABLE . get_table_id(500) . " AS Departments
            ON Groups.f11150 = Departments.id
        LEFT JOIN " . DATA_TABLE . get_table_id(510) . " AS Schools
            ON Departments.f9580 = Schools.id
    WHERE
    ((Groups.f11240 <'" . $sDateNext . "' AND Groups.f11240 <>'" . $date_zero . "' AND (Groups.f11270 IS NULL OR Groups.f11270 = '" . $date_zero . "'))
        OR (Groups.f11240 <'" . $sDateNext . "' AND Groups.f11240 <>'" . $date_zero . "' AND Groups.f11270 >= '" . $sDateFirst . "' AND Groups.f11270 <> '" . $date_zero . "' AND NOT Groups.f11270 IS NULL))
        AND Groups.status = 0
    ORDER BY
    GroupName";

$vGroupData = sql_query($sSqlQueryGroup);
$vGroups = [];

while ($vGroupRow = sql_fetch_assoc($vGroupData)) {
    if ($iGroupId == 0) {
        $iGroupId = $vGroupRow['GroupId'];
    }
    $vTableData['GroupId'] = $vGroupRow['GroupId'];
    $vTableData['GroupName'] = form_display($vGroupRow['GroupName']);
    $vTableData['CostPerLesson'] = $vGroupRow['CostPerLesson'];
    $vTableData['CostPerMonth'] = $vGroupRow['CostPerMonth'];
    $vTableData['DepartmentName'] = $vGroupRow['DepartmentName'];
    $vTableData['SchoolName'] = $vGroupRow['SchoolName'];
    $vTableData['SchoolOKPO'] = $vGroupRow['SchoolOKPO'];
    $vGroups['g'.$vGroupRow['GroupId']] = $vTableData;
}

$vLines = [];
//f11650 Номер счета f11540 Скидка f11580 Дата зачисления f11590 Дата отчисления
$sSqlQueryStudents = "SELECT ClienCards.id as StudentId
     , ClienCards.f9750 as StudentFIO
     , Students.f11480 as GroupFactId
     , Groups.f11090 AS GroupFactName
     , if(Students.f11650 IS NULL, '', Students.f11650) AS AccountNumber
     , if(Students.f11540 IS NULL, 0, Students.f11540) AS StudentDisc
     , if(Students.f11580 >= '".$sDateFirst."' AND Students.f11580 < '" . $sDateNext . "', Students.f11580, '') AS EnrollmentDate
     , if(Students.f11590 >= '" . $sDateFirst . "' AND Students.f11590 < '" . $sDateNext . "', Students.f11590, '') AS DeductionsDate
 FROM
    " . DATA_TABLE . get_table_id(720) . " AS Students
        INNER JOIN " . DATA_TABLE . get_table_id(530) . " AS ClienCards
            ON Students.f11460 = ClienCards.id
        INNER JOIN " . DATA_TABLE . get_table_id(700) . " AS Groups
            ON Students.f11480 = Groups.id
     WHERE
         Students.f11570 = '". $iGroupId."'
         AND ((Students.f11580 <'" . $sDateNext . "' AND Students.f11590 IS NULL AND Students.f11580 <>'" . $date_zero . "')
            OR (Students.f11580 <'" . $sDateNext . "' AND Students.f11590 = '" . $date_zero . "' AND Students.f11580 <>'" . $date_zero . "')
            OR (Students.f11580 <>'" . $date_zero . "' AND Students.f11580 <'" . $sDateNext . "' AND Students.f11590 >= '" . $sDateFirst . "' AND Students.f11590 <>'" . $date_zero . "'))
         AND Students.status = 0
     ORDER BY StudentFIO";
$vStudentsData = sql_query($sSqlQueryStudents);

while ($vStudentRow = sql_fetch_assoc($vStudentsData)) {
    //print_r($vStudentRow);Schedule.f13550 = '" . $vStudentRow['GroupFactId'] . "'
    $vTableData['StudentId'] = $vStudentRow['StudentId'];
    $vTableData['StudentFIO'] = form_display($vStudentRow['StudentFIO']);
    $vTableData['AccountNumber'] = $vStudentRow['AccountNumber'];
    $vTableData['StudentDisc'] = $vStudentRow['StudentDisc'];
    $vTableData['PayByRate'] = 0;//Плата по ставке
    $vTableData['Reson'] = "";//Причины непосещения (основание)
    $vTableData['Skipped'] = 0; //Пропущено
    $vTableData['SkippedPay'] = 0; //Пропущено оплата
    $vTableData['TotalPay'] = 0; //Всего к оплате
    $vTableData['DayData'] = [];
    $vTableData['DayColor'] = [];
    foreach ($ddateformate as $dOndate) {
        $sDateSearch = $dOndate->format('Y-m-d 00:00:00');
        $sSqlQueryWeekDay = "SELECT
            Schedule.id
            , Schedule.f13560 AS fff
            , Schedule.f13550 AS ggg
         FROM
            " . DATA_TABLE . get_table_id(790) . " AS Schedule
                INNER JOIN " . DATA_TABLE . get_table_id(600) . " AS DaysWeek
                    ON Schedule.f13560 = DaysWeek.id
         WHERE
            Schedule.f13550 = '" . $vStudentRow['GroupFactId'] . "'
            AND DaysWeek.f10330 = '" . $aDays[date("w", strtotime($sDateSearch))] . "'
             AND Schedule.status = 0
             AND DaysWeek.status = 0";
        //        $sSqlQueryWeekDay = "SELECT
//            Schedule.id as id
//            , Schedule.f13560 AS fff
//, Schedule.f13550 AS ggg
//         FROM
//            " . DATA_TABLE . get_table_id(790) . " AS Schedule
//         WHERE
//             Schedule.status = 0";
        //echo $vStudentRow['StudentFIO'] . "---" .$vStudentRow['GroupFactName']."---" . $vStudentRow['GroupFactId'] . "___". $sDateSearch. "___". $aDays[date("w", strtotime($sDateSearch))]."____<br>";
        //print_r($sSqlQueryWeekDay);
        //Schedule.f13550 = '".$vStudentRow['GroupFactId']."'
        //    AND
        // Проверка на день недели
        $sDayResult = "в";
        if ($vResultWeekDay = sql_query($sSqlQueryWeekDay)) {
            if ($vRowWeekDay = sql_fetch_assoc($vResultWeekDay)) {
                //echo "___" . $vRowWeekDay['ggg'] . "!!---" . $vRowWeekDay['fff'] . "____<br>";
                $sSqlQueryStudent = "SELECT
                    Students.f11460 as StudentId
                FROM
                " . DATA_TABLE . get_table_id(720) . " AS Students
                    WHERE
                        Students.f11570 = '" . $iGroupId . "'
                        AND Students.f11460 = '" . $vStudentRow['StudentId'] . "'
                        AND (('" . $sDateSearch . "' >=Students.f11580 AND Students.f11590 IS NULL AND Students.f11580 <>'" . $date_zero . "')
                        OR ('" . $sDateSearch . "' >=Students.f11580 AND Students.f11590 = '" . $date_zero . "' AND Students.f11580 <>'" . $date_zero . "')
                        OR (Students.f11580 <>'" . $date_zero . "' AND '" . $sDateSearch . "' >=Students.f11580 AND '" . $sDateSearch . "' <= Students.f11590 AND Students.f11590 <>'" . $date_zero . "'))
                        AND Students.status = 0";
                // Проверка Зачислен или нет
                if ($vStudentData = sql_query($sSqlQueryStudent)) {
                    if ($vRowStudentData = sql_fetch_assoc($vStudentData)) {
                        $sGroupCode = $dOndate->format('d.m.Y') . "|" . $vStudentRow['GroupFactName'] . "|" . $vStudentRow['StudentFIO'];
                        //print_r($sGroupCode);
                        //f15980 = ДатаП|ГруппаП|ФИО
                        $sSqlQueryPay = "SELECT
                            WorkingOut.id as PayId
                        FROM
                        " . DATA_TABLE . get_table_id(820) . " AS WorkingOut
                            WHERE
                                WorkingOut.f15980 = '" . $sGroupCode . "'
                                AND WorkingOut.f14890 = '" . $vStudentRow['StudentId'] . "'
                                AND WorkingOut.f14900 = '" . $sDateSearch . "'
                                AND WorkingOut.f16280 = 'Неоплата'
                                AND WorkingOut.status = 0";
                        //Проверка на “Неоплату”
                        if ($vPayData = sql_query($sSqlQueryPay)) {
                            if ($vRowStudentData = sql_fetch_assoc($vPayData)) {
                                $sDayResult = "нн";
                            } else {
                                $sDayResult = "";
                            }
                        } else {
                            $sDayResult = "";
                        }
                        //Если нет неоплаты
                        if ($sDayResult == "") {
                            //echo "was1 ". $sGroupCode."<br>";
                            //f15990 Дата|Группа|Фио f14700 Был? f14670 Пробное f14710 Отработка
                            $sSqlQueryWas = "SELECT
                                if(GradesClass.f14700 IS NULL, '', GradesClass.f14700) AS Was
                                , if(GradesClass.f14670 IS NULL, '', GradesClass.f14670) AS Trial
                            FROM
                            " . DATA_TABLE . get_table_id(810) . " AS GradesClass
                                WHERE
                                    GradesClass.f15990 = '" . $sGroupCode . "'
                                    AND (GradesClass.f14710 = '' OR GradesClass.f14710 IS NULL)
                                    AND GradesClass.status = 0";
                            // Проверка Был на занятии
                            if ($vWasData = sql_query($sSqlQueryWas)) {
                                if ($vRowWasData = sql_fetch_assoc($vWasData)) {
                                    //echo "was<br>";
                                    if ($vRowWasData["Trial"] == "пробное") {
                                        $sDayResult = "нн";
                                    } else {
                                        //Если не был
                                        if ($vRowWasData["Was"] == "н") {
                                            $sDayResult = "но";
                                        }
                                    }
                                } else {
                                    $sDayResult = "?";
                                }
                            } else {
                                $sDayResult = "?";
                            }
                            // Был через отработку
                            if ($sDayResult == "?") {
                                //f15980 ДатаП|ГруппаП|ФИО f16030 ДатаО|ГруппаО|ФИО
                                $sSqlQueryWorkingOff = "SELECT
                                    if(WorkingOff.f16030 IS NULL, '', WorkingOff.f16030) AS GroupCodeO
                                FROM
                                " . DATA_TABLE . get_table_id(820) . " AS WorkingOff
                                    WHERE
                                        WorkingOff.f15980 = '" . $sGroupCode . "'
                                        AND WorkingOff.status = 0";
                                // Проверка Был на занятии
                                if ($vWorkingOffData = sql_query($sSqlQueryWorkingOff)) {
                                    if ($vRowWorkingOffData = sql_fetch_assoc($vWorkingOffData)) {
                                        $sSqlQueryWas2 = "SELECT
                                            if(GradesClass.f14670 IS NULL, '', GradesClass.f14670) AS Trial
                                        FROM
                                        " . DATA_TABLE . get_table_id(810) . " AS GradesClass
                                        WHERE
                                            GradesClass.f15990 = '" . $vRowWorkingOffData['GroupCodeO'] . "'
                                            AND (GradesClass.f14700 = '' OR GradesClass.f14700 IS NULL)
                                            AND (GradesClass.f14710 <> '' OR GradesClass.f14710 IS NOT NULL)
                                            AND GradesClass.status = 0";
                                        // Проверка Был на занятии
                                        if ($vWasData2 = sql_query($sSqlQueryWas2)) {
                                            if ($vRowWasData2 = sql_fetch_assoc($vWasData2)) {
                                                if ($vRowWasData["Trial"] == "пробное") {
                                                    $sDayResult = "нн";
                                                } else {
                                                    $sDayResult = "";
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        $sDayResult = "нн";
                    }
                } else {
                    $sDayResult = "нн";
                }
            }
        }
        $sDayKey = $dOndate->format('j');
        $vTableData['DayData'][$sDayKey] = $sDayResult;
        $sDayColor = "#4baaf5";
        switch ($sDayResult) {
            case "в":
                $sDayColor = "white";
                break;
            case "":
                $sDayColor = "#419544"; //green
                $vTableData['TotalPay']++;
                break;
            case "но":
                $sDayColor = "#f1749e"; //red
                $vTableData['Skipped']++;
                $vTableData['SkippedPay']++;
                $vTableData['TotalPay']++;
                break;
            case "нн":
                $sDayColor = "#fdd87d"; //yellow
                $vTableData['Skipped']++;
                break;
            case "?":
                $sDayColor = "#4baaf5"; //blue
                break;
        }
        $vTableData['DayColor'][$sDayKey] = $sDayColor;
        $vTableData['PayByRate'] = "";
        $iCostPerLesson = $vGroups['g' . $iGroupId]['CostPerLesson'];
        $iCostPerMonth = $vGroups['g' . $iGroupId]['CostPerMonth'];
        if (($iCostPerLesson > 0 && $iCostPerMonth > 0) || ($iCostPerLesson == 0 && $iCostPerMonth == 0))
            $vTableData['PayByRate'] = "";
        if ($iCostPerLesson > 0)
            $vTableData['PayByRate'] = $iCostPerLesson;
        elseif ($iCostPerMonth > 0)
            $vTableData['PayByRate'] = $iCostPerMonth;

        if ($vTableData['PayByRate'] != "" && $vTableData['PayByRate'] != 0) {
            if ($vTableData['StudentDisc'] != 0)
                $vTableData['PayByRate'] = $vTableData['PayByRate'] * $vTableData['StudentDisc'];
        }
        if ($vTableData['Skipped'] > 0)
            $vTableData['Reson'] = "справка";
        //“зачислен с dd.mm.yyyy” и “отчислен с dd.mm.yyyy”,
        if ($vStudentRow['EnrollmentDate'] != "") {
            if ($vTableData['Reson'] != "")
                $vTableData['Reson'] += ", ";
            $vTableData['Reson'] += $vStudentRow['EnrollmentDate']->format('d.m.Y');
        }
        if ($vStudentRow['DeductionsDate'] != "") {
            if ($vTableData['Reson'] != "")
                $vTableData['Reson'] += ", ";
            $vTableData['Reson'] += $vStudentRow['DeductionsDate']->format('d.m.Y');
        }
    }
    $vLines[] = $vTableData;
}

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