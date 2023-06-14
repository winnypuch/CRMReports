<?php
$bdebug = false;
if($bdebug) "<pre>".var_dump($_REQUEST)."</pre><br>";
$aMonths = array(1 => 'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь');
if ($_REQUEST['sDateBeg'] && $_REQUEST['sDateEnd']) {
    $iYear = intval($_REQUEST['year']);
    $sDateBeg = date("Y-m-d 00:00:00", strtotime(form_eng_time($_REQUEST['sDateBeg'])));
    $sDateEnd = date("Y-m-d 00:00:00", strtotime(form_eng_time($_REQUEST['sDateEnd'])));
} else {
    $iYear = date("Y");
    $sDateBeg = date("Y-m-d 00:00:00", mktime(0, 0, 0, date("m"), 1, date("Y")));
    $sDateEnd = date("Y-m-d 00:00:00", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
}
if ($_REQUEST['iGroupId']) {
    $iGroupId = $_REQUEST['iGroupId'];
    $bIsPerfomance = array_key_exists('bIsPerfomance', $_REQUEST) ? true: false;

} else {
    $iGroupId = 0;
    $bIsPerfomance = true;
}

$sDateZero = '0000-00-00 00:00:00';
$dDateBeg = new DateTime($sDateBeg);
$dDateEnd = new DateTime($sDateEnd);
$vLines = [];
$sDateNow = date('Y-m-d 00:00:00');
$iFaultData = 0;
$iMinQtyClasses = 0;
$iMinQtyClassesSubdivision = 0;
$bIsAdmin = true;

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
if ($result = sql_select_field(USERS_TABLE, "id", "id='" . $iUserId . "' and group_id IN (790, 800)")) {
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
    }
}
//f11260 - Фиктивная группа
//f11140 - Программный возраст
//f13580 - Формат план
$sSqlQueryGroup = "SELECT DISTINCT
    Groups.id AS GroupId
    , Groups.f11090 AS GroupName
    , if(Groups.f11140 IS NULL, '', Groups.f11140) AS ProgramAge
    , if(Schedule.f13580 IS NULL, '', Schedule.f13580) AS ClassFormat
    , if(Teacher.f9660 IS NULL, '', Teacher.f9660) AS TeacherFioMin
    FROM
        " . DATA_TABLE . get_table_id(720) . " AS Students
        INNER JOIN " . DATA_TABLE . get_table_id(700) . " AS Groups
            ON Students.f11570 = Groups.id
        LEFT JOIN " . DATA_TABLE . get_table_id(790) . " AS Schedule
            ON Schedule.f13550 = Groups.id
        LEFT JOIN " . DATA_TABLE . get_table_id(520) . " AS Teacher
            ON Groups.f11160 = Teacher.id
    WHERE
    ((Groups.f11240 <='" . $sDateEnd . "' AND Groups.f11240 <>'" . $sDateZero . "' AND (Groups.f11270 IS NULL OR Groups.f11270 = '" . $sDateZero . "'))
        OR (Groups.f11240 <='" . $sDateEnd . "' AND Groups.f11240 <>'" . $sDateZero . "' AND Groups.f11270 >= '" . $sDateBeg . "' AND Groups.f11270 <> '" . $sDateZero . "' AND NOT Groups.f11270 IS NULL))
        AND Groups.f11260 = '0'
        AND Groups.status = 0";
//проверка грыппы на пользователя педагога
if ($iTeacherId != 0) {
    $sSqlQueryGroup = $sSqlQueryGroup . " AND Groups.f11180='" . $iTeacherId . "'";
    $bIsAdmin = false;
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
    $vTableData['ProgramAge'] = form_display($vGroupRow['ProgramAge']);
    $vTableData['ClassFormat'] = form_display($vGroupRow['ClassFormat']);
    $vTableData['TeacherFioMin'] = $vGroupRow['TeacherFioMin'];
    $vGroups['g'.$vGroupRow['GroupId']] = $vTableData;
}
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


