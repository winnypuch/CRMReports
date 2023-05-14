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
//методисты 2
//педагоги таблица  520
//педагоги 790 f9660 ФИО педагога сокр.
//группы 700
//“Педагог в табеле” f11160 или “Педагог ФАКТ” f11180

//f11150 Подразделение f11310 Стоимость за занятие f11320 Стоимость за месяц
//f9450 Подразделение f9580 Название школы
//f9530 Название школы f9540 ОКПО
//f13570 Время занятий

$iUserId = 40;//$user['id'];
$sFioTeacher = "";
if (
    $result = sql_select_field(
        USERS_TABLE,
        "id",
        "id='" . $iUserId . "' and group_id=790"
    )
) {
    if ($row = sql_fetch_assoc($result)) {
        $sSqlQueryTeacher = "SELECT
           Teacher.f9660 AS fiosearch
         FROM
            " . DATA_TABLE . get_table_id(520) . " AS Teacher
         WHERE
            Teacher.f9630= '" . $iUserId . "'
             AND Teacher.status = 0";
        $vTeacherData = sql_query($sSqlQueryTeacher);

        while ($vTeacherRow = sql_fetch_assoc($vTeacherData)) {
            $sFioTeacher = $vTeacherRow['fiosearch'];
            echo $sFioTeacher;
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
    , if(DaysWeek.f10330 IS NULL, '', DaysWeek.f10330) AS DayWeek
    , if(Schedule.f13570 IS NULL, '', Schedule.f13570) AS ClassTime
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
    WHERE
    ((Groups.f11240 <'" . $sDateNext . "' AND Groups.f11240 <>'" . $date_zero . "' AND (Groups.f11270 IS NULL OR Groups.f11270 = '" . $date_zero . "'))
        OR (Groups.f11240 <'" . $sDateNext . "' AND Groups.f11240 <>'" . $date_zero . "' AND Groups.f11270 >= '" . $sDateFirst . "' AND Groups.f11270 <> '" . $date_zero . "' AND NOT Groups.f11270 IS NULL))
        AND Groups.status = 0";
//проверка грыппы на пользователя педагога
if($sFioTeacher != ""){
    $sSqlQueryGroup = $sSqlQueryGroup . " AND (Groups.f11160='".$sFioTeacher."' OR Groups.f11180='".$sFioTeacher."')";
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
    $vTableData['DayWeek'] = $vGroupRow['DayWeek'];
    $vTableData['ClassTime'] = $vGroupRow['ClassTime'];
    $vTableData['Skipped'] = 0; //Пропущено
    $vTableData['SkippedPay'] = 0; //Пропущено оплата
    $vTableData['TotalPay'] = 0; //Всего к оплате
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
                $vTableData['PayByRate'] = $vTableData['PayByRate'] * $vTableData['StudentDisc'];
        }
        if ($vTableData['Skipped'] > 0)
            $vTableData['Reson'] = "справка";
        //“зачислен с dd.mm.yyyy” и “отчислен с dd.mm.yyyy”,
        if ($vStudentRow['EnrollmentDate'] != "") {
            if ($vTableData['Reson'] != "")
                $vTableData['Reson'] = $vTableData['Reson'] . ", ";
            $dEnrollmentDate = new DateTime($vStudentRow['EnrollmentDate']);
            $vTableData['Reson'] = $vTableData['Reson']."зачислен с ". $dEnrollmentDate->format('d.m.Y');
        }
        if ($vStudentRow['DeductionsDate'] != "") {
            if ($vTableData['Reson'] != "")
                $vTableData['Reson'] = $vTableData['Reson'] . ", ";
            $dDeductionsDate = new DateTime($vStudentRow['DeductionsDate']);
            $vTableData['Reson'] = $vTableData['Reson'] . "отчислен с " .$dDeductionsDate->format('d.m.Y');
        }
    }
    $vLines[] = $vTableData;
}

if ($_REQUEST['xsl'] == 1) {
    $xsl = "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"></head><body>\r\n";
    $xsl .= "<table cellspacing=\"0\" cellpadding=\"3\" border=\"1\">\r\n<tr><td></td><td></td><td colspan=\"5\"><h1>Табель</h1></td></tr>\r\n";
    $xsl .= "<tr><td></td><td></td><td colspan=\"5\"><h1>УЧЕТА ПОСЕЩАЕМОСТИ ДЕТЕЙ</h1></td></tr>\r\n";
    $xsl .= "<tr><td></td><td width=\"150\" style=\"text-align: center\" align=\"rigth\">за</td><td>".$months[$month]."</td><td>" . $year . "</td></tr>\r\n";
    $xsl .= "<tr><td></td><td width=\"150\" style=\"text-align: center\" align=\"center\">Учреждение:</td><td>" . $vGroups['g' . $iGroupId]['SchoolName'] . "</td></tr>\r\n";
    $xsl .= "<tr><td></td><td width=\"150\" style=\"text-align: center\" align=\"center\">Структурное подразделение:</td><td>" . $vGroups['g' . $iGroupId]['DepartmentName'] . "</td></tr>\r\n";
    $xsl .= "<tr><td></td><td width=\"150\" style=\"text-align: center\" align=\"center\">Вид расчета:</td><td></td></tr>\r\n";
    $xsl .= "<tr><td></td><td width=\"150\" style=\"text-align: center\" align=\"center\">ОКПО:</td><td>" . $vGroups['g' . $iGroupId]['SchoolOKPO'] . "</td></tr>\r\n";
    $xsl .= "<tr><td></td><td width=\"150\" style=\"text-align: center\" align=\"center\">Режим работы:</td><td>" . $vGroups['g' . $iGroupId]['DayWeek'] . " ". $vGroups['g' . $iGroupId]['ClassTime'] ."</td></tr>\r\n";
    $xsl .= "<tr><td width=\"50\" rowspan=\"2\" style=\"text-align: center\" align=\"center\">№ п/п</td>\r\n";
    $xsl .= "<td width=\"600\" rowspan=\"2\" style=\"text-align: center\" align=\"center\">Фамилия, имя ребенка</td>\r\n";
    $xsl .= "<td width=\"300\" rowspan=\"2\" style=\"text-align: center\" align=\"center\">Номер счета</td>\r\n";
    $xsl .= "<td width=\"200\" rowspan=\"2\" style=\"text-align: center\" align=\"center\">Плата по ставке</td>\r\n";
    $xsl .= "<td width=\"3100\" colspan=\"31\" style=\"text-align: center\" align=\"center\">Дни посещения</td>\r\n";
    $xsl .= "<td width=\"300\" colspan=\"2\" style=\"text-align: center\" align=\"center\">Пропущено дней</td>\r\n";
    $xsl .= "<td width=\"150\" rowspan=\"2\" style=\"text-align: center\" align=\"center\">Дни по-\r\nсещения, подлежа-\r\nщие оплате</td>\r\n";
    $xsl .= "<td width=\"400\" rowspan=\"2\" style=\"text-align: center\" align=\"center\">Причины непосещения (основание)</td>\r\n";
    $xsl .= "</tr><tr>\r\n";
    for ($i = 1; $i < 32; $i++) {
        $xsl .= "<td width=\"100\" style=\"text-align: center\" align=\"center\">".$i."</td>\r\n";
    }

    $xsl .= "<td width=\"150\" style=\"text-align: center\" align=\"center\">всего</td>\r\n";
    $xsl .= "<td width=\"150\" style=\"text-align: center\" align=\"center\">в том числе засчи- тыва- емых</td>\r\n";
    $xsl .= "</tr>\r\n";
    $iPos = 0;
    foreach ($vLines as $aData) {
        $iPos++;
        $xsl .= "<tr><td width=\"50\" style=\"text-align: center\" align=\"center\">". $iPos ."</td>\r\n";
        $xsl .= "<td width=\"600\" style=\"text-align: left\" align=\"left\">". $aData['StudentFIO']."</td>\r\n";
        $xsl .= "<td width=\"300\" style=\"text-align: center\" align=\"center\">" . $aData['AccountNumber'] . "</td>\r\n";
        $xsl .= "<td width=\"200\" style=\"text-align: center\" align=\"center\">" . $aData['PayByRate'] . "</td>\r\n";
        for ($i = 1; $i < 32; $i++) {
            if(array_key_exists($i, $aData['DayData']))
                $xsl .= "<td width=\"100\" style=\"text-align: center\" align=\"center\">" . $aData['DayData'][$i] . "</td>\r\n";
            else
                $xsl .= "<td width=\"100\" style=\"text-align: center\" align=\"center\">в</td>\r\n";
        }
        $xsl .= "<td width=\"150\" style=\"text-align: center\" align=\"center\">".$aData['Skipped']."</td>\r\n";
        $xsl .= "<td width=\"150\" style=\"text-align: center\" align=\"center\">" . $aData['SkippedPay'] . "</td>\r\n";
        $xsl .= "<td width=\"150\" style=\"text-align: center\" align=\"center\">" . $aData['TotalPay'] . "</td>\r\n";
        $xsl .= "<td width=\"400\" style=\"text-align: left\" align=\"left\">" . $aData['Reson'] . "</td>\r\n";
        $xsl .= "</tr>\r\n";
    }
    $xsl .= "<tr>\r\n";
    $xsl .= "<td colspan=\"35\"></td>\r\n";
    $xsl .= "<td width=\"150\" style=\"text-align: center\" align=\"center\">" . $aData['Skipped'] . "</td>\r\n";
    $xsl .= "<td width=\"150\" style=\"text-align: center\" align=\"center\">" . $aData['SkippedPay'] . "</td>\r\n";
    $xsl .= "<td width=\"150\" style=\"text-align: center\" align=\"center\">" . $aData['TotalPay'] . "</td>\r\n";
    $xsl .= "</tr>\r\n";
    $xsl .= "</table></body></html>";

    $name = "Table".$iGroupId."_".$month."_".$year.".xls";

    header("Content-type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=" . $name);

    echo $xsl;

    exit;
} else {
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