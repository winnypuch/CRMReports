<?php
$bdebug = false;
if($bdebug) "<pre>".var_dump($_REQUEST)."</pre><br>";
//if ($_REQUEST['sDateT1']) {
//    $sDateT1 = date("Y-m-d 00:00:00", strtotime(form_eng_time($_REQUEST['sDateT1'])));
//} else {
//    $sDateT1 = date("Y-m-d 00:00:00", mktime(0, 0, 0, date("m"), 1, date("Y")));
//}
$sDateT1 = date("Y-m-d 00:00:00", mktime(0, 0, 0, date("m"), 1, date("Y")));
if ($_REQUEST['iGroupId']) {
    $iGroupId = $_REQUEST['iGroupId'];

} else {
    $iGroupId = 0;
}

$sDateZero = '0000-00-00 00:00:00';
$vLines = [];
$sDateNow = date('Y-m-d 00:00:00');
$bIsAdmin = true;
$sCabinetH1 = "---";
$sFormatPlanL1 = "---";
$sWeekDayV1 = "---";
$sDateTimeX1 = "---";
$sTeacherFioFactD2 = "---";
$sDepartmentNameH2 = "---";
$sDepartmentAddressL2 = "---";
$sCabinetH1 = "---";
$sLessonTimeX1 = "---";
$sProgramAgeX2 = "---";
$sAcademicYearU2 = "---";
$sWeekLessonR2 = "---";
$sProgramForYearL3 = "---";
$sSubsectionP3 = "---";
$sJobNameT3 = "---";
$sPrintOutsPdf = "---";
$aTeachers =[];
$vTeachers = [];

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
if ($result = sql_select_field(USERS_TABLE, "group_id", "id='" . $iUserId . "' and group_id IN (790, 800)")) {
    if ($row = sql_fetch_assoc($result)) {
        $sSqlQueryTeacher = "SELECT
            Teacher.id as id
           ,Teacher.f9660 AS fiosearch
         FROM
            " . DATA_TABLE . get_table_id(520) . " AS Teacher
         WHERE
            Teacher.f9630= '" . $iUserId . "'
             AND Teacher.status = 0";
        $vClassesData = sql_query($sSqlQueryTeacher);

        while ($vClassesRow = sql_fetch_assoc($vClassesData)) {
            //$sFioTeacher = $vTeacherRow['fiosearch'];
            $iTeacherId = $vClassesRow['id'];
        }
        //Если это Педагоги огр
        if($row["group_id"] == 800)
            $bIsAdmin = false;
    }
}
//f11260 - Фиктивная группа
//f11140 - Программный возраст
//f13580 - Формат план f11240 Дата начала занятий f11270 Дата окончания занятий
//f11180 - педагог факт
//f11160 - Педагог в табеле
//Подразделение f11150
//ФИо педагога сокр f9660
//f13630
// f11240 - Дата начала занятий
$sSqlQueryGroup = "SELECT DISTINCT
        Groups.id AS GroupId
        , Groups.f11090 AS GroupName
        , if(Groups.f11140 IS NULL, '', Groups.f11140) AS ProgramAge
        , if(Teacher.id IS NULL, 0, Teacher.id) AS TeacherId
        , if(Teacher.f9660 IS NULL, '', Teacher.f9660) AS TeacherFioFact
        , if(Departments.f9450 IS NULL, '', Departments.f9450) AS DepartmentName
        , if(Departments.f9460  IS NULL, '', Departments.f9460) AS DepartmentAddress
        , IFNULL((SELECT MAX(Classes.f12750) FROM " . DATA_TABLE . get_table_id(780) . "AS Classes  WHERE Classes.f12870 = Groups.id AND Classes.status = 0),'') AS MaxClassesDate
        , if(Groups.f11240 IS NULL, '', Groups.f11240) AS ClassStartDate
    FROM
        " . DATA_TABLE . get_table_id(790) . " AS Schedule
        INNER JOIN " . DATA_TABLE . get_table_id(700) . " AS Groups
            ON Schedule.f13550 = Groups.id
        LEFT JOIN " . DATA_TABLE . get_table_id(520) . " AS Teacher
            ON Groups.f11180 = Teacher.id
        LEFT JOIN " . DATA_TABLE . get_table_id(500) . " AS Departments
            ON Groups.f11150 = Departments.id
    WHERE
        Schedule.status = 0
        AND (Groups.f11240 <>'" . $sDateZero . "' AND NOT Groups.f11240 IS NULL AND (Groups.f11270 IS NULL OR Groups.f11270 = '" . $sDateZero . "'
            OR (Groups.f11270 <>'" . $sDateZero . "' AND NOT Groups.f11270 IS NULL AND DATE_ADD(Groups.f11270, INTERVAL 30 DAY) >= '" . $sDateNow . "')))
        AND Groups.f11260 = '0'
        AND Groups.status = 0";