//f10700 1) D умножить на число из таблицы Педагоги поле “Ставка мини. очно” тянем по полю ФИО педагога сокр.
//f10690 2) F умножить на число из таблицы Педагоги (тянем аналогично) поле “Ставка очно”
//f10750 3) H умножить на число из таблицы Педагоги (тянем аналогично) поле “Ставка мин. инд.”
//f10740 (J+M)* число из таблицы Педагоги поле “Ставка мин. онлайн” тянем по полю ФИО педагога сокр.
//f10710 L* число из таблицы Педагоги поле “Ставка онлайн дошк.” тянем по полю ФИО педагога сокр. дошкольный
//f10720 O* число из таблицы Педагоги поле “Ставка онлайн. шк.” тянем по полю ФИО педагога сокр.
//print_r($_REQUEST);
//Если нужно добавить данные в таблицу
if (intval($_REQUEST['ChildrenId']) > 0 && (intval($_REQUEST['Report']) == 1 || intval($_REQUEST['SendReport']) == 1)) {

    if(intval($_REQUEST['Report']) == 1){
        $aResData = SetState($iUserId, 0, $vGroups, $iGroupId, intval($_REQUEST['ChildrenId']), $bIsPerfomance, $aReportData, $sDateBeg, $sDateEnd, $dDateBeg, $dDateEnd, $bdebug);
    } else{
        if(intval($_REQUEST['SendReport']) == 1){
            $aResData = SetState($iUserId, 1, $vGroups, $iGroupId, intval($_REQUEST['ChildrenId']), $bIsPerfomance, $aReportData, $sDateBeg, $sDateEnd, $dDateBeg, $dDateEnd, $bdebug);
        }
    }
} else {
    if (intval($_REQUEST['SendAllReport']) > 0 && $_REQUEST['SendAllReportData']) {
        $aChildReport = explode(";", $_REQUEST['SendAllReportData']);
        foreach($aChildReport as $key => $value){
            if(intval($value) > 0)
                SetState($iUserId, 1, $vGroups, $iGroupId, intval($value), $bIsPerfomance, $aReportData, $sDateBeg, $sDateEnd, $dDateBeg, $dDateEnd, $bdebug);
        }
    } else {
        if (intval($_REQUEST['CreateAllReport']) > 0 && $_REQUEST['SendAllReportData']) {
            $aChildReport = explode(";", $_REQUEST['SendAllReportData']);
            foreach($aChildReport as $key => $value){
                if(intval($value) > 0)
                    SetState($iUserId, 0, $vGroups, $iGroupId, intval($value), $bIsPerfomance, $aReportData, $sDateBeg, $sDateEnd, $dDateBeg, $dDateEnd, $bdebug);
            }
        }
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
$smarty->assign("months", $aMonths);
$smarty->assign("iYear", $iYear);
$smarty->assign("sDateBeg", $dDateBeg->format('d.m.Y'));
$smarty->assign("sDateEnd", $dDateEnd->format('d.m.Y'));
$smarty->assign("lines", $vLines);
$smarty->assign("vGroups", $vGroups);
$smarty->assign("iGroupId", $iGroupId);
$smarty->assign("bIsAdmin", $bIsAdmin);
$smarty->assign("bIsPerfomance", $bIsPerfomance);
$smarty->assign("iFaultData", $aReportData['iFaultData']);
$smarty->assign("iMinQtyClasses", $aReportData['iMinQtyClasses']);
$smarty->assign("iMinQtyClassesSubdivision", $aReportData['iMinQtyClassesSubdivision']);

function GenerateReport($vGroups, $iGroupId, $iChildrenId, $bIsPerfomance, $aReportData, $sDateBeg, $sDateEnd, $dDateBeg, $dDateEnd, $bdebug){
    //$vGroups['g'.$iGroupId]['TeacherFioMin']
    //$vGroups['g'.$iGroupId]['ClassFormat']
    //$vGroups['g'.$iGroupId]['ProgramAge']
    //header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    //810 Оценки на занятии f14680 ФИО ребенка f17280 Формат Факт
    //f17260 Пр. возраст
    //Дата f14820
    //Был? f14700
    //Оценка 1.1 f14470 Оценка 1.2 f14480 Подраздел 1 f17180 Результат 1 f14630
    //Оценка 2.1 f14490 Оценка 2.2 f14500 Подраздел 2 f17190 Результат 2 f14640
    //Оценка 3.1 f14510 Оценка 3.2 f14520 Подраздел 3 f17200 Результат 3 f14650
    //Оценка 4.1 f14530 Оценка 4.2 f14540 Подраздел 4 f17210 Результат 4 f14660
                        //AND GradesClass1.f17280 ='".$vGroups['g'.$iGroupId]['ClassFormat']."'
                        //    AND GradesClass2.f17280 ='".$vGroups['g'.$iGroupId]['ClassFormat']."'
                        //                        AND GradesClass3.f17280 ='".$vGroups['g'.$iGroupId]['ClassFormat']."'
                        //                                            AND GradesClass4.f17280 ='".$vGroups['g'.$iGroupId]['ClassFormat']."'
    $sSqlQueryGradesClass = "SELECT AllRes.Subsection AS Subsection, SUM(AllRes.QtyClasses) AS QtyClasses FROM
            (
                SELECT
                    GradesClass1.f17180 AS Subsection, IFNULL(COUNT(GradesClass1.f17180), 0) AS QtyClasses
                FROM " . DATA_TABLE . get_table_id(810) . " AS GradesClass1
                WHERE
                    GradesClass1.f14680 = ".$iChildrenId."

                    AND GradesClass1.f17260 ='".$vGroups['g'.$iGroupId]['ProgramAge']."'
                    AND GradesClass1.f14820 >= '".$sDateBeg."' AND GradesClass1.f14820 <= '".$sDateEnd."'
                    AND if(GradesClass1.f14700 IS NULL, '', GradesClass1.f14700) = ''
                    AND (if(GradesClass1.f14470 IS NULL, '', GradesClass1.f14470) <> ''
                    OR if(GradesClass1.f14480 IS NULL, '', GradesClass1.f14480) <> '')
                    AND GradesClass1.status = 0
                    AND if(GradesClass1.f17180 IS NULL, '', GradesClass1.f17180) <> ''
                GROUP BY GradesClass1.f17180
                UNION ALL
                SELECT
                    GradesClass2.f17190 AS Subsection, IFNULL(COUNT(GradesClass2.f17190), 0) AS QtyClasses
                FROM " . DATA_TABLE . get_table_id(810) . " AS GradesClass2
                WHERE
                    GradesClass2.f14680 = ".$iChildrenId."

                    AND GradesClass2.f17260 ='".$vGroups['g'.$iGroupId]['ProgramAge']."'
                    AND GradesClass2.f14820 >= '".$sDateBeg."' AND GradesClass2.f14820 <= '".$sDateEnd."'
                    AND if(GradesClass2.f14700 IS NULL, '', GradesClass2.f14700) = ''
                    AND (if(GradesClass2.f14490 IS NULL, '', GradesClass2.f14490) <> ''
                    OR if(GradesClass2.f14500 IS NULL, '', GradesClass2.f14500) <> '')
                    AND if(GradesClass2.f17190 IS NULL, '', GradesClass2.f17190) <> ''
                    AND GradesClass2.status = 0
                GROUP BY GradesClass2.f17190
                UNION ALL
                SELECT
                    GradesClass3.f17200 AS Subsection, IFNULL(COUNT(GradesClass3.f17200), 0) AS QtyClasses
                FROM " . DATA_TABLE . get_table_id(810) . " AS GradesClass3
                WHERE
                    GradesClass3.f14680 = ".$iChildrenId."
                    AND GradesClass3.f17260 ='".$vGroups['g'.$iGroupId]['ProgramAge']."'
                    AND GradesClass3.f14820 >= '".$sDateBeg."' AND GradesClass3.f14820 <= '".$sDateEnd."'
                    AND if(GradesClass3.f14700 IS NULL, '', GradesClass3.f14700) = ''
                    AND (if(GradesClass3.f14510 IS NULL, '', GradesClass3.f14510) <> ''
                    OR if(GradesClass3.f14520 IS NULL, '', GradesClass3.f14520) <> '')
                    AND if(GradesClass3.f17200 IS NULL, '', GradesClass3.f17200) <> ''
                    AND GradesClass3.status = 0
                GROUP BY GradesClass3.f17200
                UNION ALL
                SELECT
                    GradesClass4.f17210 AS Subsection, IFNULL(COUNT(GradesClass4.f17210), 0) AS QtyClasses
                FROM " . DATA_TABLE . get_table_id(810) . " AS GradesClass4
                WHERE
                    GradesClass4.f14680 = ".$iChildrenId."
                    AND GradesClass4.f17260 ='".$vGroups['g'.$iGroupId]['ProgramAge']."'
                    AND GradesClass4.f14820 >= '".$sDateBeg."' AND GradesClass4.f14820 <= '".$sDateEnd."'
                    AND if(GradesClass4.f14700 IS NULL, '', GradesClass4.f14700) = ''
                    AND (if(GradesClass4.f14530 IS NULL, '', GradesClass4.f14530) <> ''
                    OR if(GradesClass4.f14540 IS NULL, '', GradesClass4.f14540) <> '')
                    AND if(GradesClass4.f17210 IS NULL, '', GradesClass4.f17210) <> ''
                    AND GradesClass4.status = 0
                GROUP BY GradesClass4.f17210
            ) AS AllRes
            GROUP BY Subsection
            HAVING QtyClasses >=".$aReportData['iMinQtyClassesSubdivision'];
    //if($bdebug) echo $sSqlQueryGradesClass;
    $iKey = 0;
    $iAllQtyClassesBalSumC =0;
    $iAllQtyClassesBalSumD =0;
    //GradesClass1.f17280 ='".$vGroups['g'.$iGroupId]['ClassFormat']."'
    //GradesClass2.f17280 ='".$vGroups['g'.$iGroupId]['ClassFormat']."'
    //GradesClass3.f17280 ='".$vGroups['g'.$iGroupId]['ClassFormat']."'
    //GradesClass4.f17280 ='".$vGroups['g'.$iGroupId]['ClassFormat']."'
    if($vResultGradesClass = sql_query($sSqlQueryGradesClass)) {
        while($vRowGradesClass = sql_fetch_assoc($vResultGradesClass)){
            //echo $vGroupRow['GroupId']."___".$iWorkingOff."---".$vRowMisses['MissesCount']."---<br>";
            $iQtyClasses = $vRowGradesClass ['QtyClasses'];

            $sSqlQueryBalSumC = "SELECT AllRes.Subsection AS Subsection, SUM(AllRes.QtyClasses) AS QtyClasses, SUM(AllRes.ResClasses) AS ResClasses FROM
            (
                SELECT
                    GradesClass1.f17180 AS Subsection, IFNULL(COUNT(GradesClass1.f17180), 0) AS QtyClasses, IFNULL(SUM(GradesClass1.f14630), 0) AS ResClasses
                FROM " . DATA_TABLE . get_table_id(810) . " AS GradesClass1
                WHERE
                    GradesClass1.f17260 ='".$vGroups['g'.$iGroupId]['ProgramAge']."'
                    AND GradesClass1.f14820 >= '".$sDateBeg."' AND GradesClass1.f14820 <= '".$sDateEnd."'
                    AND if(GradesClass1.f14700 IS NULL, '', GradesClass1.f14700) = ''
                    AND (if(GradesClass1.f14470 IS NULL, '', GradesClass1.f14470) <> ''
                    OR if(GradesClass1.f14480 IS NULL, '', GradesClass1.f14480) <> '')
                    AND if(GradesClass1.f17180 IS NULL, '', GradesClass1.f17180) = '".$vRowGradesClass['Subsection']."'
                    AND GradesClass1.status = 0
                GROUP BY GradesClass1.f17180
                UNION ALL
                SELECT
                    GradesClass2.f17190 AS Subsection, IFNULL(COUNT(GradesClass2.f17190), 0) AS QtyClasses, IFNULL(SUM(GradesClass2.f14640), 0) AS ResClasses
                FROM " . DATA_TABLE . get_table_id(810) . " AS GradesClass2
                WHERE
                    GradesClass2.f17260 ='".$vGroups['g'.$iGroupId]['ProgramAge']."'
                    AND GradesClass2.f14820 >= '".$sDateBeg."' AND GradesClass2.f14820 <= '".$sDateEnd."'
                    AND if(GradesClass2.f14700 IS NULL, '', GradesClass2.f14700) = ''
                    AND (if(GradesClass2.f14490 IS NULL, '', GradesClass2.f14490) <> ''
                    OR if(GradesClass2.f14500 IS NULL, '', GradesClass2.f14500) <> '')
                    AND if(GradesClass2.f17190 IS NULL, '', GradesClass2.f17190) = '".$vRowGradesClass ['Subsection']."'
                    AND GradesClass2.status = 0
                GROUP BY GradesClass2.f17190
                UNION ALL
                SELECT
                    GradesClass3.f17200 AS Subsection, IFNULL(COUNT(GradesClass3.f17200), 0) AS QtyClasses, IFNULL(SUM(GradesClass3.f14650), 0) AS ResClasses
                FROM " . DATA_TABLE . get_table_id(810) . " AS GradesClass3
                WHERE
                    GradesClass3.f17260 ='".$vGroups['g'.$iGroupId]['ProgramAge']."'
                    AND GradesClass3.f14820 >= '".$sDateBeg."' AND GradesClass3.f14820 <= '".$sDateEnd."'
                    AND if(GradesClass3.f14700 IS NULL, '', GradesClass3.f14700) = ''
                    AND (if(GradesClass3.f14510 IS NULL, '', GradesClass3.f14510) <> ''
                    OR if(GradesClass3.f14520 IS NULL, '', GradesClass3.f14520) <> '')
                    AND if(GradesClass3.f17200 IS NULL, '', GradesClass3.f17200) = '".$vRowGradesClass ['Subsection']."'
                    AND GradesClass3.status = 0
                GROUP BY GradesClass3.f17200
                UNION ALL
                SELECT
                    GradesClass4.f17210 AS Subsection, IFNULL(COUNT(GradesClass4.f17210), 0) AS QtyClasses, IFNULL(SUM(GradesClass4.f14660), 0) AS ResClasses
                FROM " . DATA_TABLE . get_table_id(810) . " AS GradesClass4
                WHERE
                    GradesClass4.f17260 ='".$vGroups['g'.$iGroupId]['ProgramAge']."'
                    AND GradesClass4.f14820 >= '".$sDateBeg."' AND GradesClass4.f14820 <= '".$sDateEnd."'
                    AND if(GradesClass4.f14700 IS NULL, '', GradesClass4.f14700) = ''
                    AND (if(GradesClass4.f14530 IS NULL, '', GradesClass4.f14530) <> ''
                    OR if(GradesClass4.f14540 IS NULL, '', GradesClass4.f14540) <> '')
                    AND if(GradesClass4.f17210 IS NULL, '', GradesClass4.f17210) = '".$vRowGradesClass ['Subsection']."'
                    AND GradesClass4.status = 0
                GROUP BY GradesClass4.f17210
            ) AS AllRes
            GROUP BY Subsection";
            //if($bdebug) echo $sSqlQueryBalSumC."<br>";
            $iQtyClassesBalSumC = 0;

            if($vResultBalSumC = sql_query($sSqlQueryBalSumC)) {
                if($vRowBalSumC = sql_fetch_assoc($vResultBalSumC)){
                    //echo $vGroupRow['GroupId']."___".$iWorkingOff."---".$vRowMisses['MissesCount']."---<br>";
                    if($vRowBalSumC ['QtyClasses'] != 0)
                        $iQtyClassesBalSumC = $vRowBalSumC ['ResClasses'] / $vRowBalSumC ['QtyClasses'];
                }
            }
            //AND GradesClass1.f17280 ='".$vGroups['g'.$iGroupId]['ClassFormat']."'
            //AND GradesClass2.f17280 ='".$vGroups['g'.$iGroupId]['ClassFormat']."'
            //                    AND GradesClass3.f17280 ='".$vGroups['g'.$iGroupId]['ClassFormat']."'
            //                    AND GradesClass4.f17280 ='".$vGroups['g'.$iGroupId]['ClassFormat']."'
            $sSqlQueryBalSumD = "SELECT AllRes.Subsection AS Subsection, SUM(AllRes.QtyClasses) AS QtyClasses, SUM(AllRes.ResClasses) AS ResClasses FROM
            (
                SELECT
                    GradesClass1.f17180 AS Subsection, IFNULL(COUNT(GradesClass1.f17180), 0) AS QtyClasses, IFNULL(SUM(GradesClass1.f14630), 0) AS ResClasses
                FROM " . DATA_TABLE . get_table_id(810) . " AS GradesClass1
                WHERE
                    GradesClass1.f14680 = ".$iChildrenId."
                    AND GradesClass1.f17260 ='".$vGroups['g'.$iGroupId]['ProgramAge']."'
                    AND GradesClass1.f14820 >= '".$sDateBeg."' AND GradesClass1.f14820 <= '".$sDateEnd."'
                    AND if(GradesClass1.f14700 IS NULL, '', GradesClass1.f14700) = ''
                    AND (if(GradesClass1.f14470 IS NULL, '', GradesClass1.f14470) <> ''
                    OR if(GradesClass1.f14480 IS NULL, '', GradesClass1.f14480) <> '')
                    AND if(GradesClass1.f17180 IS NULL, '', GradesClass1.f17180) = '".$vRowGradesClass ['Subsection']."'
                    AND GradesClass1.status = 0
                GROUP BY GradesClass1.f17180
                UNION ALL
                SELECT
                    GradesClass2.f17190 AS Subsection, IFNULL(COUNT(GradesClass2.f17190), 0) AS QtyClasses, IFNULL(SUM(GradesClass2.f14640), 0) AS ResClasses
                FROM " . DATA_TABLE . get_table_id(810) . " AS GradesClass2
                WHERE
                    GradesClass2.f14680 = ".$iChildrenId."
                    AND GradesClass2.f17260 ='".$vGroups['g'.$iGroupId]['ProgramAge']."'
                    AND GradesClass2.f14820 >= '".$sDateBeg."' AND GradesClass2.f14820 <= '".$sDateEnd."'
                    AND if(GradesClass2.f14700 IS NULL, '', GradesClass2.f14700) = ''
                    AND (if(GradesClass2.f14490 IS NULL, '', GradesClass2.f14490) <> ''
                    OR if(GradesClass2.f14500 IS NULL, '', GradesClass2.f14500) <> '')
                    AND if(GradesClass2.f17190 IS NULL, '', GradesClass2.f17190) = '".$vRowGradesClass ['Subsection']."'
                    AND GradesClass2.status = 0
                GROUP BY GradesClass2.f17190
                UNION ALL
                SELECT
                    GradesClass3.f17200 AS Subsection, IFNULL(COUNT(GradesClass3.f17200), 0) AS QtyClasses, IFNULL(SUM(GradesClass3.f14650), 0) AS ResClasses
                FROM " . DATA_TABLE . get_table_id(810) . " AS GradesClass3
                WHERE
                    GradesClass3.f14680 = ".$iChildrenId."
                    AND GradesClass3.f17260 ='".$vGroups['g'.$iGroupId]['ProgramAge']."'
                    AND GradesClass3.f14820 >= '".$sDateBeg."' AND GradesClass3.f14820 <= '".$sDateEnd."'
                    AND if(GradesClass3.f14700 IS NULL, '', GradesClass3.f14700) = ''
                    AND (if(GradesClass3.f14510 IS NULL, '', GradesClass3.f14510) <> ''
                    OR if(GradesClass3.f14520 IS NULL, '', GradesClass3.f14520) <> '')
                    AND if(GradesClass3.f17200 IS NULL, '', GradesClass3.f17200) = '".$vRowGradesClass ['Subsection']."'
                    AND GradesClass3.status = 0
                GROUP BY GradesClass3.f17200
                UNION ALL
                SELECT
                    GradesClass4.f17210 AS Subsection, IFNULL(COUNT(GradesClass4.f17210), 0) AS QtyClasses, IFNULL(SUM(GradesClass4.f14660), 0) AS ResClasses
                FROM " . DATA_TABLE . get_table_id(810) . " AS GradesClass4
                WHERE
                    GradesClass4.f14680 = ".$iChildrenId."
                    AND GradesClass4.f17260 ='".$vGroups['g'.$iGroupId]['ProgramAge']."'
                    AND GradesClass4.f14820 >= '".$sDateBeg."' AND GradesClass4.f14820 <= '".$sDateEnd."'
                    AND if(GradesClass4.f14700 IS NULL, '', GradesClass4.f14700) = ''
                    AND (if(GradesClass4.f14530 IS NULL, '', GradesClass4.f14530) <> ''
                    OR if(GradesClass4.f14540 IS NULL, '', GradesClass4.f14540) <> '')
                    AND if(GradesClass4.f17210 IS NULL, '', GradesClass4.f17210) = '".$vRowGradesClass ['Subsection']."'
                    AND GradesClass4.status = 0
                GROUP BY GradesClass4.f17210
            ) AS AllRes
            GROUP BY Subsection";
            //if($bdebug) echo $sSqlQueryBalSumD."<br>";
            $iQtyClassesBalSumD = 0;

            if($vResultBalSumD = sql_query($sSqlQueryBalSumD)) {
                if($vRowBalSumD = sql_fetch_assoc($vResultBalSumD)){
                    if($bdebug) echo $vRowBalSumD ['ResClasses']."___".$vRowBalSumD ['QtyClasses']."---<br>";
                    if($vRowBalSumD ['QtyClasses'] != 0)
                        $iQtyClassesBalSumD = $vRowBalSumD ['ResClasses'] / $vRowBalSumD ['QtyClasses'];
                }
            }
            $arLessons[$iKey] = $vRowGradesClass['Subsection'];
            $arValuesAvg[$iKey] = round($iQtyClassesBalSumC * 100);
            $arValues[$iKey] = round($iQtyClassesBalSumD * 100);
            //echo $iQtyClassesBalSumC . "---". $iQtyClassesBalSumD ."---". $aReportData["iFaultData"];
            $arRows[$iKey] = ($arValues[$iKey] >= $arValuesAvg[$iKey] - $aReportData["iFaultData"]) ? "green" : "yellow";
            $iAllQtyClassesBalSumC+=$iQtyClassesBalSumC;
            $iAllQtyClassesBalSumD+=$iQtyClassesBalSumD;
            $iKey++;

        }
    }
    $sChildrenFIO ="";
    if ($vResReportData = sql_query("SELECT ClienCards.f9750 as ChildrenFIO FROM " . DATA_TABLE . get_table_id(530) . " AS ClienCards WHERE ClienCards.id = '" . $iChildrenId . "'")) {
        if ($vRowReportData = sql_fetch_assoc($vResReportData)) {
            $sChildrenFIO = $vRowReportData['ChildrenFIO'];
        }
    }

    $arData = [$vGroups['g'.$iGroupId]['TeacherFioMin'], $sChildrenFIO, $vGroups['g'.$iGroupId]['ClassFormat'], $vGroups['g'.$iGroupId]['ProgramAge']];
    $arData[4] = "от ".$dDateBeg->format('d.m.Y')." до ".$dDateEnd->format('d.m.Y');
    if($iKey > 0) {
        $arData[5] = form_local_number(($iAllQtyClassesBalSumD/$iKey) * 3 + 2, '2/10');
        $arData[6] = form_local_number(($iAllQtyClassesBalSumC/$iKey) * 3 + 2, '2/10');
    } else{
        $arData[5] = 0;
        $arData[6] = 0;
    }
    return ["arData" => $arData, "bIsPerfomance" => $bIsPerfomance, "arRows" => $arRows, "arLessons"=>$arLessons, "arValues"=>$arValues, "arValuesAvg" => $arValuesAvg];
}

//iSetReportState 0 - Отчёт сформирован
//1 - Отчёт сформирован и помечен к отправке
//2 - Отчёт отправлен
function SetState($iUserId, $iSetReportState, $vGroups, $iGroupId, $iChildrenId, $bIsPerfomance, $aReportData, $sDateBeg, $sDateEnd, $dDateBeg, $dDateEnd, $bdebug){
        //Отчёты для отправки 980
    // Уникальное поле f17840
    // Id ученика f17850
    // id группы f17860
    // Дата от f17870
    // Дата до f17880
    // Погрешность f17890
    // Минимальное кол-во занятий f17900
    // Минимальное кол-во занятий по подразделу f17910
    // Добавить успеваемость в отчет f17920
    // Отчёт f17930
    // Состояние отчёта f17940
    // Кто изменил f17990
    $sSqlReportState = "SELECT Id AS ReportStateId, f17840 AS UniqueId, f17930 AS Report, f17940 AS ReportState
            FROM " . DATA_TABLE . get_table_id(980) ."
            WHERE
                f17850='" . $iChildrenId . "'
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
    $aReportGen=[];
    if ($vResReportState = sql_query($sSqlReportState)) {
        if ($vRowReportState = sql_fetch_assoc($vResReportState)) {
            $iReportStateId = $vRowReportState['ReportStateId'];
            $sReportUniqueId = $vRowReportState['UniqueId'];
            $iReportState = $vRowReportState['ReportState'];
            $aReportGen = json_decode($vRowReportState['Report'], true);
            if($bdebug) echo "vResReportState--1<br>";
        }
    }

    if($iReportStateId == 0){
        $aReportGen = GenerateReport($vGroups, $iGroupId, $iChildrenId, $bIsPerfomance, $aReportData, $sDateBeg, $sDateEnd, $dDateBeg, $dDateEnd, $bdebug);
        $sReportUniqueId=hash('crc32', $iGroupId."-".$iChildrenId."-".$sDateBeg."-".$sDateEnd);
        //f17640 Фио родителя | телефон
        //родители 950 Телефон f17580
        //Telegram 960 Телефон f17690 Telegram ID f17700
        //Telegram IDs f18000
        $sSqlTelegrams = "SELECT Telegrams.f17700 AS TelegramId
        FROM  " . DATA_TABLE . get_table_id(530) . " AS ClienCards
            INNER JOIN " . DATA_TABLE . get_table_id(950) ." AS Parents
                ON ClienCards.f17640 = Parents.Id
            INNER JOIN " . DATA_TABLE . get_table_id(960) ." AS Telegrams
                ON Parents.f17580 = Telegrams.f17690
        WHERE
            ClienCards.Id='" . $iChildrenId . "'
            AND Parents.status='0'
            AND Telegrams.status='0'";
        $aTelegramIds = [];
        if ($vResTelegrams = sql_query($sSqlTelegrams)) {
            while($vRowTelegrams = sql_fetch_assoc($vResTelegrams)) {
                $aTelegramIds[] = $vRowTelegrams['TelegramId'];
            }
        }
        data_insert(980, array('status'=>'0', 'f17840'=>$sReportUniqueId, 'f17850'=>$iChildrenId, 'f17860'=>$iGroupId, 'f17870'=>$sDateBeg, 'f17880'=>$sDateEnd, 'f17890'=>floatval($aReportData['iFaultData']),'f17900'=>intval($aReportData['iMinQtyClasses']),'f17910'=>intval($aReportData['iMinQtyClassesSubdivision']),'f17920'=>($bIsPerfomance ? "1": "0"),'f17930'=>json_encode($aReportGen), 'f17940'=>$iSetReportState, 'f17990'=>$iUserId, 'f18000'=>json_encode($aTelegramIds) ));
        $sSqlReportState = "SELECT Id AS ReportStateId
                FROM " . DATA_TABLE . get_table_id(980) ."
                WHERE
                    f17850='" . $iChildrenId . "'
                    AND f17860='" . $iGroupId . "'
                    AND f17870='" . $sDateBeg . "'
                    AND f17880='" . $sDateEnd . "'
                    AND f17890='" . floatval($aReportData['iFaultData']) . "'
                    AND f17900='" . intval($aReportData['iMinQtyClasses']) . "'
                    AND f17910='" . intval($aReportData['iMinQtyClassesSubdivision'])  . "'
                    AND f17920='" . ($bIsPerfomance ? "1": "0") . "'
                    AND status='0'";
        if ($vResReportState = sql_query($sSqlReportState)) {
            if ($vRowReportState = sql_fetch_assoc($vResReportState)) {
                $iReportStateId = $vRowReportState['ReportStateId'];
                $sReportUniqueId=hash('crc32', $iGroupId."-".$iChildrenId."-".$sDateBeg."-".$sDateEnd.$iReportStateId);
                data_update(980, array( 'f17840'=>$sReportUniqueId, 'f17990'=>$iUserId), "`Id`='",$iReportStateId,"' AND status ='0' ");
                if($bdebug) echo "vResReportState--2__".$sReportUniqueId."<br>";
            }
        }
    } else{
        if($iSetReportState > 0)
            data_update(980, array( 'f17940'=>$iSetReportState, 'f17990'=>$iUserId), "`Id`='",$iReportStateId,"' AND status ='0' ");
        if($bdebug) echo "vResReportState--3__".$iReportStateId."<br>";
    }
    return $aReportGen;
}