<?php
$months = array(1 => 'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь');
if ($_REQUEST['year'] && $_REQUEST['month']) {
    $month = $_REQUEST['month'];
    $year = $_REQUEST['year'];
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
//f10700 1) D умножить на число из таблицы Педагоги поле “Ставка мини. очно” тянем по полю ФИО педагога сокр.
//f10690 2) F умножить на число из таблицы Педагоги (тянем аналогично) поле “Ставка очно”
//f10750 3) H умножить на число из таблицы Педагоги (тянем аналогично) поле “Ставка мин. инд.”
//f10740 (J+M)* число из таблицы Педагоги поле “Ставка мин. онлайн” тянем по полю ФИО педагога сокр.
//f10710 L* число из таблицы Педагоги поле “Ставка онлайн дошк.” тянем по полю ФИО педагога сокр. дошкольный
//f10720 O* число из таблицы Педагоги поле “Ставка онлайн. шк.” тянем по полю ФИО педагога сокр.

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
                WHERE
                    group_id = 790
                    ";//AND arc = 0
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
                            if($iInPay > 0 && $iInPay <= 2){
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
                            if($iInPay > 0 && $iInPay <= 1.5){
                                $iJCol++;
                            } elseif($iInPay > 1.5){
                                $iKCol++;
                                $iLCol = $iLCol + $iInPay;//Считаем сумму по полю Дети к оплате разделить на кол-во педагогов по занятиям в колонке K
                            }
                        }elseif($vClassesRow['GroupType'] == "школа"){
                            if($iInPay > 0 && $iInPay <= 2.5){
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
                $iSCol = $vExpensesTeachersRow['Sum'];
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
                $iTCol = $vPartTimeRow['Sum'];
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
                $iZCol = $vExspansesDecartRow['Sum'];
            }
        }

        $iICol = $iDCol * $vTeachersRow['RateMinYea'] + $iECol * $vTeachersRow['RateYea'] + $iHCol * $vTeachersRow['RateMinInd'];
        $iRCol = ($iJCol + $iMCol) * $vTeachersRow['RateMinOnline'] + $iLCol * $vTeachersRow['RateOnlinePreSchool'] + $iOCol * $vTeachersRow['RateOnlineSchool'] + $iQCol * $vTeachersRow['RateMinInd'];
        $iYCol = $iICol + $iRCol + $iSCol + $iTCol;
        $iBCol = $iDCol + $iECol + $iJCol + $iKCol + $iMCol + $iNCol + $iQCol+ $iHCol;
        $vLines[] = array("A"=>$vTeachersRow['TeacherFIO'], "B"=>$iBCol, "D"=>$iDCol,"E"=>$iECol,"F"=>$iFCol,"H"=>$iHCol,"I"=>$iICol,"J"=>$iJCol,"K"=>$iKCol,"L"=>$iLCol,"M"=>$iMCol,"N"=>$iNCol,"O"=>$iOCol,"Q"=>$iQCol,"R"=>$iRCol,"S"=>$iSCol,"T"=>$iTCol,"Y"=>$iYCol,"Z"=>$iZCol);
    }
}




$result = sql_select_field("" . SCHEMES_TABLE . "", "color3", "active='1'");
$row = sql_fetch_assoc($result);

$smarty->assign("color3", $row['color3']);
$smarty->assign("months", $months);
$smarty->assign("month", $month);
$smarty->assign("year", $year);
$smarty->assign("lines", $vLines);