//проверка грыппы на пользователя педагога
if ($iTeacherId != 0) {
    $sSqlQueryGroup = $sSqlQueryGroup . " AND (Groups.f11160='" . $iTeacherId . "' OR Groups.f11180='" . $iTeacherId . "')";
}

$sSqlQueryGroup = $sSqlQueryGroup . " ORDER BY GroupName";

if($bdebug) echo $sSqlQueryGroup."--3<br>";


$vGroups = [];
$iGroupTeacherId = 0;
if($vGroupData = sql_query($sSqlQueryGroup)){
    while ($vGroupRow = sql_fetch_assoc($vGroupData)) {
        if ($iGroupId == 0) {
            $iGroupId = $vGroupRow['GroupId'];
        }
        $vTableData['GroupId'] = $vGroupRow['GroupId'];
        $vTableData['GroupName'] = form_display($vGroupRow['GroupName']);
        if ($iGroupId == $vGroupRow['GroupId']) {
            $sProgramAgeX2 = $vGroupRow['ProgramAge'];
            $sTeacherFioFactD2 = form_display($vGroupRow['TeacherFioFact']);
            $sDepartmentNameH2 = form_display($vGroupRow['DepartmentName']);
            $sDepartmentAddressL2 = form_display($vGroupRow['DepartmentAddress']);
            $sMaxClassesDate = $vGroupRow['MaxClassesDate'];
            $sClassStartDate = $vGroupRow['ClassStartDate'];
            $iGroupTeacherId = $vGroupRow['TeacherId'];
        }
        $vTableData['TeacherId'] = $vGroupRow['TeacherId'];
        $vTableData['ProgramAge'] = $vGroupRow['ProgramAge'];
        $vTableData['sDepartmentNameH2'] = $vGroupRow['sDepartmentNameH2'];
        $vTableData['sDepartmentAddressL2'] = $vGroupRow['sDepartmentAddressL2'];
        $vTableData['MaxClassesDate'] = $vGroupRow['MaxClassesDate'];
        $vTableData['ClassStartDate'] = $vGroupRow['ClassStartDate'];
        $vTableData['TeacherFioFact'] = $vGroupRow['TeacherFioFact'];
        $vGroups['g'.$vGroupRow['GroupId']] = $vTableData;
    }
}


