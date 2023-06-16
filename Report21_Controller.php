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
$sProgramAgeX2 = "---";
$sAcademicYearU2 = "---";


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
        , if(Schedule.f13580 IS NULL, '', Schedule.f13580) AS FormatPlan
        , if(Schedule.f13630 IS NULL, '', Schedule.f13630) AS FormatPlan
        , if(Teacher.f9660 IS NULL, '', Teacher.f9660) AS TeacherFioFact
        , if(Departments.f9450 IS NULL, '', Departments.f9450) AS DepartmentName
        , if(Departments.f9460  IS NULL, '', Departments.f9460) AS DepartmentAddress
        , IFNULL((SELECT MAX(Classes.f12750) FROM " . DATA_TABLE . get_table_id(780) . "AS Classes  WHERE Classes.f12870 = Groups.id AND Classes.status = 0),'') AS MaxClassesDate
        , if(Groups.f11240 IS NULL, '', Groups.f11240) AS ClassStartDate
    FROM
        " . DATA_TABLE . get_table_id(720) . " AS Students
        INNER JOIN " . DATA_TABLE . get_table_id(700) . " AS Groups
            ON Students.f11570 = Groups.id
        LEFT JOIN " . DATA_TABLE . get_table_id(790) . " AS Schedule
            ON Schedule.f13550 = Groups.id
        LEFT JOIN " . DATA_TABLE . get_table_id(520) . " AS Teacher
            ON Groups.f11180 = Teacher.id
        LEFT JOIN " . DATA_TABLE . get_table_id(500) . " AS Departments
            ON Groups.f11150 = Departments.id
    WHERE
    (Groups.f11240 <>'" . $sDateZero . "' AND NOT Groups.f11240 IS NULL AND (Groups.f11270 IS NULL OR Groups.f11270 = '" . $sDateZero . "'
        OR (Groups.f11270 <>'" . $sDateZero . "' AND NOT Groups.f11270 IS NULL AND DATE_ADD(Groups.f11270, INTERVAL 30 DAY) >= '" . $sDateNow . "')))
        AND Groups.f11260 = '0'
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
    if ($iGroupId == $vGroupRow['GroupId']) {
        $sProgramAgeX2 = form_display($vGroupRow['ProgramAge']);
        $sFormatPlanL1 = form_display($vGroupRow['FormatPlan']);
        $sTeacherFioFactD2 = form_display($vGroupRow['TeacherFioFact']);
        $sDepartmentNameH2 = form_display($vGroupRow['sDepartmentNameH2']);
        $sDepartmentAddressL2 = form_display($vGroupRow['sDepartmentAddressL2']);
        $sMaxClassesDate = $vGroupRow['MaxClassesDate'];
        $sClassStartDate = $vGroupRow['ClassStartDate'];
    }
    $vTableData['ProgramAge'] = form_display($vGroupRow['ProgramAge']);
    $vTableData['FormatPlan'] = form_display($vGroupRow['FormatPlan']);
    $vTableData['TeacherFioMin'] = $vGroupRow['TeacherFioMin'];
    $vGroups['g'.$vGroupRow['GroupId']] = $vTableData;
}
$sAcademicYearU2 = "";



//Работа с таблице параметров отчёта
$iReportId = 410; //Id текущего отчёта
$aReportData = [];
//Данные для отчётоВ 970
if ($vResReportData = sql_query("SELECT f17790 FROM " . DATA_TABLE . get_table_id(970) ." WHERE f17780='" . $iReportId . "' AND status='0'")) {
    if ($vRowReportData = sql_fetch_assoc($vResReportData)) {
        $aReportData = json_decode($vRowReportData['f17790'], true);
        if($bdebug) echo $iReportId."--1<br>";
        if(!array_key_exists("iFaultData", $aReportData)){
            if($bdebug) echo $iReportId."--2<br>";
            $aReportData = ["iFaultData"=> 2.00, "iMinQtyClasses" => 10, "iMinQtyClassesSubdivision" => 3];
            data_update(970, array('status'=>'0', 'f17790'=>json_encode($aReportData)), "`f17780`='",$iReportId,"' AND status ='0'" );
        }
    }
}

if(!array_key_exists("iFaultData", $aReportData)){
    if($bdebug) echo $iReportId."--3<br>";
    $aReportData = ["iFaultData"=> 2.00, "iMinQtyClasses" => 10, "iMinQtyClassesSubdivision" => 3];
    data_insert(970, array('status'=>'0', 'f17780'=>$iReportId, 'f17790'=>json_encode($aReportData)));
}
//сохраняем данные
if (array_key_exists('iFaultData', $_REQUEST) && array_key_exists('iMinQtyClasses', $_REQUEST) && array_key_exists('iMinQtyClassesSubdivision', $_REQUEST)) {
    if($bdebug) echo $iReportId."--4<br>";
    if(floatval($aReportData['iFaultData']) != floatval($_REQUEST['iFaultData']) || intval($aReportData['iMinQtyClasses']) != intval($_REQUEST['iMinQtyClasses']) || intval($aReportData['iMinQtyClassesSubdivision']) != intval($_REQUEST['iMinQtyClassesSubdivision'])){
        if($bdebug) echo $iReportId."--5<br>";
        $aReportData = ["iFaultData"=> floatval($_REQUEST['iFaultData']), "iMinQtyClasses" => intval($_REQUEST['iMinQtyClasses']), "iMinQtyClassesSubdivision" => intval($_REQUEST['iMinQtyClassesSubdivision'])];
        data_update(970, array('f17790'=>json_encode($aReportData)), "`f17780`='",$iReportId,"' AND status ='0' ");
    }
}



