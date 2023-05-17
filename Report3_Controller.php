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
$sDateZero = '0000-00-00 00:00:00';

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
//методисты 2
//педагоги таблица  520
//педагоги 790 f9660 ФИО педагога сокр.
//группы 700
//“Педагог в табеле” f11160 или “Педагог ФАКТ” f11180

//f11150 Подразделение f11310 Стоимость за занятие f11320 Стоимость за месяц
//f9450 Подразделение f9580 Название школы
//f9530 Название школы f9540 ОКПО
//f13570 Время занятий

$iUserId = $user['id'];//40; //
$iTeacherId = 0;
if (
    $result = sql_select_field(
        USERS_TABLE,
        "id",
        "id='" . $iUserId . "' and group_id=790"
    )
) {
    if ($row = sql_fetch_assoc($result)) {
        $sSqlQueryTeacher = "SELECT
            Teacher.id as id
           ,Teacher.f9660 AS fiosearch
         FROM
            " . DATA_TABLE . get_table_id(520) . " AS Teacher
         WHERE
            Teacher.f9630= '" . $iUserId . "'
             AND Teacher.status = 0";
        $vTeacherData = sql_query($sSqlQueryTeacher);

        while ($vTeacherRow = sql_fetch_assoc($vTeacherData)) {
            //$sFioTeacher = $vTeacherRow['fiosearch'];
            $iTeacherId = $vTeacherRow['id'];
        }
    }
}

$sSqlQueryGroup = "SELECT DISTINCT
    Groups.id AS GroupId
    , Groups.f11090 AS GroupName
    , if(Groups.f11310 IS NULL, 0, Groups.f11310) AS CostPerLesson
    , if(Groups.f11320 IS NULL, 0, Groups.f11320) AS CostPerMonth
    , if(Departments.f9450 IS NULL, '', Departments.f9450) AS DepartmentName
    , if(Schools.f9530 IS NULL, '', Schools.f9530) AS SchoolName
    , if(Schools.f9540 IS NULL, '', Schools.f9540) AS SchoolOKPO
    , if(Schools.f9550 IS NULL, '', Schools.f9550) AS SchoolDir
    , if(DaysWeek.f10330 IS NULL, '', DaysWeek.f10330) AS DayWeek
    , if(Schedule.f13570 IS NULL, '', Schedule.f13570) AS ClassTime
    , if(Teacher.f9660 IS NULL, '', Teacher.f9660) AS TeacherFioMin
    FROM
        " . DATA_TABLE . get_table_id(720) . " AS Students
        INNER JOIN " . DATA_TABLE . get_table_id(700) . " AS Groups
            ON Students.f11570 = Groups.id
        LEFT JOIN " . DATA_TABLE . get_table_id(500) . " AS Departments
            ON Groups.f11150 = Departments.id
        LEFT JOIN " . DATA_TABLE . get_table_id(510) . " AS Schools
            ON Departments.f9580 = Schools.id
        LEFT JOIN " . DATA_TABLE . get_table_id(790) . " AS Schedule
            ON Schedule.f13550 = Groups.id
        LEFT JOIN " . DATA_TABLE . get_table_id(600) . " AS DaysWeek
            ON Schedule.f13560 = DaysWeek.id
        LEFT JOIN " . DATA_TABLE . get_table_id(520) . " AS Teacher
            ON Groups.f11160 = Teacher.id
    WHERE
    ((Groups.f11240 <'" . $sDateNext . "' AND Groups.f11240 <>'" . $sDateZero . "' AND (Groups.f11270 IS NULL OR Groups.f11270 = '" . $sDateZero . "'))
        OR (Groups.f11240 <'" . $sDateNext . "' AND Groups.f11240 <>'" . $sDateZero . "' AND Groups.f11270 >= '" . $sDateFirst . "' AND Groups.f11270 <> '" . $sDateZero . "' AND NOT Groups.f11270 IS NULL))
        AND Groups.status = 0";
//проверка грыппы на пользователя педагога
if ($iTeacherId != 0) {
    $sSqlQueryGroup = $sSqlQueryGroup . " AND (Groups.f11160='" . $iTeacherId . "' OR Groups.f11180='" . $iTeacherId . "')";
}

$sSqlQueryGroup = $sSqlQueryGroup . " ORDER BY GroupName";

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
    $vTableData['SchoolDir'] = $vGroupRow['SchoolDir'];
    $vTableData['TeacherFioMin'] = $vGroupRow['TeacherFioMin'];
    $vTableData['DayWeek'] = $vGroupRow['DayWeek'];
    $vTableData['ClassTime'] = $vGroupRow['ClassTime'];
    $vTableData['Skipped'] = 0; //Пропущено
    $vTableData['SkippedPay'] = 0; //Пропущено оплата
    $vTableData['TotalPay'] = 0; //Всего к оплате
    $vGroups['g'.$vGroupRow['GroupId']] = $vTableData;
}



if ($_REQUEST['xsl'] == 1) {
    $vLines = GetData($ddateformate, $vGroups, $iGroupId, $sDateFirst, $sDateNext, $sDateZero);

    echo ExcelData($months, $month, $year, $iGroupId, $vGroups, $vLines);

    exit;
} elseif($_REQUEST['xsl'] == 2) {

    foreach ($vGroups as $vGroup)
    {

      $vLines = GetData($ddateformate, $vGroups, $vGroup['GroupId'], $sDateFirst, $sDateNext, $sDateZero);

      echo ExcelData($months, $month, $year, $vGroup['GroupId'], $vGroups, $vLines);
    }
    exit;

} else {
    $vLines = GetData($ddateformate, $vGroups, $iGroupId, $sDateFirst, $sDateNext, $sDateZero);

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
}

