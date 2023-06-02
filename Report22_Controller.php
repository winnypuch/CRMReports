<?php
$months = array(1 => 'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь');
if ($_REQUEST['year'] && $_REQUEST['month']) {
    $month = intval($_REQUEST['month']);
    $year = intval($_REQUEST['year']);
} else {
    $month = date("m");
    $year = date("Y");
}

$sDateFirst = date("Y-m-d", mktime(0, 0, 0, $month, 1, $year));
$sDateZero = '0000-00-00 00:00:00';

$dDateNext = new DateTime($sDateFirst);
$dDateNext->modify('1 month');
$sDateNext = $dDateNext->format('Y-m-d 00:00:00');
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
    $bisAdmin = false;
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
//Работа с таблице параметров отчёта
$iReportId = 410; //Id текущего отчёта
$aReportData = ["iFaultData"=> 2.00, "iMinQtyClasses" => 10, "iMinQtyClassesSubdivision" => 3];
//Данные для отчётоВ 790
if ($vResReportData = sql_select_field(790, "f17790", "f17780='" . $iReportId . "'")) {
    if ($vRowReportData = sql_fetch_assoc($vResReportData)) {
        $aReportData = json_decode($vResReportData['f17790']);
    }
}
//сохраняем данные
if ($_REQUEST['iFaultData'] && $_REQUEST['iMinQtyClasses'] && $_REQUEST['iMinQtyClassesSubdivision']) {
    if($aReportData['iFaultData'] != $_REQUEST['iFaultData'] || $aReportData['iMinQtyClasses'] != $_REQUEST['iMinQtyClasses'] || $aReportData['iMinQtyClassesSubdivision'] != $_REQUEST['iMinQtyClassesSubdivision']){
        data_update(790, array('status'=>'0', 'f17790'=>json_encode($aReportData)), "`f17780`='",$iReportId,"'" );
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
if ($_REQUEST['update_data'] == 1 && intval($_REQUEST['update_sum']) > 0 && intval($_REQUEST['update_teacherid']) >0) {
    //Расходы Декарт 940
    //f17420 Дата
    //f17480 Кому (ФИО)
    //f17450 Сумма
    //f17470 Статья
    //f17430 Пользователь
    //echo $_REQUEST['update_sum'] ."-". $_REQUEST['update_teacherid'] ."-". $_REQUEST['csrf'];
    data_insert(940, array('status'=>'0', 'f17430'=>$iUserId, 'f17480' => intval($_REQUEST['update_teacherid']),'f17420'=>$sDateNow, 'f17470'=>'ЗП', 'f17450' => intval($_REQUEST['update_sum'])));
}

$sTeachersSqlQuery = "SELECT
                    Users.Id AS UserId
                    , Users.fio AS UserFIO
                    , Teachers.Id AS TeacherId
                    , Teachers.f9660 AS TeacherFIO
                    , IFNULL(Teachers.f10700, 0) AS RateMinYea
                    , IFNULL(Teachers.f10690, 0) AS RateYea
                    , IFNULL(Teachers.f10750, 0) AS RateMinInd
                    , IFNULL(Teachers.f10740, 0) AS RateMinOnline
                    , IFNULL(Teachers.f10710, 0) AS RateOnlinePreSchool
                    , IFNULL(Teachers.f10720, 0) AS RateOnlineSchool
                FROM
                " . USERS_TABLE . " AS Users
                    INNER JOIN " . DATA_TABLE . get_table_id(520) . " AS Teachers
                        ON Users.id = Teachers.f9630

                    ";//AND arc = 0
                //WHERE
                //    group_id = 790
$iEmployeesTable = 1;
//Если юзер преподователь
if ($vUserRes = sql_select_field(USERS_TABLE, "id", "id='" . $iUserId . "' and group_id=790")) {
    if ($$vUserRow = sql_fetch_assoc($vUserRes)) {
        $sTeachersSqlQuery = $sTeachersSqlQuery . " WHERE Users.Id = ".$iUserId;

    }
}

//Преподователи
if ($vTeachersRes = sql_query($sTeachersSqlQuery)) {
    while ($vTeachersRow = sql_fetch_assoc($vTeachersRes)) {
        $iDCol = 0;
        $iECol = 0;
        $iFCol = 0;
        $iHCol = 0;
        $iJCol = 0;
        $iKCol = 0;
        $iLCol = 0;
        $iMCol = 0;
        $iNCol = 0;
        $iOCol = 0;
        $iQCol = 0;
        //780 - таблица  занятия
        //f13840 Дата|Группа
        //f12790 ФИО педагога сокр. 1 f12790] => 30
        //f12850 ФИО педагога сокр. 2
        // f13470 Оплата занятия
        // f12750 Дата
        // f17500 Дети к оплате
        // f12830 Формат план
        // f13450 Тип Группы
        $sClassesSqlQuery = "SELECT
            Classes.f13840 as DateGroup
            , IFNULL(Classes.f17500, 0) AS ChildrenToPay
            , IFNULL(Classes.f12830, '') AS PlanFormat
            , IFNULL(Classes.f13450, '') AS GroupType
            , if(Classes.f12790 = 0 OR Classes.f12790 IS NULL, 0, 1) AS Teacher1
            , if(Classes.f12850 = 0 OR Classes.f12850 IS NULL, 0, 1) AS Teacher2
         FROM
            " . DATA_TABLE . get_table_id(780) . " AS Classes
         WHERE
            (Classes.f12790 = " . $vTeachersRow['TeacherId'] . "
            OR Classes.f12850 = " . $vTeachersRow['TeacherId'] . ")
            AND Classes.f13470 ='Оплата'
            AND Classes.f12750 >= '" . $sDateFirst . "'
            AND Classes.f12750 <'" . $sDateNext . "'
            AND Classes.status = 0";
        //смотрим занятия
        if($vClassesData = sql_query($sClassesSqlQuery)){
            while ($vClassesRow = sql_fetch_assoc($vClassesData)) {
                //(Поле Дети к оплате разделить на кол-во педагогов) > 0 И
                //(Поле Дети к оплате разделить на кол-во педагогов) <= 2
                $iTeacherQty = $vClassesRow['Teacher1'] + $vClassesRow['Teacher2'];

                if($iTeacherQty > 0){
                    $iInPay = $vClassesRow['ChildrenToPay'] / $iTeacherQty;
                    if($vClassesRow['PlanFormat'] == "очно"){
                        if($vClassesRow['GroupType'] != "инд") {
                            if($iInPay >= 0 && $iInPay <= 2){
                                $iDCol++;
                            } elseif($iInPay > 2){
                                $iECol++;
                                $iFCol = $iFCol + $iInPay;//Считаем сумму по полю Дети к оплате разделить на кол-во педагогов по занятиям в колонке Е
                            }
                        }elseif($vClassesRow['GroupType'] == "инд"){
                            if($iInPay > 0)
                                $iHCol++;
                        }
                    }elseif($vClassesRow['PlanFormat'] == "онлайн"){
                        if($vClassesRow['GroupType'] == "сад") {
                            if($iInPay >= 0 && $iInPay <= 1.5){
                                $iJCol++;
                            } elseif($iInPay > 1.5){
                                $iKCol++;
                                $iLCol = $iLCol + $iInPay;//Считаем сумму по полю Дети к оплате разделить на кол-во педагогов по занятиям в колонке K
                            }
                        }elseif($vClassesRow['GroupType'] == "школа"){
                            if($iInPay >= 0 && $iInPay <= 2.5){
                                $iMCol++;
                            } elseif($iInPay > 2.5){
                                $iNCol++;
                                $iOCol = $iOCol + $iInPay;//Считаем сумму по полю Дети к оплате разделить на кол-во педагогов по занятиям в колонке N
                            }
                        }elseif($vClassesRow['GroupType'] == "инд"){
                            if($iInPay > 0)
                                $iQCol++;
                        }
                    }
                }
            }
        }

        $iSCol = 0;
        //Расходы педагоги 900
        //f15700 Дата
        //f15720 ФИО педагога
        //f15710 Сумма
        $sExpensesTeachersSqlQuery = "SELECT
                    IFNULL(SUM(ExpensesTeachers.f15710),0) AS Sum
                FROM
                    " . DATA_TABLE . get_table_id(900) . " AS ExpensesTeachers
                WHERE
                    ExpensesTeachers.f15720 = " . $vTeachersRow['TeacherId'] . "
                    AND ExpensesTeachers.f15700 >= '" . $sDateFirst . "'
                    AND ExpensesTeachers.f15700 <'" . $sDateNext . "'
                    AND ExpensesTeachers.status = 0";
        if ($vExpensesTeachersRes = sql_query($sExpensesTeachersSqlQuery)) {
            while ($vExpensesTeachersRow = sql_fetch_assoc($vExpensesTeachersRes)) {
                $iSCol = intval($vExpensesTeachersRow['Sum']);
            }
        }
        $iTCol = 0;
        //Подработка 910
        //f15850 Дата
        //f15840 ФИО педагога
        //f15860 К выплате
        $sPartTimeJobSqlQuery = "SELECT
                    IFNULL(SUM(PartTimeJob.f15860),0) AS Sum
                FROM
                    " . DATA_TABLE . get_table_id(910) . " AS PartTimeJob
                WHERE
                    PartTimeJob.f15840 = " . $vTeachersRow['TeacherId'] . "
                    AND PartTimeJob.f15850 >= '" . $sDateFirst . "'
                    AND PartTimeJob.f15850 <'" . $sDateNext . "'
                    AND PartTimeJob.status = 0";
        if ($vPartTimeJobRes = sql_query($sPartTimeJobSqlQuery)) {
            while ($vPartTimeRow = sql_fetch_assoc($vPartTimeJobRes)) {
                $iTCol = intval($vPartTimeRow['Sum']);
            }
        }

        $iZCol = 0;
        //Расходы Декарт 940
        //f17420 Дата
        //f17480 Кому (ФИО)
        //f17450 Сумма
        //f17470 Статья
        //f17430 Пользователь
        $sExspansesDecartSqlQuery = "SELECT
                    IFNULL(SUM(ExspansesDecart.f17450),0) AS Sum
                FROM
                    " . DATA_TABLE . get_table_id(940) . " AS ExspansesDecart
                WHERE
                    ExspansesDecart.f17480 = " . $vTeachersRow['TeacherId'] . "
                    AND ExspansesDecart.f17420 >= '" . $sDateFirst . "'
                    AND ExspansesDecart.f17420 <'" . $sDateNext . "'
                    AND ExspansesDecart.status = 0";
        if ($vExspansesDecartRes = sql_query($sExspansesDecartSqlQuery)) {
            while ($vExspansesDecartRow = sql_fetch_assoc($vExspansesDecartRes)) {
                $iZCol = intval($vExspansesDecartRow['Sum']);
            }
        }

        $iICol = $iDCol * $vTeachersRow['RateMinYea'] + $iFCol * $vTeachersRow['RateYea'] + $iHCol * $vTeachersRow['RateMinInd'];
        //echo $iDCol."*".$vTeachersRow['RateMinYea']."+".$iFCol."*".$vTeachersRow['RateYea']."+".$iHCol."*".$vTeachersRow['RateMinInd']."\n";
        $iRCol = ($iJCol + $iMCol) * $vTeachersRow['RateMinOnline'] + $iLCol * $vTeachersRow['RateOnlinePreSchool'] + $iOCol * $vTeachersRow['RateOnlineSchool'] + $iQCol * $vTeachersRow['RateMinInd'];
        $iYCol = $iICol + $iRCol + $iSCol + $iTCol;
        $iBCol = $iDCol + $iECol + $iJCol + $iKCol + $iMCol + $iNCol + $iQCol + $iHCol;
        if($iYCol > 0)
            $vLines[] = array("TeacherId" => $vTeachersRow['TeacherId'],  "A"=>$vTeachersRow['TeacherFIO'], "B"=>$iBCol, "D"=>$iDCol,"E"=>$iECol,"F"=>$iFCol,"H"=>$iHCol,"I"=>$iICol,"J"=>$iJCol,"K"=>$iKCol,"L"=>$iLCol,"M"=>$iMCol,"N"=>$iNCol,"O"=>$iOCol,"Q"=>$iQCol,"R"=>$iRCol,"S"=>$iSCol,"T"=>$iTCol,"Y"=>$iYCol,"Z"=>$iZCol);
    }
}


$result = sql_select_field("" . SCHEMES_TABLE . "", "color3", "active='1'");
$row = sql_fetch_assoc($result);

$smarty->assign("color3", $row['color3']);
$smarty->assign("months", $months);
$smarty->assign("month", $month);
$smarty->assign("year", $year);
$smarty->assign("lines", $vLines);
$smarty->assign("vGroups", $vGroups);
$smarty->assign("iGroupId", $iGroupId);
$smarty->assign("bIsAdmin", $bIsAdmin);
$smarty->assign("iFaultData", $aReportData['iFaultData']);
$smarty->assign("iMinQtyClasses", $aReportData['iMinQtyClasses'];
$smarty->assign("iMinQtyClassesSubdivision", $aReportData['iMinQtyClassesSubdivision']);