//f11480 Название группы факт f11580 Дата зачисления f11590 Дата отчисления
$sSqlQueryStudents = "SELECT ClienCards.id as ChildrenId
    , ClienCards.f9750 as ChildrenFIO
    , Groups.f11090 AS GroupFactName
FROM
" . DATA_TABLE . get_table_id(720) . " AS Students
    INNER JOIN " . DATA_TABLE . get_table_id(530) . " AS ClienCards
        ON Students.f11460 = ClienCards.id
    INNER JOIN " . DATA_TABLE . get_table_id(700) . " AS Groups
        ON Students.f11480 = Groups.id
    WHERE
        Students.f11480 = '" . $iGroupId . "'
        AND ((Students.f11580 <='" . $sDateEnd . "' AND Students.f11590 IS NULL AND Students.f11580 <>'" . $sDateZero . "')
        OR (Students.f11580 <='" . $sDateEnd . "' AND Students.f11590 = '" . $sDateZero . "' AND Students.f11580 <>'" . $sDateZero . "')
        OR (Students.f11580 <>'" . $sDateZero . "' AND Students.f11580 <='" . $sDateEnd . "' AND Students.f11590 >= '" . $sDateBeg . "' AND Students.f11590 <>'" . $sDateZero . "'))
        AND Students.status = 0
    ORDER BY ChildrenFIO";
//                AND GradesClass.f17280 ='".$vGroups['g'.$iGroupId]['ClassFormat']."'
if($vStudentsData = sql_query($sSqlQueryStudents)) {
    while ($vStudentRow = sql_fetch_assoc($vStudentsData)) {
        $iQtyClasses = 0;
    //810 Оценки на занятии f14680 ФИО ребенка f17280 Формат Факт f17260 Пр. возраст Дата f14820 Был? f14700
        $sSqlQueryGradesClass = "SELECT IFNULL(COUNT(*), 0) AS QtyClasses FROM " . DATA_TABLE . get_table_id(810) . " AS GradesClass
            WHERE
                GradesClass.f14680 = ".$vStudentRow['ChildrenId']."
                AND GradesClass.f17260 ='".$vGroups['g'.$iGroupId]['ProgramAge']."'
                AND GradesClass.f14820 >= '".$sDateBeg."' AND GradesClass.f14820 <= '".$sDateEnd."'
                AND if(GradesClass.f14700 IS NULL, '', GradesClass.f14700) = ''
                AND GradesClass.status = 0";
        if($vResultGradesClass = sql_query($sSqlQueryGradesClass)) {
            if($vRowGradesClass = sql_fetch_assoc($vResultGradesClass)){
                //echo $vGroupRow['GroupId']."___".$iWorkingOff."---".$vRowMisses['MissesCount']."---<br>";
                $iQtyClasses = $vRowGradesClass ['QtyClasses'];
            }
        }
        $sSqlReportState = "SELECT Id AS ReportStateId, f17840 AS UniqueId, f17940 AS ReportState
            FROM " . DATA_TABLE . get_table_id(980) ."
            WHERE
                f17850='" . $vStudentRow['ChildrenId'] . "'
                AND f17860='" . $iGroupId . "'
                AND f17870='" . $sDateBeg . "'
                AND f17880='" . $sDateEnd . "'
                AND f17890='" . floatval($aReportData['iFaultData']) . "'
                AND f17900='" . intval($aReportData['iMinQtyClasses']) . "'
                AND f17910='" . intval($aReportData['iMinQtyClassesSubdivision'])  . "'
                AND f17920='" . ($bIsPerfomance ? "1": "0") . "'
                AND status='0'";
        $iReportStateId = 0;
        $iReportState = -1;
        $sReportUniqueId = "";
        if ($vResReportState = sql_query($sSqlReportState)) {
            if ($vRowReportState = sql_fetch_assoc($vResReportState)) {
                $iReportStateId = $vRowReportState['ReportStateId'];
                $sReportUniqueId = $vRowReportState['UniqueId'];
                $iReportState = $vRowReportState['ReportState'];
                if($bdebug) echo "vResReportState1--1<br>";
            }
        }


        $vLines[] = array("ChildrenId" => $vStudentRow['ChildrenId'], "ChildrenFIO"=> $vStudentRow['ChildrenFIO'], "QtyClasses" => $iQtyClasses, "ReportState" => $iReportState, "ReportStateId" => $iReportStateId, "ReportUniqueId" => $sReportUniqueId);
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

$smarty->assign("sAcademicYearU2", $sAcademicYearU2);
$smarty->assign("sProgramAgeX2", $sProgramAgeX2);