function GetData($ddateformate, &$vGroups, $iGroupId, $sDateFirst, $sDateNext, $sDateZero)
{
    $aDays = array('Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота');
    $vLines = [];
    //f11650 Номер счета f11540 Скидка f11580 Дата зачисления f11590 Дата отчисления
    $sSqlQueryStudents = "SELECT ClienCards.id as StudentId
     , ClienCards.f9750 as StudentFIO
     , Students.f11480 as GroupFactId
     , Groups.f11090 AS GroupFactName
     , if(Students.f11650 IS NULL, '', Students.f11650) AS AccountNumber
     , if(Students.f11540 IS NULL, 0, Students.f11540) AS StudentDisc
     , if(Students.f11580 >= '" . $sDateFirst . "' AND Students.f11580 < '" . $sDateNext . "', Students.f11580, '') AS EnrollmentDate
     , if(Students.f11590 >= '" . $sDateFirst . "' AND Students.f11590 < '" . $sDateNext . "', Students.f11590, '') AS DeductionsDate
 FROM
    " . DATA_TABLE . get_table_id(720) . " AS Students
        INNER JOIN " . DATA_TABLE . get_table_id(530) . " AS ClienCards
            ON Students.f11460 = ClienCards.id
        INNER JOIN " . DATA_TABLE . get_table_id(700) . " AS Groups
            ON Students.f11480 = Groups.id
     WHERE
         Students.f11570 = '" . $iGroupId . "'
         AND ((Students.f11580 <'" . $sDateNext . "' AND Students.f11590 IS NULL AND Students.f11580 <>'" . $sDateZero . "')
            OR (Students.f11580 <'" . $sDateNext . "' AND Students.f11590 = '" . $sDateZero . "' AND Students.f11580 <>'" . $sDateZero . "')
            OR (Students.f11580 <>'" . $sDateZero . "' AND Students.f11580 <'" . $sDateNext . "' AND Students.f11590 >= '" . $sDateFirst . "' AND Students.f11590 <>'" . $sDateZero . "'))
         AND Students.status = 0
     ORDER BY StudentFIO";
    $vStudentsData = sql_query($sSqlQueryStudents);

    while ($vStudentRow = sql_fetch_assoc($vStudentsData)) {
        //print_r($vStudentRow);Schedule.f13550 = '" . $vStudentRow['GroupFactId'] . "'
        $vTableData['StudentId'] = $vStudentRow['StudentId'];
        $vTableData['StudentFIO'] = form_display($vStudentRow['StudentFIO']);
        $vTableData['AccountNumber'] = $vStudentRow['AccountNumber'];
        $vTableData['StudentDisc'] = $vStudentRow['StudentDisc'];
        $vTableData['PayByRate'] = 0; //Плата по ставке
        $vTableData['Reson'] = ""; //Причины непосещения (основание)
        $vTableData['Skipped'] = 0; //Пропущено
        $vTableData['SkippedPay'] = 0; //Пропущено оплата
        $vTableData['nn'] = 0; //Пропущено
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

            // Проверка на день недели
            $sDayResult = "в";
            if ($vResultWeekDay = sql_query($sSqlQueryWeekDay)) {
                if ($vRowWeekDay = sql_fetch_assoc($vResultWeekDay)) {
                    //echo "___" . $vRowWeekDay['ggg'] . "!!---" . $vRowWeekDay['fff'] . "____<br>";
                    $bisHoliday = false;
                    //Проверка на праздник поле f12510 таблицы 760 f12500 - дата
                    $sSqlQueryHoliday = "SELECT
                                    id
                                FROM
                                " . DATA_TABLE . get_table_id(760) . " AS WorkingOff
                                    WHERE
                                        WorkingOff.f12500 = '" . $sDateSearch . "'
                                        AND (WorkingOff.f12510 = 'Праздник' OR WorkingOff.f12510 = 'праздник')
                                        AND WorkingOff.status = 0";
                    // Проверка Был праздник
                    if ($vHolidayData = sql_query($sSqlQueryHoliday)) {
                        if ($vRowHoliday = sql_fetch_assoc($vHolidayData)) {
                            $bisHoliday = true;
                        }
                    }
                    //Не праздник
                    if (!$bisHoliday) {
                        $sSqlQueryStudent = "SELECT
                            Students.f11460 as StudentId
                        FROM
                        " . DATA_TABLE . get_table_id(720) . " AS Students
                            WHERE
                                Students.f11570 = '" . $iGroupId . "'
                                AND Students.f11460 = '" . $vStudentRow['StudentId'] . "'
                                AND (('" . $sDateSearch . "' >=Students.f11580 AND Students.f11590 IS NULL AND Students.f11580 <>'" . $sDateZero . "')
                                OR ('" . $sDateSearch . "' >=Students.f11580 AND Students.f11590 = '" . $sDateZero . "' AND Students.f11580 <>'" . $sDateZero . "')
                                OR (Students.f11580 <>'" . $sDateZero . "' AND '" . $sDateSearch . "' >=Students.f11580 AND '" . $sDateSearch . "' <= Students.f11590 AND Students.f11590 <>'" . $sDateZero . "'))
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
                                        $sDayResult = "?";
                                    }
                                } else {
                                    $sDayResult = "?";
                                }
                                //Если нет неоплаты
                                if ($sDayResult == "?") {
                                    //echo "was1 ". $sGroupCode."<br>";
                                    //f15990 Дата|Группа|Фио f14700 Был? f14670 Пробное f14710 Отработка
                                    $sSqlQueryWas = "SELECT
                                        if(GradesClass.f14700 IS NULL, '', GradesClass.f14700) AS Was
                                        , if(GradesClass.f14670 IS NULL, '', GradesClass.f14670) AS Trial
                                        , if(GradesClass.f14710 IS NULL, '". $sDateZero."', GradesClass.f14710) AS WorkingOff
                                    FROM
                                    " . DATA_TABLE . get_table_id(810) . " AS GradesClass
                                        WHERE
                                            GradesClass.f15990 = '" . $sGroupCode . "'
                                            AND GradesClass.status = 0";
                                    // Проверка Был на занятии
                                    if ($vWasData = sql_query($sSqlQueryWas)) {
                                        if ($vRowWasData = sql_fetch_assoc($vWasData)) {
                                            if($vRowWasData["Was"] == "" && $vRowWasData["WorkingOff"] == $sDateZero){
                                                if ($vRowWasData["Trial"] == "пробное") {
                                                    $sDayResult = "нн";
                                                } else {
                                                    $sDayResult = "";
                                                }
                                            } elseif($vRowWasData["Was"] == "н" && $vRowWasData["WorkingOff"] == $sDateZero) { //Если не был на занятии
                                                // Проверка в таблице отработки
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
                                                            AND GradesClass.f14710 <> ''
                                                            AND GradesClass.f14710 IS NOT NULL
                                                            AND GradesClass.f14710 <> '". $sDateZero."'
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
                                                if ($sDayResult == "?") { //Если не отработка

                                                    if ($vRowWasData["Trial"] == "пробное") {
                                                        $sDayResult = "нн";
                                                    } else {
                                                        $sDayResult = "но";
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
                    $vGroups['g' . $iGroupId]['TotalPay']++;
                    break;
                case "но":
                    $sDayColor = "#f1749e"; //red
                    $vTableData['Skipped']++;
                    $vGroups['g' . $iGroupId]['Skipped']++;
                    $vTableData['SkippedPay']++;
                    $vGroups['g' . $iGroupId]['SkippedPay']++;
                    $vTableData['TotalPay']++;
                    $vGroups['g' . $iGroupId]['TotalPay']++;
                    break;
                case "нн":
                    $sDayColor = "#fdd87d"; //yellow
                    $vTableData['Skipped']++;
                    $vTableData['nn']++;
                    $vGroups['g' . $iGroupId]['Skipped']++;
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
                    $vTableData['PayByRate'] = $vTableData['PayByRate'] * (1 - $vTableData['StudentDisc']);
            }
            $vTableData['Reson'] = "";
            if ($vTableData['nn'] > 0)
                $vTableData['Reson'] = "справка";
            //$vTableData['TrHeight'] = 20;
            //“зачислен с dd.mm.yyyy” и “отчислен с dd.mm.yyyy”,
            if ($vStudentRow['EnrollmentDate'] != "") {
                if ($vTableData['Reson'] != "")
                    $vTableData['Reson'] = $vTableData['Reson'] . ",<br>";
                $dEnrollmentDate = new DateTime($vStudentRow['EnrollmentDate']);
                $vTableData['Reson'] = $vTableData['Reson'] . "зачислен с " . $dEnrollmentDate->format('d.m.Y');
                //$vTableData['Reson'] = $vTableData['Reson'] . ",<br>отчислен с " . $dEnrollmentDate->format('d.m.Y');
               // $vTableData['TrHeight'] += 20;
            }
            if ($vStudentRow['DeductionsDate'] != "") {
                if ($vTableData['Reson'] != "")
                    $vTableData['Reson'] = $vTableData['Reson'] . ",<br>";
                $dDeductionsDate = new DateTime($vStudentRow['DeductionsDate']);
                $vTableData['Reson'] = $vTableData['Reson'] . "отчислен с " . $dDeductionsDate->format('d.m.Y');
                //$vTableData['TrHeight'] += 20;
            }
        }
        $vLines[] = $vTableData;
    }
    return $vLines;
}

function ExcelData($months, $month, $year, $iGroupId, $vGroups, $vLines)
{
    $xsl = "<HTML><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"><style type=\"text/css\">.grid-container {height: 100%;width: 100%;overflow: auto}.ritz .waffle a { color: inherit; }.ritz .waffle .s5{border-right:2px SOLID #000000;background-color:#ffffff;text-align:left;color:#000000;font-family:'docs-Calibri',Arial;font-size:10pt;vertical-align:bottom;white-space:nowrap;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s18{border-bottom:1px SOLID #000000;border-right:1px SOLID #000000;background-color:#ffffff;text-align:left;color:#000000;font-family:'docs-Calibri',Arial;font-size:10pt;vertical-align:bottom;white-space:nowrap;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s2{border-right:1px SOLID #000000;background-color:#ffffff;text-align:left;color:#000000;font-family:'docs-Calibri',Arial;font-size:10pt;vertical-align:bottom;white-space:nowrap;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s9{border-bottom:1px SOLID #000000;border-right:2px SOLID #000000;background-color:#ffffff;text-align:center;color:#000000;font-family:'Arial';font-size:8pt;vertical-align:bottom;white-space:normal;overflow:hidden;word-wrap:break-word;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s10{border-bottom:1px SOLID #000000;background-color:#fff2cc;text-align:left;color:#000000;font-family:'Arial';font-size:8pt;vertical-align:bottom;white-space:normal;overflow:hidden;word-wrap:break-word;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s4{background-color:#ffffff;text-align:right;color:#000000;font-family:'Arial';font-size:8pt;vertical-align:bottom;white-space:nowrap;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s8{border-bottom:1px SOLID #000000;background-color:#fff2cc;text-align:center;color:#000000;font-family:'Arial';font-size:8pt;vertical-align:bottom;white-space:normal;overflow:hidden;word-wrap:break-word;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s13{border-bottom:2px SOLID #000000;border-right:2px SOLID #000000;background-color:#ffffff;text-align:center;color:#000000;font-family:'Arial';font-size:8pt;vertical-align:bottom;white-space:nowrap;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s24{border-bottom:1px SOLID #000000;border-right:1px SOLID #000000;background-color:#fff2cc;text-align:center;color:#000000;font-family:'Arial';font-size:8pt;vertical-align:bottom;white-space:normal;overflow:hidden;word-wrap:break-word;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s27{border-bottom:1px SOLID #000000;border-right:1px SOLID #000000;background-color:#ffffff;text-align:center;color:#000000;font-family:'Arial';font-size:7pt;vertical-align:bottom;white-space:normal;overflow:hidden;word-wrap:break-word;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s33{border-left: none;border-right: none;background-color:#ffffff;text-align:left;color:#000000;font-family:'docs-Calibri',Arial;font-size:10pt;vertical-align:bottom;white-space:nowrap;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s0{background-color:#ffffff;text-align:left;color:#000000;font-family:'docs-Calibri',Arial;font-size:10pt;vertical-align:bottom;white-space:nowrap;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s1{background-color:#ffffff;text-align:center;font-weight:bold;color:#000000;font-family:'Arial';font-size:8pt;vertical-align:bottom;white-space:nowrap;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s28{background-color:#ffffff;text-align:left;color:#000000;font-family:'Arial';font-size:8pt;vertical-align:bottom;white-space:normal;overflow:hidden;word-wrap:break-word;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s32{border-right: none;background-color:#ffffff;text-align:left;color:#000000;font-family:'docs-Calibri',Arial;font-size:10pt;vertical-align:bottom;white-space:nowrap;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s3{border-bottom:2px SOLID #000000;border-right:1px SOLID #000000;background-color:#ffffff;text-align:center;color:#000000;font-family:'Arial';font-size:8pt;vertical-align:bottom;white-space:nowrap;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s14{border-bottom:1px SOLID #000000;background-color:#ffffff;text-align:left;color:#000000;font-family:'docs-Calibri',Arial;font-size:10pt;vertical-align:bottom;white-space:nowrap;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s34{border-left: none;border-right: none;background-color:#ffffff;text-align:center;color:#000000;font-family:'Arial';font-size:6pt;vertical-align:top;white-space:nowrap;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s37{background-color:#ffffff;text-align:left;color:#000000;font-family:'Arial';font-size:8pt;vertical-align:bottom;white-space:nowrap;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s11{border-bottom:1px SOLID #000000;border-right:2px SOLID #000000;background-color:#fff2cc;text-align:right;color:#000000;font-family:'docs-Calibri',Arial;font-size:8pt;vertical-align:bottom;white-space:nowrap;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s15{border-bottom:1px SOLID #000000;border-right:1px SOLID #000000;background-color:#ffffff;text-align:center;color:#000000;font-family:'Arial';font-size:8pt;vertical-align:bottom;white-space:normal;overflow:hidden;word-wrap:break-word;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s23{border-bottom:1px SOLID #000000;border-right:1px SOLID #000000;background-color:#fff2cc;text-align:center;color:#000000;font-family:'Arial';font-size:7pt;vertical-align:bottom;white-space:normal;overflow:hidden;word-wrap:break-word;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s35{border-left: none;background-color:#ffffff;text-align:left;color:#000000;font-family:'docs-Calibri',Arial;font-size:10pt;vertical-align:bottom;white-space:nowrap;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s31{background-color:#ffffff;text-align:center;color:#000000;font-family:'Arial';font-size:6pt;vertical-align:top;white-space:nowrap;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s26{border-right:1px SOLID #000000;background-color:#ffffff;text-align:right;color:#000000;font-family:'Arial';font-size:8pt;vertical-align:bottom;white-space:normal;overflow:hidden;word-wrap:break-word;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s7{border-bottom:1px SOLID #000000;background-color:#fff2cc;text-align:right;color:#000000;font-family:'Arial';font-size:8pt;vertical-align:bottom;white-space:normal;overflow:hidden;word-wrap:break-word;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s36{background-color:#ffffff;text-align:left;color:#000000;font-family:'docs-Calibri',Arial;font-size:10pt;vertical-align:bottom;white-space:nowrap;direction:ltr;padding:0;}.ritz .waffle .s17{border-bottom:2px SOLID #000000;border-right:1px SOLID #000000;background-color:#ffffff;text-align:center;color:#000000;font-family:'Arial';font-size:8pt;vertical-align:bottom;white-space:normal;overflow:hidden;word-wrap:break-word;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s19{border-bottom:1px SOLID #000000;border-right:1px SOLID #000000;background-color:#fff2cc;text-align:left;color:#000000;font-family:'Arial';font-size:8pt;vertical-align:bottom;white-space:normal;overflow:hidden;word-wrap:break-word;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s38{border-right: none;background-color:#ffffff;text-align:left;color:#000000;font-family:'Arial';font-size:8pt;vertical-align:bottom;white-space:nowrap;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s6{border-bottom:1px SOLID #000000;border-right:2px SOLID #000000;background-color:#ffffff;text-align:center;color:#000000;font-family:'Arial';font-size:8pt;vertical-align:bottom;white-space:nowrap;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s16{border-bottom:1px SOLID #000000;border-right:1px SOLID #000000;background-color:#ffffff;text-align:center;color:#000000;font-family:'Arial';font-size:8pt;vertical-align:bottom;white-space:nowrap;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s30{border-bottom:1px SOLID #000000;background-color:#ffffff;text-align:center;color:#ffffff;font-family:'Arial';font-size:8pt;vertical-align:bottom;white-space:nowrap;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s22{border-bottom:1px SOLID #000000;border-right:1px SOLID #000000;background-color:#fff2cc;text-align:right;color:#000000;font-family:'Arial';font-size:8pt;vertical-align:bottom;white-space:normal;overflow:hidden;word-wrap:break-word;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s12{border-bottom:1px SOLID #000000;background-color:#ffffff;text-align:left;color:#000000;font-family:'Arial';font-size:8pt;vertical-align:bottom;white-space:normal;overflow:hidden;word-wrap:break-word;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s25{border-bottom:1px SOLID #000000;border-right:1px SOLID #000000;background-color:#fff2cc;text-align:left;color:#000000;font-family:'Arial';font-size:8pt;vertical-align:middle;white-space:normal;overflow:hidden;word-wrap:break-word;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s21{border-bottom:1px SOLID #000000;border-right:1px SOLID #000000;background-color:#fff2cc;text-align:left;color:#000000;font-family:'docs-Calibri',Arial;font-size:8pt;vertical-align:bottom;white-space:break-word;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s20{border-bottom:1px SOLID #000000;border-right:1px SOLID #ffffff;background-color:#fff2cc;text-align:center;color:#ffffff;font-family:'Arial';font-size:8pt;vertical-align:bottom;white-space:normal;overflow:hidden;word-wrap:break-word;direction:ltr;padding:2px 3px 2px 3px;}.ritz .waffle .s29{border-bottom:1px SOLID #000000;background-color:#ffffff;text-align:center;color:#000000;font-family:'Arial';font-size:8pt;vertical-align:bottom;white-space:normal;overflow:hidden;word-wrap:break-word;direction:ltr;padding:2px 3px 2px 3px;}.row-header-wrapper {overflow: hidden;border-width: 0;margin: 0;padding: 0}th.freezebar-origin-ltr {background: no-repeat url(//ssl.gstatic.com/docs/spreadsheets/waffle_sprite53.png) -205px 0;background-color: #eee;position: relative}.grid-fixed-table, .waffle {font-size: 13px;table-layout: fixed;border-collapse: separate;border-style: none;border-spacing: 0;width: 0;cursor: default}.grid-fixed-table tr {height: 10px}.grid-fixed-table td, .waffle td {overflow: hidden;border: 1px solid;border-color: rgba(0,0,0,0);border-width: 0 1px 1px 0;vertical-align: bottom;line-height: inherit;background-color: #fff;padding: 0 3px}.grid-fixed-table-ltr td, .waffle-ltr td {border-width: 0 1px 1px 0}.grid-fixed-table-rtl td, .waffle-rtl td {border-width: 0 0 1px 1px}.grid-fixed-table th, .waffle th {font-weight: 400;background: transparent;text-align: center;vertical-align: middle;font-size: 8pt;color: #222;height: 23px;border: 1px solid;border-color: rgba(0,0,0,0); border-width: 0 1px 1px 0;overflow: hidden;padding: 0}.grid-fixed-table-ltr th, .waffle-ltr th {border-width: 0 1px 1px 0}.grid-fixed-table-rtl th, .waffle-rtl th {border-width: 0 0 1px 1px}.column-headers-background, .row-headers-background {z-index: 1}</style></head><body><div class=\"ritz grid-container\" dir=\"ltr\"><table class=\"waffle\" cellspacing=\"0\" cellpadding=\"0\">\r\n";
    $xsl .= "<thead><tr><th class=\"row-header freezebar-origin-ltr\"/><th id=\"2089150648C0\" style=\"width:6px;\" class=\"column-headers-background\"> </th><th id=\"2089150648C1\" style=\"width:27px;\" class=\"column-headers-background\"> </th><th id=\"2089150648C2\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C3\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C4\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C5\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C6\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C7\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C8\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C9\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C10\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C11\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C12\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C13\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C14\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C15\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C16\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C17\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C18\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C19\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C20\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C21\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C22\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C23\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C24\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C25\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C26\" style=\"width:27px;\" class=\"column-headers-background\"/><th id=\"2089150648C27\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C28\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C29\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C30\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C31\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C32\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C33\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C34\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C35\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C36\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C37\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C38\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C39\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C40\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C41\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C42\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C43\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C44\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C45\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C46\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C47\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C48\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C49\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C50\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C51\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C52\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C53\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C54\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C55\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C56\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C57\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C58\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C59\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C60\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C61\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C62\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C63\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C64\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C65\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C66\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C67\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C68\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C69\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C70\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C71\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C72\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C73\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C74\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C75\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C76\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C77\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C78\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C79\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C80\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C81\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C82\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C83\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C84\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C85\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C86\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C87\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C88\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C89\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C90\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C91\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C92\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C93\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C94\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C95\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C96\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C97\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C98\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C99\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C100\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C101\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C102\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C103\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C104\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C105\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C106\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C107\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C108\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C109\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C110\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C111\" style=\"width:8px;\" class=\"column-headers-background\"/><th id=\"2089150648C112\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C113\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C114\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C115\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C116\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C117\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C118\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C119\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C120\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C121\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C122\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C123\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C124\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C125\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C126\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C127\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C128\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C129\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C130\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C131\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C132\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C133\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C134\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C135\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C136\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C137\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C138\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C139\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C140\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C141\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C142\" style=\"width:6px;\" class=\"column-headers-background\"/><th id=\"2089150648C143\" style=\"width:6px;\" class=\"column-headers-background\"/></tr></thead><tbody>\r\n";
    $xsl .= "<tr style=\"height: 20px\"><th id=\"2089150648R0\" style=\"height: 20px;\" class=\"row-headers-background\"><div class=\"row-header-wrapper\" style=\"line-height: 20px\"/></th><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s1\" colspan=\"76\">ТАБЕЛЬ</td><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s2\"/><td class=\"s3\" colspan=\"12\">КОДЫ</td></tr>\r\n";
    $xsl .= "<tr style=\"height: 20px\"><th id=\"2089150648R1\" style=\"height: 20px;\" class=\"row-headers-background\"><div class=\"row-header-wrapper\" style=\"line-height: 20px\"/></th><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s1\" colspan=\"76\">УЧЕТА ПОСЕЩАЕМОСТИ ДЕТЕЙ</td><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s4\" colspan=\"12\">Форма по ОКУД</td><td class=\"s0\"/><td class=\"s5\"/><td class=\"s6\" colspan=\"12\">0504608</td></tr>\r\n";
    $xsl .= "<tr style=\"height: 20px\"><th id=\"2089150648R2\" style=\"height: 20px;\" class=\"row-headers-background\"><div class=\"row-header-wrapper\" style=\"line-height: 20px\"/></th><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s7\" colspan=\"8\">за</td><td class=\"s8\" colspan=\"8\">" . $months[$month] . "</td><td class=\"s8\" colspan=\"8\">" . $year . "</td><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s4\" colspan=\"12\">Дата</td><td class=\"s0\"/><td class=\"s5\"/><td class=\"s9\" colspan=\"12\"/></tr>\r\n";
    $xsl .= "<tr style=\"height: 20px\"><th id=\"2089150648R3\" style=\"height: 20px;\" class=\"row-headers-background\"><div class=\"row-header-wrapper\" style=\"line-height: 20px\"/></th><td class=\"s0\"/><td class=\"s0\" colspan=\"25\">Учреждение:</td><td class=\"s10\" colspan=\"90\">" . $vGroups['g' . $iGroupId]['SchoolName'] . "</td><td class=\"s0\"/><td class=\"s0\"/><td class=\"s4\" colspan=\"12\">по ОКПО</td><td class=\"s0\"/><td class=\"s5\"/><td class=\"s11\" colspan=\"12\">" . $vGroups['g' . $iGroupId]['SchoolOKPO'] . "</td></tr>\r\n";
    $xsl .= "<tr style=\"height: 20px\"><th id=\"2089150648R4\" style=\"height: 20px;\" class=\"row-headers-background\"><div class=\"row-header-wrapper\" style=\"line-height: 20px\"/></th><td class=\"s0\"/><td class=\"s0\" colspan=\"25\">Структурное подразделение:</td><td class=\"s10\" colspan=\"90\">" . $vGroups['g' . $iGroupId]['DepartmentName'] . "</td><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s5\"/><td class=\"s6\" colspan=\"12\"/></tr>\r\n";
    $xsl .= "<tr style=\"height: 20px\"><th id=\"2089150648R5\" style=\"height: 20px;\" class=\"row-headers-background\"><div class=\"row-header-wrapper\" style=\"line-height: 20px\"/></th><td class=\"s0\"/><td class=\"s0\" colspan=\"25\">Вид расчета:</td><td class=\"s12\" colspan=\"90\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s5\"/><td class=\"s6\" colspan=\"12\"/></tr>\r\n";
    $xsl .= "<tr style=\"height: 20px\"><th id=\"2089150648R6\" style=\"height: 20px;\" class=\"row-headers-background\"><div class=\"row-header-wrapper\" style=\"line-height: 20px\"></div></th><td class=\"s0\"/><td class=\"s0\" colspan=\"25\">Режим работы:</td><td class=\"s10\" colspan=\"30\">" . $vGroups['g' . $iGroupId]['DayWeek'] . " " . $vGroups['g' . $iGroupId]['ClassTime'] . "</td><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s12\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s5\"/><td class=\"s13\" colspan=\"12\"/></tr>\r\n";
    $xsl .= "<tr style=\"height: 20px\"><th id=\"2089150648R7\" style=\"height: 20px;\" class=\"row-headers-background\"><div class=\"row-header-wrapper\" style=\"line-height: 20px\"/></th><td class=\"s0\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/></tr>\r\n";
    $xsl .= "<tr style=\"height: 20px\"><th id=\"2089150648R8\" style=\"height: 20px;\" class=\"row-headers-background\"><div class=\"row-header-wrapper\" style=\"line-height: 20px\"/></th><td class=\"s2\"/><td class=\"s15\" rowspan=\"3\">№ п/п</td><td class=\"s16\" colspan=\"25\" rowspan=\"3\">Фамилия, имя ребенка</td><td class=\"s16\" colspan=\"16\" rowspan=\"3\">Номер счета</td><td class=\"s17\" colspan=\"7\" rowspan=\"3\">Плата по ставке</td><td class=\"s16\" colspan=\"56\">Дни посещения</td><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s18\"/><td class=\"s15\" colspan=\"11\" rowspan=\"2\">Пропущено дней</td><td class=\"s15\" colspan=\"9\" rowspan=\"3\">Дни по-<br>сещения, подлежа-<br>щие оплате</td><td class=\"s15\" colspan=\"12\" rowspan=\"3\">Причины непосещения (основание)</td></tr>\r\n";
    $xsl .= "<tr style=\"height: 20px\"><th id=\"2089150648R9\" style=\"height: 20px;\" class=\"row-headers-background\"><div class=\"row-header-wrapper\" style=\"line-height: 20px\"/></th><td class=\"s2\"/><td class=\"s16\" colspan=\"2\" rowspan=\"2\">1</td><td class=\"s16\" colspan=\"2\" rowspan=\"2\">2</td><td class=\"s16\" colspan=\"2\" rowspan=\"2\">3</td><td class=\"s16\" colspan=\"2\" rowspan=\"2\">4</td><td class=\"s16\" colspan=\"2\" rowspan=\"2\">5</td><td class=\"s16\" colspan=\"2\" rowspan=\"2\">6</td><td class=\"s16\" colspan=\"2\" rowspan=\"2\">7</td><td class=\"s16\" colspan=\"2\" rowspan=\"2\">8</td><td class=\"s16\" colspan=\"2\" rowspan=\"2\">9</td><td class=\"s16\" colspan=\"2\" rowspan=\"2\">10</td><td class=\"s16\" colspan=\"2\" rowspan=\"2\">11</td><td class=\"s16\" colspan=\"2\" rowspan=\"2\">12</td><td class=\"s16\" colspan=\"2\" rowspan=\"2\">13</td><td class=\"s16\" colspan=\"2\" rowspan=\"2\">14</td><td class=\"s16\" colspan=\"2\" rowspan=\"2\">15</td><td class=\"s16\" colspan=\"2\" rowspan=\"2\">16</td><td class=\"s16\" colspan=\"2\" rowspan=\"2\">17</td><td class=\"s16\" colspan=\"2\" rowspan=\"2\">18</td><td class=\"s16\" colspan=\"2\" rowspan=\"2\">19</td><td class=\"s16\" colspan=\"2\" rowspan=\"2\">20</td><td class=\"s16\" colspan=\"2\" rowspan=\"2\">21</td><td class=\"s16\" colspan=\"2\" rowspan=\"2\">22</td><td class=\"s16\" colspan=\"2\" rowspan=\"2\">23</td><td class=\"s16\" colspan=\"2\" rowspan=\"2\">24</td><td class=\"s16\" colspan=\"2\" rowspan=\"2\">25</td><td class=\"s16\" colspan=\"2\" rowspan=\"2\">26</td><td class=\"s16\" colspan=\"2\" rowspan=\"2\">27</td><td class=\"s16\" colspan=\"2\" rowspan=\"2\">28</td><td class=\"s16\" colspan=\"2\" rowspan=\"2\">29</td><td class=\"s16\" colspan=\"2\" rowspan=\"2\">30</td><td class=\"s16\" colspan=\"2\" rowspan=\"2\">31</td></tr>\r\n";
    $xsl .= "<tr style=\"height: 20px\"><th id=\"2089150648R10\" style=\"height: 20px;\" class=\"row-headers-background\"><div class=\"row-header-wrapper\" style=\"line-height: 20px\"/></th><td class=\"s2\"/><td class=\"s15\" colspan=\"5\">всего</td><td class=\"s15\" colspan=\"6\">в том числе засчи-тыва-емых</td></tr>\r\n";
    $iPos = 0;
    foreach ($vLines as $aData) {
        $iPos++;
        $xsl .= "<tr style=\"height: 20px\"><th id=\"2089150648R11\" style=\"height: 20px;\" class=\"row-headers-background\"><div class=\"row-header-wrapper\" style=\"line-height: 20px\"/></th>\r\n";
        $xsl .= "<td class=\"s2\"/><td class=\"s16\">" . $iPos . "</td><td class=\"s19\" colspan=\"25\">" . $aData['StudentFIO'] . "</td><td class=\"s20\"/><td class=\"s21\" colspan=\"15\">" . $aData['AccountNumber'] . "</td><td class=\"s22\" colspan=\"7\">" . $aData['PayByRate'] . "</td>\r\n";

        for ($i = 1; $i < 32; $i++) {
            if (array_key_exists($i, $aData['DayData']))
                $xsl .= "<td class=\"s23\" colspan=\"2\">" . $aData['DayData'][$i] . "</td>\r\n";
            else
                $xsl .= "<td class=\"s23\" colspan=\"2\">в</td>\r\n";
        }
        $xsl .= "<td class=\"s24\" colspan=\"5\">" . $aData['Skipped'] . "</td><td class=\"s24\" colspan=\"6\">" . $aData['SkippedPay'] . "</td><td class=\"s24\" colspan=\"9\">" . $aData['TotalPay'] . "</td><td class=\"s21\" colspan=\"12\">" . $aData['Reson'] . "</td></tr>\r\n";
    }
    $xsl .= "<tr style=\"height: 20px\"><th id=\"2089150648R14\" style=\"height: 20px;\" class=\"row-headers-background\"><div class=\"row-header-wrapper\" style=\"line-height: 20px\"/></th><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s26\" colspan=\"7\"/><td class=\"s27\" colspan=\"2\"/><td class=\"s27\" colspan=\"2\"/><td class=\"s27\" colspan=\"2\"/><td class=\"s27\" colspan=\"2\"/><td class=\"s27\" colspan=\"2\"/><td class=\"s27\" colspan=\"2\"/><td class=\"s27\" colspan=\"2\"/><td class=\"s27\" colspan=\"2\"/><td class=\"s27\" colspan=\"2\"/><td class=\"s27\" colspan=\"2\"/><td class=\"s27\" colspan=\"2\"/><td class=\"s27\" colspan=\"2\"/><td class=\"s27\" colspan=\"2\"/><td class=\"s27\" colspan=\"2\"/><td class=\"s27\" colspan=\"2\"/><td class=\"s27\" colspan=\"2\"/><td class=\"s27\" colspan=\"2\"/><td class=\"s27\" colspan=\"2\"/><td class=\"s27\" colspan=\"2\"/><td class=\"s27\" colspan=\"2\"/><td class=\"s27\" colspan=\"2\"/><td class=\"s27\" colspan=\"2\"/><td class=\"s27\" colspan=\"2\"/><td class=\"s27\" colspan=\"2\"/><td class=\"s27\" colspan=\"2\"/><td class=\"s27\" colspan=\"2\"/><td class=\"s27\" colspan=\"2\"/><td class=\"s27\" colspan=\"2\"/><td class=\"s27\" colspan=\"2\"/><td class=\"s27\" colspan=\"2\"/><td class=\"s27\" colspan=\"2\"/><td class=\"s24\" dir=\"ltr\" colspan=\"5\">" . $vGroups['g' . $iGroupId]['Skipped'] . "</td><td class=\"s24\" dir=\"ltr\" colspan=\"6\">" . $vGroups['g' . $iGroupId]['SkippedPay'] . "</td><td class=\"s24\" dir=\"ltr\" colspan=\"9\">" . $vGroups['g' . $iGroupId]['TotalPay'] . "</td><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/></tr>\r\n";
    $xsl .= "<tr style=\"height: 20px\"><th id=\"2089150648R15\" style=\"height: 20px;\" class=\"row-headers-background\"><div class=\"row-header-wrapper\" style=\"line-height: 20px\"/></th><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/></tr>\r\n";
    $xsl .= "<tr style=\"height: 20px\"><th id=\"2089150648R16\" style=\"height: 20px;\" class=\"row-headers-background\"><div class=\"row-header-wrapper\" style=\"line-height: 20px\"/></th><td class=\"s28\" colspan=\"11\">Руководитель учреждения<br>(ответственный исполнитель)</td><td class=\"s29\" colspan=\"19\">Директор</td><td class=\"s0\"/><td class=\"s30\" colspan=\"16\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s8\" colspan=\"18\">". $vGroups['g' . $iGroupId]['SchoolDir']."</td><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s28\" colspan=\"12\">Ответственный<br>исполнитель</td><td class=\"s0\"/><td class=\"s29\" colspan=\"18\"/><td class=\"s0\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s14\"/><td class=\"s0\"/><td class=\"s29\" colspan=\"19\"/><td class=\"s0\"/></tr>\r\n";
    $xsl .= "<tr style=\"height: 20px\"><th id=\"2089150648R17\" style=\"height: 20px;\" class=\"row-headers-background\"><div class=\"row-header-wrapper\" style=\"line-height: 20px\"/></th><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s31\" colspan=\"19\">(должность)</td><td class=\"s0\"/><td class=\"s31\" colspan=\"16\">(подпись)</td><td class=\"s0\"/><td class=\"s0\"/><td class=\"s31\" colspan=\"18\">(расшифровка подписи)</td><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s31\" colspan=\"18\">(должность)</td><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s32\"/><td class=\"s33\"/><td class=\"s33\"/><td class=\"s33\"/><td class=\"s34 softmerge\"><div class=\"softmerge-inner\" style=\"width:52px;left:-25px\">(подпись)</div></td><td class=\"s33\"/><td class=\"s33\"/><td class=\"s33\"/><td class=\"s35\"/><td class=\"s35\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s31\" colspan=\"19\">(расшифровка подписи)</td><td class=\"s0\"/></tr>\r\n";
    $xsl .= "<tr style=\"height: 20px\"><th id=\"2089150648R18\" style=\"height: 20px;\" class=\"row-headers-background\"><div class=\"row-header-wrapper\" style=\"line-height: 20px\"/></th><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/></tr>\r\n";
    $xsl .= "<tr style=\"height: 20px\"><th id=\"2089150648R19\" style=\"height: 20px;\" class=\"row-headers-background\"><div class=\"row-header-wrapper\" style=\"line-height: 20px\"/></th><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s36\" colspan=\"16\" rowspan=\"2\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/></tr>\r\n";
    $xsl .= "<tr style=\"height: 20px\"><th id=\"2089150648R20\" style=\"height: 20px;\" class=\"row-headers-background\"><div class=\"row-header-wrapper\" style=\"line-height: 20px\"/></th><td class=\"s37\" colspan=\"24\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s8\" colspan=\"19\">" . $vGroups['g' . $iGroupId]['TeacherFioMin'] . "</td><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s38 softmerge\"><div class=\"softmerge-inner\" style=\"width:206px;left:-1px\">&quot; ___ &quot; _______________________ 20____ г.</div></td><td class=\"s33\"/><td class=\"s33\"/><td class=\"s33\"/><td class=\"s33\"/><td class=\"s33\"/><td class=\"s33\"/><td class=\"s33\"/><td class=\"s33\"/><td class=\"s33\"/><td class=\"s33\"/><td class=\"s33\"/><td class=\"s33\"/><td class=\"s33\"/><td class=\"s33\"/><td class=\"s33\"/><td class=\"s33\"/><td class=\"s33\"/><td class=\"s33\"/><td class=\"s33\"/><td class=\"s33\"/><td class=\"s33\"/><td class=\"s33\"/><td class=\"s33\"/><td class=\"s33\"/><td class=\"s35\"/><td class=\"s35\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/></tr>\r\n";
    $xsl .= "<tr style=\"height: 20px\"><th id=\"2089150648R21\" style=\"height: 20px;\" class=\"row-headers-background\"><div class=\"row-header-wrapper\" style=\"line-height: 20px\"/></th><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s31\" colspan=\"16\">(подпись)</td><td class=\"s0\"/><td class=\"s31\" colspan=\"19\">(расшифровка подписи)</td><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/><td class=\"s0\"/></tr>\r\n";
    $xsl .= "</tbody></table></div></body><HTML>\r\n";

    $name = $vGroups['g' . $iGroupId]['GroupName'] . "_" . $months[$month] . "_" . $year . ".html";
    //$name = "Table" . $iGroupId . "_" . $month . "_" . $year . ".pdf";
       //header("Content-type: application/vnd.ms-excel; charset=utf-8");
    //header("Content-Disposition: attachment; filename=" . $name);

    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=" . $name); //File name extension was wrong
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private", false);
    return $xsl;
}