//$aDays[date("w", strtotime($sDateSearch))]
//Находим следующую дату занятия
//$aDaysEn = array('sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday');
//$aDays = array('Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота');
$aDaysToEng = array('Воскресенье' => 'sunday', 'Понедельник' => 'monday', 'Вторник' => 'tuesday', 'Среда' => 'wednesday', 'Четверг' => 'thursday', 'Пятница' => 'friday', 'Суббота' => 'saturday');
if($sMaxClassesDate == ""){
    $sStartDate = $sClassStartDate;
}else{
    $sStartDate = $sMaxClassesDate;
}
$dStartDate = new DateTime($sStartDate);
$sStartWeekDay = strtolower(date("l", strtotime($sStartDate)));
$dSearchDate = $dStartDate;
$iWeekDay = 0;
$sWeekDayV1 = "";
//$dStartDate->modify('next monday');
//Расписние 790 Дни недели 600
//f13560 День недели
//f10330 Дни недели
$iColClass = 0;
if ($vResWeekDays = sql_query("SELECT WeekDays.id AS WeekDayId, WeekDays.f10330 AS WeekDayName FROM " . DATA_TABLE . get_table_id(790) ." AS Schedule INNER JOIN " . DATA_TABLE . get_table_id(600) ." AS WeekDays ON Schedule.f13560 = WeekDays.id WHERE Schedule.f13550='" . $iGroupId . "' AND Schedule.status='0' ORDER BY WeekDays.id")) {
    while ($vRowWeekDays = sql_fetch_assoc($vResWeekDays)) {
        $iColClass++;
        //Если день начальной даты для поиска равен дню недели
        if($sStartWeekDay == $aDaysToEng[$vRowWeekDays['WeekDayName']]) {
            $iWeekDay = $vRowWeekDays['WeekDayId'];
            $sWeekDayV1 = $vRowWeekDays['WeekDayName'];
            $dSearchDate = $dStartDate;
            break;
        } else{
            if($iColClass == 1) {
                $dSearchDate->modify('next '.$aDaysToEng[$vRowWeekDays['WeekDayName']]);
                $iWeekDay = $vRowWeekDays['WeekDayId'];
                $sWeekDayV1 = $vRowWeekDays['WeekDayName'];
            }else{
                $dCheckDate = $dStartDate;
                $dCheckDate->modify('next '.$aDaysToEng[$vRowWeekDays['WeekDayName']]);
                if($dCheckDate < $dSearchDate) {
                    $dSearchDate = $dCheckDate;
                    $iWeekDay = $vRowWeekDays['WeekDayId'];
                    $sWeekDayV1 = $vRowWeekDays['WeekDayName'];
                }
            }
        }
    }
}
// Если количество занятий больше 0
if($iColClass > 0){
    $sSqlQuerySchedule = "SELECT
       if(Schedule.f13580 IS NULL, '', Schedule.f13580) AS FormatPlan
        , if(Schedule.f13630 IS NULL, '', Schedule.f13630) AS Cabinet
        , if(Schedule.f13570 IS NULL, '', Schedule.f13570) AS LessonTime
    FROM
        " . DATA_TABLE . get_table_id(790) . " AS Schedule
        INNER JOIN " . DATA_TABLE . get_table_id(700) . " AS Groups
            ON Schedule.f13550 = Groups.id
    WHERE
        Schedule.f12870 = ".$iGroupId."
        AND Schedule.f13560 = ".$iWeekDay."
        AND Schedule.status = 0";
    if($vScheduleData = sql_query($sSqlQuerySchedule)){
        while ($vScheduleRow = sql_fetch_assoc($vScheduleData)) {
            $sFormatPlanL1 = form_display($vScheduleRow['FormatPlan']);
            $sCabinetH1 = form_display($vScheduleRow['Cabinet']);
            $sLessonTimeX1 = form_display($vScheduleRow['LessonTime']);
        }
    }
    //Учебный год
    $dTrainingBeg = new DateTime();
    $dTrainingBeg->setDate(intval($dSearchDate->format("Y")) - 1, 9, 1);
    $dTrainingEnd = new DateTime();
    $dTrainingEnd->setDate(intval($dSearchDate->format("Y")), 8, 31);

    $sAcademicYearU2 = ($dSearchDate >= $dTrainingBeg && $dSearchDate <= $dTrainingEnd) ?  strval(intval($dSearchDate->format("Y")) - 1) : $dSearchDate->format("Y");
    $iWeek = 0;
    $iLesson = 0;
    //Из таблицы Занятия 780 по полю Название группы f12870 вычисляем по полям № недели f13020 и № урока f13340 номер последнего занятия (сначала макс по неделе, потом макс по уроку).
    if ($vResClassesData = sql_query("SELECT MAX(f13020) AS Week FROM " . DATA_TABLE . get_table_id(780) ." WHERE f12870='" . $iGroupId . "' AND status='0'")) {
        if ($vRowClassesData = sql_fetch_assoc($vResClassesData)) {
            $iWeek = intval($vRowClassesData['Week']);
            if ($vResLessonData = sql_query("SELECT MAX(f13340) AS Lesson FROM " . DATA_TABLE . get_table_id(780) ." WHERE f13020 = '".$iWeek."' AND f12870='" . $iGroupId . "' AND status='0'")) {
                if ($vRowLessonData = sql_fetch_assoc($vResLessonData)) {
                    $iLesson = intval($vRowLessonData['Week']);
                    if($iColClass == 1) {
                        $iWeek++;
                        $iLesson = 1;
                    } else {
                        if($iLesson == 1) {
                            $iLesson++;
                        } else {
                            $iWeek++;
                            $iLesson = 1;
                        }
                    }
                    $sWeekLessonR2 = strval($iWeek).".".strval($iLesson);
                }
            }
        }
    }

    $sSqlQueryTeachers = "SELECT
           Teachers.id AS TeacherId
           , Teachers.f9660 AS TeacherName
        FROM
            " . DATA_TABLE . get_table_id(520) . " AS Teachers
        WHERE
           Teachers.id <> ".$iGroupTeacherId."
            AND Teachers.status = 0
        ORDER BY Teachers.f9660";
    if($vTeachersData = sql_query($sSqlQueryTeachers)){
        while ($vTeachersRow = sql_fetch_assoc($vTeachersData)) {
            $aTeachers["TeacherId"] = $vTeachersRow["TeacherId"];
            $aTeachers["TeacherName"] = $vTeachersRow["TeacherName"];
            $vTeachers[] = array("TeacherId" => $vTeachersRow["TeacherId"], "TeacherName" => $vTeachersRow["TeacherName"]);
        }
    }

    $sSqlQueryProgramForYear = "SELECT
           ProgramForYear.f12600 AS ProgramForYearName
        FROM
            " . DATA_TABLE . get_table_id(730) . " AS ProgramForYear
        WHERE
           ProgramForYear.f11700 = '".$sProgramAgeX2."'
           AND ProgramForYear.f11710 = '".$iWeek."'
           AND ProgramForYear.f11850 = '".$iLesson."'
           AND ProgramForYear.status = 0";
    if($vProgramForYearData = sql_query($sSqlQueryProgramForYear)){
        if ($vProgramForYearRow = sql_fetch_assoc($vProgramForYearData)) {
            $sProgramForYearL3 = $vProgramForYearRow['ProgramForYearName'];
        }
    }
    $sSqlQueryTasks = "SELECT
           Tasks.f14840 AS Subsection
           , Tasks.f10440 AS JobName
            , Tasks.f10480 AS PrintOutsPdf
        FROM
            " . DATA_TABLE . get_table_id(620) . " AS Tasks
        WHERE
           Tasks.f12530 = '".$sProgramForYearL3."'
           AND Tasks.status = 0";
    if($vProgramForYearData = sql_query($sSqlQueryProgramForYear)){
        if ($vProgramForYearRow = sql_fetch_assoc($vProgramForYearData)) {
            $sSubsectionP3 = $vProgramForYearRow['Subsection'];
            $sJobNameT3 = $vProgramForYearRow['JobName'];
            $sPrintOutsPdf = $vProgramForYearRow['PrintOutsPdf'];
        }
    }
}


$result = sql_select_field("" . SCHEMES_TABLE . "", "color3", "active='1'");
$row = sql_fetch_assoc($result);

$smarty->assign("color3", $row['color3']);
$smarty->assign("lines", $vLines);
$smarty->assign("vGroups", $vGroups);
$smarty->assign("iGroupId", $iGroupId);
$smarty->assign("bIsAdmin", $bIsAdmin);
$smarty->assign("sCabinetH1", $sCabinetH1);
$smarty->assign("sFormatPlanL1", $sFormatPlanL1);
$smarty->assign("sWeekDayV1", $sWeekDayV1);
$smarty->assign("sDateTimeX1", $sDateTimeX1);
$smarty->assign("sTeacherFioFactD2", $sTeacherFioFactD2);
$smarty->assign("sDepartmentNameH2", $sDepartmentNameH2);
$smarty->assign("sDepartmentAddressL2", $sDepartmentAddressL2);
$smarty->assign("aTeachers", $aTeachers);
$smarty->assign("sAcademicYearU2", $sAcademicYearU2);
$smarty->assign("sProgramAgeX2", $sProgramAgeX2);

