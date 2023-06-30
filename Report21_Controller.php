<?php
$bdebug = false;
if($bdebug) echo "<pre>".var_dump($_REQUEST)."</pre><br>";
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
$bIsSave = false;
//сохраняем данные
if (array_key_exists('SaveReport', $_REQUEST) && $_REQUEST['SaveReport'] == 1) {
    $bIsSave = true;
    $aWeekLessonR2 = explode(".", $_REQUEST['sWeekLessonR2']);
    if(is_array($aWeekLessonR2) && count($aWeekLessonR2) == 2) {
        $sClassDate = date("Y-m-d 00:00:00", strtotime(form_eng_time($_REQUEST['sDateTimeT1'])));
        $iInsertId = data_insert(780,
            EVENTS_ENABLE,
            array('status'=>'0'
            , 'f12750'=> date("Y-m-d 00:00:00", strtotime(form_eng_time($_REQUEST['sDateTimeT1'])))
            , 'f12770' => $_REQUEST['sAcademicYearU2']
            , 'f12790' => $_REQUEST['iTeacherFioFactId']
            , 'f12850' => $_REQUEST['iTeacherFioFactId2']
            , 'f12810' => $_REQUEST['sProgramAgeX2']
            , 'f13470' => $_REQUEST['iPayPlan_F28']
            , 'f12820' => $_REQUEST['sFormatFactO1']
            , 'f12870' => $_REQUEST['iGroupId']
            , 'f13020' => $aWeekLessonR2[0]
            , 'f13340' => $aWeekLessonR2[1]
            , 'f13010' => $_REQUEST['CommentNextClass']
            , 'f12890' => $_REQUEST['sProgramForYearL3']
            , 'f16430' => $_REQUEST['sTopicJ8']
            , 'f12930' => $_REQUEST['sJobCodeJ11']
            , 'f13180' => $_REQUEST['CommentTopicLevelJ4']
            , 'f12960' => $_REQUEST['CommentTaskLevelJ5']
            , 'f13030' => $_REQUEST['NotesJ31']
            , 'f17950' => $_REQUEST['iJ28_Text']
            , 'f13260' => $_REQUEST['sJobCodeN11']
            , 'f16440' => $_REQUEST['sTopicN8']
            , 'f13250' => $_REQUEST['CommentTopicLevelN4']
            , 'f13280' => $_REQUEST['CommentTaskLevelN5']
            , 'f13290' => $_REQUEST['NotesN31']
            , 'f17960' => $_REQUEST['iN28_Text']
            , 'f13330' => $_REQUEST['sJobCodeR11']
            , 'f16450' => $_REQUEST['sTopicR8']
            , 'f13320' => $_REQUEST['CommentTopicLevelR4']
            , 'f13360' => $_REQUEST['CommentTaskLevelR5']
            , 'f13370' => $_REQUEST['NotesR31']
            , 'f17970' => $_REQUEST['iR28_Text']
            , 'f13410' => $_REQUEST['sJobCodeV11']
            , 'f16460' => $_REQUEST['sTopicV8']
            , 'f13400' => $_REQUEST['CommentTopicLevelV4']
            , 'f13430' => $_REQUEST['CommentTaskLevelV5']
            , 'f13440' => $_REQUEST['NotesV31']
            , 'f17980' => $_REQUEST['iV28_Text']
            ));


        for ($i = 1; $i <= $_REQUEST['iChildrensCount']; $i++)
        {
            //отработка
            $sWorkingOut = $_REQUEST['iHH'.strval($i)];
            $iDisc = 0;
            $iPriceClassPlan = 0;
            $iPriceMonthPlan = 0;
            $sWhereToPay = "";
            $iSelectGroup = $_REQUEST['iGroupId'];
            if($sWorkingOut != ""){
                $sWorkingOut = date("Y-m-d 00:00:00", strtotime(form_eng_time($_REQUEST['iHH'.strval($i)])));
                if($vGroup = data_select_field(820, "f14920 AS GroupId", "f15070='", $sClassDate, "' AND f14960='", $iSelectGroup, "' AND f14890='", $_REQUEST['iB'.strval($i)], "' AND status='0'")) {
                    if($vRowGroup = sql_fetch_assoc($vGroup)){
                        $iSelectGroup = $vRowGroup['GroupId'];
                    } else {
                        $iSelectGroup = 0;
                    }
                } else {
                    $iSelectGroup = 0;
                }
            }
            $sSqlQueryDiskW = "SELECT
                Students.f11540 AS Disk
                , IFNULL(WhereToPays.f10990, '') AS WhereToPay
        FROM
            " . DATA_TABLE . get_table_id(720) . " AS Students
            LEFT JOIN " . DATA_TABLE . get_table_id(680) . " AS WhereToPays
                ON (Students.f15460 = WhereToPays.id AND WhereToPays.status = 0)
            WHERE
                Students.f11480='".$iSelectGroup. "'
                AND Students.f11460='".$_REQUEST['iB'.strval($i)]. "'
                AND Students.status = 0";

            if($result2 = sql_query($sSqlQueryDiskW)) {
                if($row2 = sql_fetch_assoc($result2)){
                    $iDisc = $row2['Disk'];
                    $sWhereToPay = $row2['WhereToPay'];
                }
            }
            if($result4 = data_select_field(700, "f11310 AS PriceClassPlan, f11320 AS PriceMonthPlan", "id='", $iSelectGroup, "' AND status='0'")) {
                if($row4 = sql_fetch_assoc($result4)){
                    $iPriceClassPlan = $row4['PriceClassPlan'];
                    $iPriceMonthPlan = $row4['PriceMonthPlan'];
                }
            }
            //INSERT INTO f_data780 (status, f14820, f14680, f14670, f14690, f14170, f14470, f14480, f14720, f14460, f14490, f14500, f14730,
            //f14310, f14510, f14520, f14740, f14380, f14530, f14540, f14750, f14770, f16290, user_id, add_time, f12770, f17500, f17490, r)
            //VALUES ('0', '2023-09-11 00:00:00', '1081', 'Пробное', '+', '3.1.1|5969', '+', '+-', '3.1.1|5969:468', '2.5.2|6005', '+-', '+-', '2.5.2|6005:483',
            //'2.5.1|6006', '+', '+-', '2.5.1|6006:454', '1.4.3|6054', '+', '+-', '1.4.3|6054:487', '0.00', '0', '10', '2023-06-22 15:49:02', '0', '0', '0', '1')
            data_insert(810,
             EVENTS_ENABLE,
             array('status'=>'0'
             , 'f14820'=> date("Y-m-d 00:00:00", strtotime(form_eng_time($_REQUEST['sDateTimeT1'])))
             , 'f14680' => $_REQUEST['iB'.strval($i)]
             , 'f14700' => $_REQUEST['iI'.strval($i)]
             , 'f14670' => $_REQUEST['iH'.strval($i)]
             , 'f14710' => $sWorkingOut
             , 'f14690' => $_REQUEST['iG'.strval($i)]
             , 'f14760' => $_REQUEST['iAI'.strval($i)]
             , 'f14170' => $_REQUEST['sJobCodeJ11']
             , 'f14470' => $_REQUEST['iJ'.strval($i)]
             , 'f14480' => $_REQUEST['iL'.strval($i)]
             , 'f14720' => $_REQUEST['iJ44_'.strval($i).'_Text']
             , 'f14460' => $_REQUEST['sJobCodeN11']
             , 'f14490' => $_REQUEST['iN'.strval($i)]
             , 'f14500' => $_REQUEST['iP'.strval($i)]
             , 'f14730' => $_REQUEST['iN44_'.strval($i).'_Text']
             , 'f14310' => $_REQUEST['sJobCodeR11']
             , 'f14510' => $_REQUEST['iR'.strval($i)]
             , 'f14520' => $_REQUEST['iT'.strval($i)]
             , 'f14740' => $_REQUEST['iR44_'.strval($i).'_Text']
             , 'f14380' => $_REQUEST['sJobCodeV11']
             , 'f14530' => $_REQUEST['iV'.strval($i)]
             , 'f14540' => $_REQUEST['iX'.strval($i)]
             , 'f14750' => $_REQUEST['iV44_'.strval($i).'_Text']
             , 'f14770' => $iDisc
             , 'f14810' => $iPriceClassPlan
             , 'f14790' => $iPriceMonthPlan
             , 'f16290' => $sWhereToPay
             , 'f13980' => $iInsertId
             ));
        }

        SaveFoto($iInsertId, 'Foto1', '13480');
        SaveFoto($iInsertId, 'Foto2', '13490');


    } else {
        echo "Ошибка данных Неделя.урок -- " .$_REQUEST['sWeekLessonR2']."<br>";
    }

    //[Foto1] => Array ( [name] => 2896b.jpg [type] => image/jpeg [tmp_name] => /home/crm149992/tmp/apache/phpkdgGMg [error] => 0 [size] => 29059
    //[iGroupId] => 268 [sFormatFactO1] => очно [sWeekLessonR2] => 10.1 [iTeacherId2] => 0 [CommentNextClass] => [CommentTopicLevelJ4] =>
    //    [CommentTopicLevelN4] => [CommentTopicLevelR4] => [CommentTopicLevelV4] => [CommentTaskLevelJ5] => [CommentTaskLevelN5] =>
    //    [CommentTaskLevelR5] => [CommentTaskLevelV5] =>
    //    [iB1] => 87 [iG1] => [iH1] => [iI1] => [iJ1] => + [iL1] => [iN1] => [iP1] => [iR1] => [iT1] => [iV1] => [iX1] => [iAI1] => 0
    //    [iB2] => 248 [iG2] => [iH2] => [iI2] => н [iJ2] => [iL2] => [iN2] => [iP2] => [iR2] => [iT2] => [iV2] => [iX2] => [iAI2] => 0
    //    [iB3] => 207 [iG3] => [iH3] => [iI3] => н [iJ3] => [iL3] => [iN3] => [iP3] => [iR3] => [iT3] => [iV3] => [iX3] => [iAI3] => 0
    //    [iB4] => 202 [iG4] => [iH4] => [iI4] => н [iJ4] => [iL4] => [iN4] => [iP4] => [iR4] => [iT4] => [iV4] => [iX4] => [iAI4] => 0
    //    [iB5] => 231 [iG5] => [iH5] => [iI5] => н [iJ5] => [iL5] => [iN5] => [iP5] => [iR5] => [iT5] => [iV5] => [iX5] => [iAI5] => 0
    //    [iPayPlan_F28] => Оплата [iPayFact_F28] =>
    //    [iJ28] => 619 [iN28] => 0 [iR28] => 0 [iV28] => 0 [NotesJ31] => [NotesN31] => [NotesR31] => [NotesV31] =>
    //    [iJ44_1] => 783 [iN44_1] => 0 [iR44_1] => 0 [iV44_1] => 0
    //    [iJ44_2] => 0 [iN44_2] => 0 [iR44_2] => 0 [iV44_2] => 0
    //    [iJ44_3] => 0 [iN44_3] => 0 [iR44_3] => 0 [iV44_3] => 0
    //    [iJ44_4] => 0 [iN44_4] => 0 [iR44_4] => 0 [iV44_4] => 0
    //    [iJ44_5] => 0 [iN44_5] => 0 [iR44_5] => 0 [iV44_5] => 0
    //    [SaveReport] => 1 [FormaFact] => 0 [WeekLesson] => 0
    //занятие 780
}

$sDateZero = '0000-00-00 00:00:00';
$vLines = [];
$sDateNow = date('Y-m-d 00:00:00');
$bIsAdmin = true;
$sCabinetH1 = "";
$sFormatPlanL1 = "";
$sFormatFactO1 = "";
$sWeekDayV1 = "";
$sDateTimeX1 = "";
$sTeacherFioFactD2 = "";
$sDepartmentNameH2 = "";
$sDepartmentAddressL2 = "";
$sCabinetH1 = "";
$sLessonTimeX1 = "";
$sProgramAgeX2 = "";
$sAcademicYearU2 = "";
$sWeekLessonR2 = "";
$sProgramForYearL3 = "";
$sSubsectionP3 = "";
$sJobNameT3 = "";
$sPrintOutsPdf = "";
$sJobCodeJ11 = "";
$sJobCodeN11 = "";
$sJobCodeR11 = "";
$sJobCodeV11 = "";
$sSubsectionJ7 = "";
$sSubsectionN7 = "";
$sSubsectionR7 = "";
$sSubsectionV7 = "";
$sTopicJ8 = "";
$sTopicN8 = "";
$sTopicR8 = "";
$sTopicV8 = "";
$sJobNameJ9 = "";
$sJobNameN9 = "";
$sJobNameR9 = "";
$sJobNameV9 = "";
$sSectionJ6 = "";
$sSectionN6 = "";
$sSectionR6 = "";
$sSectionV6 = "";
$iJobCodeM9_1 = 0;
$iJobCodeQ9_1 = 0;
$iJobCodeU9_1 = 0;
$iJobCodeY9_1 = 0;
$iJobCodeM9_2 = 0;
$iJobCodeQ9_2 = 0;
$iJobCodeU9_2 = 0;
$iJobCodeY9_2 = 0;
$sJobCodeM9 = "";
$sJobCodeQ9 = "";
$sJobCodeU9 = "";
$sJobCodeY9 = "";
$sM5 = "";
$sQ5 = "";
$sU5 = "";
$sY5 = "";
$vGifts =[];
$vTeachers = [];
$vWhatDidLearnJ28 = [];
$vWhatDidLearnN28 = [];
$vWhatDidLearnR28 = [];
$vWhatDidLearnV28 = [];
$sPreviosCommentJ33 = "";
$sThemeJ34 = "";
$sThemeN34 = "";
$sThemeR34 = "";
$sThemeV34 = "";

$sTaskJ35 = "";
$sTaskN35 = "";
$sTaskR35 = "";
$sTaskV35 = "";

$vRecomendationJ44 = [];
$vRecomendationN44 = [];
$vRecomendationR44 = [];
$vRecomendationV44 = [];
$vTasks =[];
$FormaFact = 0;
$WeekLesson = 0;
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
        , IFNULL(Groups.f11140, '') AS ProgramAge
        , IFNULL(Teacher.id, 0) AS TeacherId
        , IFNULL(Teacher.f9660, '') AS TeacherFioFact
        , IFNULL(Departments.f9450, '') AS DepartmentName
        , IFNULL(Departments.f9460, '') AS DepartmentAddress
        , IFNULL((SELECT MAX(Classes.f12750) FROM " . DATA_TABLE . get_table_id(780) . " AS Classes  WHERE Classes.f12870 = Groups.id AND Classes.status = 0),'') AS MaxClassesDate
        , IFNULL(Groups.f11240, '') AS ClassStartDate
    FROM
        " . DATA_TABLE . get_table_id(790) . " AS Schedule
        INNER JOIN " . DATA_TABLE . get_table_id(700) . " AS Groups
            ON Schedule.f13550 = Groups.id
        LEFT JOIN " . DATA_TABLE . get_table_id(520) . " AS Teacher
            ON (Groups.f11180 = Teacher.id AND Teacher.status = 0)
        LEFT JOIN " . DATA_TABLE . get_table_id(500) . " AS Departments
            ON (Groups.f11150 = Departments.id AND Departments.status = 0)
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
$aDays = array('Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота');
$aDaysToEng = array('Воскресенье' => 'sunday', 'Понедельник' => 'monday', 'Вторник' => 'tuesday', 'Среда' => 'wednesday', 'Четверг' => 'thursday', 'Пятница' => 'friday', 'Суббота' => 'saturday');
//echo "----sClassStartDate--".$sClassStartDate."--sMaxClassesDate--".$sMaxClassesDate."----<br>";
if($sMaxClassesDate == ""){
    $sStartDate = $sClassStartDate;
}else{
    $dMaxClassesDate = new DateTime($sMaxClassesDate);
    $dMaxClassesDate->modify('+1 day');
    $sStartDate = $dMaxClassesDate->format("Y-m-d 00:00:00");
}
$dStartDate = new DateTime($sStartDate);
$sStartWeekDay = strtolower(date("l", strtotime($sStartDate)));
$dSearchDate = clone $dStartDate;
$iWeekDay = 0;
//$dStartDate->modify('next monday');
//Расписние 790 Дни недели 600
//f13560 День недели
//f10330 Дни недели
//echo "0----dSearchDate--".$dSearchDate->format('Y-m-d')."--sMaxClassesDate--".$sMaxClassesDate."----<br>";
$iColClass = 0;
$sSqlWeekDays = "SELECT
        WeekDays.id AS WeekDayId
        , WeekDays.f10330 AS WeekDayName
        FROM
            " . DATA_TABLE . get_table_id(790) ." AS Schedule
                INNER JOIN " . DATA_TABLE . get_table_id(600) ." AS WeekDays
                    ON Schedule.f13560 = WeekDays.id
        WHERE
            Schedule.f13550='" . $iGroupId . "'
            AND Schedule.status='0'
            AND WeekDays.status='0'
         ORDER BY WeekDays.id";
if ($vResWeekDays = sql_query($sSqlWeekDays)) {
    while ($vRowWeekDays = sql_fetch_assoc($vResWeekDays)) {
        $iColClass++;
        //Если день начальной даты для поиска равен дню недели
        if($sStartWeekDay == $aDaysToEng[$vRowWeekDays['WeekDayName']]) {
            $iWeekDay = $vRowWeekDays['WeekDayId'];
            $sWeekDayV1 = $vRowWeekDays['WeekDayName'];
            $dSearchDate = clone $dStartDate;
            //echo "----sStartWeekDay--".$sStartWeekDay."--dSearchDate--".$dSearchDate->format('Y-m-d')."----<br>";
            break;
        } else{
            if($iColClass == 1) {
                $dSearchDate->modify('next '.$aDaysToEng[$vRowWeekDays['WeekDayName']]);
                $iWeekDay = $vRowWeekDays['WeekDayId'];
                $sWeekDayV1 = $vRowWeekDays['WeekDayName'];
                //echo "1----next--".$aDaysToEng[$vRowWeekDays['WeekDayName']]."--dSearchDate--".$dSearchDate->format('Y-m-d')."----<br>";
            }else{
                $dCheckDate = clone $dStartDate;
                $dCheckDate->modify('next '.$aDaysToEng[$vRowWeekDays['WeekDayName']]);
                //echo "2----next--".$aDaysToEng[$vRowWeekDays['WeekDayName']]."---dCheckDate--".$dCheckDate->format('Y-m-d')."<-dSearchDate--".$dSearchDate->format('Y-m-d')."----<br>";
                if($dCheckDate < $dSearchDate) {
                    $dSearchDate = clone $dCheckDate;
                    $iWeekDay = $vRowWeekDays['WeekDayId'];
                    $sWeekDayV1 = $vRowWeekDays['WeekDayName'];
                }
            }
        }
    }
}
//echo "dSearchDate--".$dSearchDate->format('Y-m-d')."----sClassStartDate--".$sClassStartDate."--sMaxClassesDate--".$sMaxClassesDate."----<br>";
if($sWeekDayV1 == ""){
    $sWeekDayV1 = $aDays[date("w", strtotime($dSearchDate->format("Y-m-d")))];
}
// Если количество занятий больше 0
if($iColClass > 0){
    $sSqlQuerySchedule = "SELECT
       IFNULL(Schedule.f13580, '') AS FormatPlan
        , IFNULL(Schedule.f13630, '') AS Cabinet
        , IFNULL(Schedule.f13570, '') AS LessonTime
    FROM
        " . DATA_TABLE . get_table_id(790) . " AS Schedule
    WHERE
        Schedule.f13550 = ".$iGroupId."
        AND Schedule.f13560 = ".$iWeekDay."
        AND Schedule.status = 0";
    if($vScheduleData = sql_query($sSqlQuerySchedule)){
        while ($vScheduleRow = sql_fetch_assoc($vScheduleData)) {
            $sFormatPlanL1 = form_display($vScheduleRow['FormatPlan']);
            $sFormatFactO1 = $sFormatPlanL1;
            $sCabinetH1 = form_display($vScheduleRow['Cabinet']);
            $sLessonTimeX1 = form_display($vScheduleRow['LessonTime']);
        }
    }
    //заменяем $sFormatFactO1 из страницы
    if (!$bIsSave && array_key_exists('FormaFact', $_REQUEST) && $_REQUEST['FormaFact'] == 1 && array_key_exists('sFormatFactO1', $_REQUEST)){
            $sFormatFactO1 = $_REQUEST['sFormatFactO1'];
            $FormaFact = 1;
    }

    //Учебный год
    $dTrainingBeg = new DateTime();
    $dTrainingBeg->setDate(intval($dSearchDate->format("Y")) - 1, 9, 1);
    $dTrainingEnd = new DateTime();
    $dTrainingEnd->setDate(intval($dSearchDate->format("Y")), 8, 31);

    $sAcademicYearU2 = ($dSearchDate >= $dTrainingBeg && $dSearchDate <= $dTrainingEnd) ?  strval(intval($dSearchDate->format("Y")) - 1) : $dSearchDate->format("Y");
    $sSearchDate = $dSearchDate->format('Y-m-d 00:00:00');
    $iWeek = 0;
    $iLesson = 0;
    //Из таблицы Занятия 780 по полю Название группы f12870 вычисляем по полям № недели f13020 и № урока f13340 номер последнего занятия (сначала макс по неделе, потом макс по уроку).
    if ($vResClassesData = sql_query("SELECT MAX(CAST(f13020 AS SIGNED)) AS Week FROM " . DATA_TABLE . get_table_id(780) ." WHERE f12870='" . $iGroupId . "' AND status='0'")) {
        if ($vRowClassesData = sql_fetch_assoc($vResClassesData)) {
            $iWeek = intval($vRowClassesData['Week']);
            if ($vResLessonData = sql_query("SELECT MAX(CAST(f13340 AS SIGNED)) AS Lesson FROM " . DATA_TABLE . get_table_id(780) ." WHERE f13020 = '".$iWeek."' AND f12870='" . $iGroupId . "' AND status='0'")) {
                if ($vRowLessonData = sql_fetch_assoc($vResLessonData)) {
                    $iLesson = intval($vRowLessonData['Lesson']);
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
    if (!$bIsSave && array_key_exists('WeekLesson', $_REQUEST) && $_REQUEST['WeekLesson'] == 1 && array_key_exists('sWeekLessonR2', $_REQUEST)){
        $sWeekLessonR2 = $_REQUEST['sWeekLessonR2'];
        $aWeekLessonR2 = explode(".", $_REQUEST['sWeekLessonR2']);
        $iWeek = $aWeekLessonR2[0];
        $iLesson = $aWeekLessonR2[1];
        $WeekLesson = 1;
    }

    //учителя
    $sSqlQueryTeachers = "SELECT
           Teachers.id AS TeacherId
           , Teachers.f9660 AS TeacherName
        FROM
            " . DATA_TABLE . get_table_id(520) . " AS Teachers
        WHERE
           Teachers.id <> ".$iGroupTeacherId."
            AND Teachers.status = 0
        ORDER BY Teachers.f9660";
    $vTeachers[] = array("TeacherId" => 0, "TeacherName" => "");
    if($vTeachersData = sql_query($sSqlQueryTeachers)){
        while ($vTeachersRow = sql_fetch_assoc($vTeachersData)) {
            $vTeachers[] = array("TeacherId" => $vTeachersRow["TeacherId"], "TeacherName" => $vTeachersRow["TeacherName"]);
        }
    }
    //подарки
    $sSqlQueryGifts = "SELECT
           Gifts.id AS GiftId
           , Gifts.f10130 AS GiftName
        FROM
            " . DATA_TABLE . get_table_id(590) . " AS Gifts
        WHERE
           Gifts.id <> ".$iGroupTeacherId."
            AND Gifts.status = 0
        ORDER BY Gifts.f10130";
    $vGifts[] = array("GiftId" => 0, "GiftName" => "");
    if($vGiftsData = sql_query($sSqlQueryGifts)){
        while ($vTGiftsRow = sql_fetch_assoc($vGiftsData)) {
            $vGifts[] = array("GiftId" => $vTGiftsRow["GiftId"], "GiftName" => $vTGiftsRow["GiftName"]);
        }
    }

    //Комментарий с предыдущего занятия.
    $sSqlQueryPreviosComment = "SELECT
           PreviosComment.f12750 AS PreviosCommentDate
           , PreviosComment.f13010 AS PreviosCommentLesson
        FROM
            " . DATA_TABLE . get_table_id(780) . " AS PreviosComment
        WHERE
           PreviosComment.f12870 = '".$iGroupId."'
           AND PreviosComment.f12750 = (
            SELECT MAX(PP.f12750)
                FROM " . DATA_TABLE . get_table_id(780) . " AS PP
                WHERE PP.f12870 = '".$iGroupId."' AND PP.status = 0)
           AND PreviosComment.status = 0";
    if($vPreviosCommentData = sql_query($sSqlQueryPreviosComment)){
        if ($vPreviosCommentRow = sql_fetch_assoc($vPreviosCommentData)) {
            if($vPreviosCommentRow['PreviosCommentLesson'] != ""){
                $dDate1 = new DateTime($vPreviosCommentRow['PreviosCommentDate']);
                $sPreviosCommentJ33 = $dDate1->format("d.m.Y") ."<br>". $vPreviosCommentRow['PreviosCommentLesson'];
            }
        }
    }

    $sSqlQueryProgramForYear = "SELECT
           Tasks.Id AS ProgramForYearId
           , Tasks.f12530 AS ProgramForYearName
        FROM
            " . DATA_TABLE . get_table_id(730) . " AS ProgramForYear
                INNER JOIN " . DATA_TABLE . get_table_id(620) . " AS Tasks
                    ON ProgramForYear.f12600 = Tasks.Id
        WHERE
           ProgramForYear.f11700 = '".$sProgramAgeX2."'
           AND ProgramForYear.f11710 = '".$iWeek."'
           AND ProgramForYear.f11850 = '".$iLesson."'
           AND ProgramForYear.status = 0
           AND Tasks.status = 0";
    if($vProgramForYearData = sql_query($sSqlQueryProgramForYear)){
        if ($vProgramForYearRow = sql_fetch_assoc($vProgramForYearData)) {
            $sProgramForYearId = $vProgramForYearRow['ProgramForYearId'];
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
    if($vTaskData = sql_query($sSqlQueryTasks)){
        if ($vTaskRow = sql_fetch_assoc($vTaskData)) {
            $sSubsectionP3 = $vTaskRow['Subsection'];
            $sJobNameT3 = $vTaskRow['JobName'];
            $sPrintOutsPdf = $vTaskRow['PrintOutsPdf'];
        }
    }

    $sSqlQueryProgramForYear1 = "SELECT
           Tasks.Id AS JobId
           , Tasks.f12530 AS JobCode
        FROM
            " . DATA_TABLE . get_table_id(730) . " AS ProgramForYear
                INNER JOIN " . DATA_TABLE . get_table_id(620) . " AS Tasks
                    ON ProgramForYear.f12590 = Tasks.Id
        WHERE
           ProgramForYear.f11700 = '".$sProgramAgeX2."'
           AND ProgramForYear.f11710 = '".$iWeek."'
           AND ProgramForYear.f11720 = '".$sFormatFactO1."'
           AND ProgramForYear.status = 0
           AND Tasks.status = 0";
    if($iColClass  > 1)
        $sSqlQueryProgramForYear1 = $sSqlQueryProgramForYear1." AND ProgramForYear.f11850 = '".$iLesson."'";
    $sSqlQueryProgramForYear1 = $sSqlQueryProgramForYear1." ORDER BY Tasks.Id";

    $i = 0;
    $sJobId  = 0;
    if($vProgramForYearData1 = sql_query($sSqlQueryProgramForYear1)){
        while ($vProgramForYearRow1 = sql_fetch_assoc($vProgramForYearData1)) {
            $i++;
            switch ($i)
            {
                case 1:
                    $sJobCodeJ11 = $vProgramForYearRow1['JobCode'];
                    $sJobCode = $sJobCodeJ11;
                    $sJobId = $vProgramForYearRow1['JobId'];
                    //Рекомендации
                    $sSqlQueryRecomendations = "SELECT
                               Recomendations.Id AS RecomendationId
                               , Recomendations.f10610 AS RecomendationText
                               , Recomendations.f10620 AS RecomendationLink
                               , Recomendations.f13870 AS RecomendationCode
                            FROM
                                " . DATA_TABLE . get_table_id(630) . " AS Recomendations
                            WHERE
                               Recomendations.f12570 = '".$sJobId."'
                               AND Recomendations.status = 0";
                    if($vRecomendationData = sql_query($sSqlQueryRecomendations)){
                        while ($vRecomendationRow = sql_fetch_assoc($vRecomendationData)) {
                            $vRecomendationJ44[] = array("RecomendationId" => $vRecomendationRow['RecomendationId'], "RecomendationText" => $vRecomendationRow['RecomendationText'], "RecomendationLink" => $vRecomendationRow['RecomendationLink'], "RecomendationCode" => $vRecomendationRow['RecomendationCode']);
                        }
                    }

                    break;
                case 2:
                    $sJobCodeN11 = $vProgramForYearRow1['JobCode'];
                    $sJobId = $vProgramForYearRow1['JobId'];
                    $sJobCode = $sJobCodeN11;
                    //Рекомендации
                    $sSqlQueryRecomendations = "SELECT
                               Recomendations.Id AS RecomendationId
                               , Recomendations.f10610 AS RecomendationText
                               , Recomendations.f10620 AS RecomendationLink
                               , Recomendations.f13870 AS RecomendationCode
                            FROM
                                " . DATA_TABLE . get_table_id(630) . " AS Recomendations
                            WHERE
                               Recomendations.f12570 = '".$sJobId."'
                               AND Recomendations.status = 0";
                    if($vRecomendationData = sql_query($sSqlQueryRecomendations)){
                        while ($vRecomendationRow = sql_fetch_assoc($vRecomendationData)) {
                            $vRecomendationN44[] = array("RecomendationId" => $vRecomendationRow['RecomendationId'], "RecomendationText" => $vRecomendationRow['RecomendationText'], "RecomendationLink" => $vRecomendationRow['RecomendationLink'], "RecomendationCode" => $vRecomendationRow['RecomendationCode']);
                        }
                    }
                    break;
                case 3:
                    $sJobCodeR11 = $vProgramForYearRow1['JobCode'];
                    $sJobId = $vProgramForYearRow1['JobId'];
                    $sJobCode = $sJobCodeR11;
                    //Рекомендации
                    $sSqlQueryRecomendations = "SELECT
                               Recomendations.Id AS RecomendationId
                               , Recomendations.f10610 AS RecomendationText
                               , Recomendations.f10620 AS RecomendationLink
                               , Recomendations.f13870 AS RecomendationCode
                            FROM
                                " . DATA_TABLE . get_table_id(630) . " AS Recomendations
                            WHERE
                               Recomendations.f12570 = '".$sJobId."'
                               AND Recomendations.status = 0";
                    if($vRecomendationData = sql_query($sSqlQueryRecomendations)){
                        while ($vRecomendationRow = sql_fetch_assoc($vRecomendationData)) {
                            $vRecomendationR44[] = array("RecomendationId" => $vRecomendationRow['RecomendationId'], "RecomendationText" => $vRecomendationRow['RecomendationText'], "RecomendationLink" => $vRecomendationRow['RecomendationLink'], "RecomendationCode" => $vRecomendationRow['RecomendationCode']);
                        }
                    }
                    break;
                case 4:
                    $sJobCodeV11 = $vProgramForYearRow1['JobCode'];
                    $sJobId = $vProgramForYearRow1['JobId'];
                    $sJobCode = $sJobCodeV11;
                    //Рекомендации
                    $sSqlQueryRecomendations = "SELECT
                               Recomendations.Id AS RecomendationId
                               , Recomendations.f10610 AS RecomendationText
                               , Recomendations.f10620 AS RecomendationLink
                               , Recomendations.f13870 AS RecomendationCode
                            FROM
                                " . DATA_TABLE . get_table_id(630) . " AS Recomendations
                            WHERE
                               Recomendations.f12570 = '".$sJobId."'
                               AND Recomendations.status = 0";
                    if($vRecomendationData = sql_query($sSqlQueryRecomendations)){
                        while ($vRecomendationRow = sql_fetch_assoc($vRecomendationData)) {
                            $vRecomendationV44[] = array("RecomendationId" => $vRecomendationRow['RecomendationId'], "RecomendationText" => $vRecomendationRow['RecomendationText'], "RecomendationLink" => $vRecomendationRow['RecomendationLink'], "RecomendationCode" => $vRecomendationRow['RecomendationCode']);
                        }
                    }
                    break;
            }

            $sSqlQueryTasks1 = "SELECT
                       IFNULL(Tasks.f14840, '') AS Subsection
                       , IFNULL(Tasks.f10440, '') AS JobName
                       , IFNULL(Themes.f10010, '') AS Topic
                       , IFNULL(Tasks.f10450, '') AS Description
                       , IFNULL(Rekvizit1.f10070, '') AS Rekvizit1Name
                       , IFNULL(Rekvizit1.f16400, '') AS Rekvizit1Link
                       , IFNULL(Rekvizit2.f10070, '') AS Rekvizit2Name
                       , IFNULL(Rekvizit2.f16400, '') AS Rekvizit2Link
                       , IFNULL(Rekvizit3.f10070, '') AS Rekvizit3Name
                       , IFNULL(Rekvizit3.f16400, '') AS Rekvizit3Link
                       , IFNULL(Rekvizit4.f10070, '') AS Rekvizit4Name
                       , IFNULL(Rekvizit4.f16400, '') AS Rekvizit4Link
                       , IFNULL(Rekvizit5.f10070, '') AS Rekvizit5Name
                       , IFNULL(Rekvizit5.f16400, '') AS Rekvizit5Link
                       , IFNULL(Rekvizit6.f10070, '') AS Rekvizit6Name
                       , IFNULL(Rekvizit6.f16400, '') AS Rekvizit6Link
                       , IFNULL(Tasks.f10480, '') AS PrintPDF
                       , IFNULL(Tasks.f10550, '') AS CardPDF
                       , IFNULL(Tasks.f16480, '') AS Video
                    FROM
                        " . DATA_TABLE . get_table_id(620) . " AS Tasks
                            LEFT JOIN " . DATA_TABLE . get_table_id(570) . " AS Themes
                                ON (Tasks.f10420 = Themes.Id AND Themes.status = '0')
                            LEFT JOIN " . DATA_TABLE . get_table_id(580) . " AS Rekvizit1
                                ON (Tasks.f10490 = Rekvizit1.Id AND Rekvizit1.status = '0')
                            LEFT JOIN " . DATA_TABLE . get_table_id(580) . " AS Rekvizit2
                                ON (Tasks.f10500 = Rekvizit2.Id AND Rekvizit2.status = '0')
                            LEFT JOIN " . DATA_TABLE . get_table_id(580) . " AS Rekvizit3
                                ON (Tasks.f10510 = Rekvizit3.Id AND Rekvizit3.status = '0')
                            LEFT JOIN " . DATA_TABLE . get_table_id(580) . " AS Rekvizit4
                                ON (Tasks.f10520 = Rekvizit4.Id AND Rekvizit4.status = '0')
                            LEFT JOIN " . DATA_TABLE . get_table_id(580) . " AS Rekvizit5
                                ON (Tasks.f10530 = Rekvizit5.Id AND Rekvizit5.status = '0')
                            LEFT JOIN " . DATA_TABLE . get_table_id(580) . " AS Rekvizit6
                                ON (Tasks.f10540 = Rekvizit6.Id AND Rekvizit6.status = '0')
                    WHERE
                       Tasks.status = '0'
                       AND Tasks.f12530 = '".$sJobCode."'";
            if($vTasksData1 = sql_query($sSqlQueryTasks1)){
                if ($vTasksRow1 = sql_fetch_assoc($vTasksData1)) {
                    switch ($i)
                    {
                        case 1:
                            $sSubsectionJ7 = $vTasksRow1['Subsection'];
                            $sTopicJ8 = $vTasksRow1['Topic'];

                            $sJobNameJ9 = $vTasksRow1['JobName'];
                            $sSubsection = $sSubsectionJ7;
                            $vTasks[] = array("iPos"=> $i, "JobName" => $sJobNameJ9, "JobCode" => $sJobCode, "JobDescription" => $vTasksRow1['Description'], "JobPrintPDF" => $vTasksRow1['PrintPDF'], "JobCardPDF" => $vTasksRow1['CardPDF'], "JobVideo" => $vTasksRow1['Video'], "JobRekvizit1Name" => $vTasksRow1['Rekvizit1Name'], "JobRekvizit1Link" => $vTasksRow1['Rekvizit1Link'], "JobRekvizit2Name" => $vTasksRow1['Rekvizit2Name'], "JobRekvizit2Link" => $vTasksRow1['Rekvizit2Link'], "JobRekvizit3Name" => $vTasksRow1['Rekvizit3Name'], "JobRekvizit3Link" => $vTasksRow1['Rekvizit3Link'], "JobRekvizit4Name" => $vTasksRow1['Rekvizit4Name'], "JobRekvizit4Link" => $vTasksRow1['Rekvizit4Link'], "JobRekvizit5Name" => $vTasksRow1['Rekvizit5Name'], "JobRekvizit5Link" => $vTasksRow1['Rekvizit5Link'], "JobRekvizit6Name" => $vTasksRow1['Rekvizit6Name'], "JobRekvizit6Link" => $vTasksRow1['Rekvizit6Link']);

                            break;
                        case 2:
                            $sSubsectionN7 = $vTasksRow1['Subsection'];
                            $sTopicN8 = $vTasksRow1['Topic'];
                            $sJobNameN9 = $vTasksRow1['JobName'];
                            $sSubsection = $sSubsectionN7;
                            $vTasks[] = array("iPos"=> $i, "JobName" => $sJobNameN9, "JobCode" => $sJobCode, "JobDescription" => $vTasksRow1['Description'], "JobPrintPDF" => $vTasksRow1['PrintPDF'], "JobCardPDF" => $vTasksRow1['CardPDF'], "JobVideo" => $vTasksRow1['Video'], "JobRekvizit1Name" => $vTasksRow1['Rekvizit1Name'], "JobRekvizit1Link" => $vTasksRow1['Rekvizit1Link'], "JobRekvizit2Name" => $vTasksRow1['Rekvizit2Name'], "JobRekvizit2Link" => $vTasksRow1['Rekvizit2Link'], "JobRekvizit3Name" => $vTasksRow1['Rekvizit3Name'], "JobRekvizit3Link" => $vTasksRow1['Rekvizit3Link'], "JobRekvizit4Name" => $vTasksRow1['Rekvizit4Name'], "JobRekvizit4Link" => $vTasksRow1['Rekvizit4Link'], "JobRekvizit5Name" => $vTasksRow1['Rekvizit5Name'], "JobRekvizit5Link" => $vTasksRow1['Rekvizit5Link'], "JobRekvizit6Name" => $vTasksRow1['Rekvizit6Name'], "JobRekvizit6Link" => $vTasksRow1['Rekvizit6Link']);
                            break;
                        case 3:
                            $sSubsectionR7 = $vTasksRow1['Subsection'];
                            $sTopicR8 = $vTasksRow1['Topic'];
                            $sJobNameR9 = $vTasksRow1['JobName'];
                            $sSubsection = $sSubsectionR7;
                            $vTasks[] = array("iPos"=> $i, "JobName" => $sJobNameR9, "JobCode" => $sJobCode, "JobDescription" => $vTasksRow1['Description'], "JobPrintPDF" => $vTasksRow1['PrintPDF'], "JobCardPDF" => $vTasksRow1['CardPDF'], "JobVideo" => $vTasksRow1['Video'], "JobRekvizit1Name" => $vTasksRow1['Rekvizit1Name'], "JobRekvizit1Link" => $vTasksRow1['Rekvizit1Link'], "JobRekvizit2Name" => $vTasksRow1['Rekvizit2Name'], "JobRekvizit2Link" => $vTasksRow1['Rekvizit2Link'], "JobRekvizit3Name" => $vTasksRow1['Rekvizit3Name'], "JobRekvizit3Link" => $vTasksRow1['Rekvizit3Link'], "JobRekvizit4Name" => $vTasksRow1['Rekvizit4Name'], "JobRekvizit4Link" => $vTasksRow1['Rekvizit4Link'], "JobRekvizit5Name" => $vTasksRow1['Rekvizit5Name'], "JobRekvizit5Link" => $vTasksRow1['Rekvizit5Link'], "JobRekvizit6Name" => $vTasksRow1['Rekvizit6Name'], "JobRekvizit6Link" => $vTasksRow1['Rekvizit6Link']);
                            break;
                        case 4:
                            $sSubsectionV7 = $vTasksRow1['Subsection'];
                            $sTopicV8 = $vTasksRow1['Topic'];
                            $sJobNameV9 = $vTasksRow1['JobName'];
                            $sSubsection = $sSubsectionV7;
                            $vTasks[] = array("iPos"=> $i, "JobName" => $sJobNameV9, "JobCode" => $sJobCode, "JobDescription" => $vTasksRow1['Description'], "JobPrintPDF" => $vTasksRow1['PrintPDF'], "JobCardPDF" => $vTasksRow1['CardPDF'], "JobVideo" => $vTasksRow1['Video'], "JobRekvizit1Name" => $vTasksRow1['Rekvizit1Name'], "JobRekvizit1Link" => $vTasksRow1['Rekvizit1Link'], "JobRekvizit2Name" => $vTasksRow1['Rekvizit2Name'], "JobRekvizit2Link" => $vTasksRow1['Rekvizit2Link'], "JobRekvizit3Name" => $vTasksRow1['Rekvizit3Name'], "JobRekvizit3Link" => $vTasksRow1['Rekvizit3Link'], "JobRekvizit4Name" => $vTasksRow1['Rekvizit4Name'], "JobRekvizit4Link" => $vTasksRow1['Rekvizit4Link'], "JobRekvizit5Name" => $vTasksRow1['Rekvizit5Name'], "JobRekvizit5Link" => $vTasksRow1['Rekvizit5Link'], "JobRekvizit6Name" => $vTasksRow1['Rekvizit6Name'], "JobRekvizit6Link" => $vTasksRow1['Rekvizit6Link']);
                            break;
                    }
                }
            }


            $sSqlQuerySubsection = "SELECT
                       Sections.f9870 AS SectionName
                    FROM
                        " . DATA_TABLE . get_table_id(560) . " AS Subsections
                        INNER JOIN " . DATA_TABLE . get_table_id(550) . " AS Sections
                            ON Subsections.f9930 = Sections.id
                    WHERE
                       Subsections.status = 0
                       AND Subsections.f9940 = '".$sSubsection."'
                       AND Sections.status = 0";
            if($vSubsectionData = sql_query($sSqlQuerySubsection)){
                if ($vSubsectionRow = sql_fetch_assoc($vSubsectionData)) {
                    switch ($i)
                    {
                        case 1:
                            $sSectionJ6 = $vSubsectionRow['SectionName'];
                            break;
                        case 2:
                            $sSectionN6 = $vSubsectionRow['SectionName'];
                            break;
                        case 3:
                            $sSectionR6 = $vSubsectionRow['SectionName'];
                            break;
                        case 4:
                            $sSectionV6 = $vSubsectionRow['SectionName'];
                            break;
                    }
                }
            }

            $sSqlQueryProgramForYear3 = "SELECT
                   COUNT(ProgramForYear.id) AS JobQty
                FROM
                    " . DATA_TABLE . get_table_id(730) . " AS ProgramForYear
                WHERE
                   ProgramForYear.f11700 = '".$sProgramAgeX2."'
                   AND ProgramForYear.f12590 = '".$sJobId."'
                   AND ProgramForYear.f11720 = '".$sFormatFactO1."'
                   AND ProgramForYear.status = 0";
            //if($bdebug) echo $sSqlQueryProgramForYear3;
            if($vProgramForYearData3 = sql_query($sSqlQueryProgramForYear3)){
                if ($vProgramForYearRow3 = sql_fetch_assoc($vProgramForYearData3)) {
                    switch ($i)
                    {
                        case 1:
                            $iJobCodeM9_1 = $vProgramForYearRow3['JobQty'] == 1 ? 1 : GetWeekPos($sProgramAgeX2, $sJobId, $sFormatFactO1, $iWeek);
                            break;
                        case 2:
                            $iJobCodeQ9_1 = $vProgramForYearRow3['JobQty'] == 1 ? 1 : GetWeekPos($sProgramAgeX2, $sJobId, $sFormatFactO1, $iWeek);
                            break;
                        case 3:
                            $iJobCodeU9_1 = $vProgramForYearRow3['JobQty'] == 1 ? 1 : GetWeekPos($sProgramAgeX2, $sJobId, $sFormatFactO1, $iWeek);
                            break;
                        case 4:
                            $iJobCodeY9_1 = $vProgramForYearRow3['JobQty'] == 1 ? 1 : GetWeekPos($sProgramAgeX2, $sJobId, $sFormatFactO1, $iWeek);
                            break;
                    }
                }
            }

            $sSqlQueryProgramForYear2 = "SELECT
                   COUNT(ProgramForYear.id) AS JobCode
                FROM
                    " . DATA_TABLE . get_table_id(730) . " AS ProgramForYear
                WHERE
                   ProgramForYear.f11700 = '".$sProgramAgeX2."'
                   AND ProgramForYear.f12590 = '".$sJobId."'
                   AND ProgramForYear.f11720 = '".$sFormatFactO1."'
                   AND ProgramForYear.status = 0";
            if($vProgramForYearData2 = sql_query($sSqlQueryProgramForYear2)){
                if ($vProgramForYearRow2 = sql_fetch_assoc($vProgramForYearData2)) {
                    switch ($i)
                    {
                        case 1:
                            $iJobCodeM9_2 = intval($vProgramForYearRow2['JobCode']);
                            if($iJobCodeM9_1 < $iJobCodeM9_2) {
                                $sM5 = "нужно";
                            } else {
                                $sM5 = "не нужно";
                            }
                            break;
                        case 2:
                            $iJobCodeQ9_2 = intval($vProgramForYearRow2['JobCode']);
                            if($iJobCodeQ9_1 < $iJobCodeQ9_2) {
                                $sQ5 = "нужно";
                            } else {
                                $sQ5 = "не нужно";
                            }
                            break;
                        case 3:
                            $iJobCodeU9_2 = intval($vProgramForYearRow2['JobCode']);
                            if($iJobCodeU9_1 < $iJobCodeU9_2) {
                                $sU5 = "нужно";
                            } else {
                                $sU5 = "не нужно";
                            }
                            break;
                        case 4:
                            $iJobCodeY9_2 = intval($vProgramForYearRow2['JobCode']);
                            if($iJobCodeY9_1 < $iJobCodeY9_2) {
                                $sY5 = "нужно";
                            } else {
                                $sY5 = "не нужно";
                            }
                            break;
                    }
                }
            }

            //Чему учились

            $sSqlQueryWhatDidLearn = "SELECT
                           WhatDidLearn.Id AS WhatDidLearnId
                           , IFNULL(WhatDidLearn.f13860, '') AS WhatDidLearnCode
                           , IFNULL(WhatDidLearn.f10680, '') AS WhatDidLearnName
                        FROM
                            " . DATA_TABLE . get_table_id(640) . " AS WhatDidLearn
                        WHERE
                           WhatDidLearn.f12580 = '".$sJobId."'
                           AND WhatDidLearn.status = 0";
            if($vWhatDidLearnData = sql_query($sSqlQueryWhatDidLearn)){
                while ($vWhatDidLearnRow = sql_fetch_assoc($vWhatDidLearnData)) {
                    switch ($i)
                    {
                        case 1:
                            $vWhatDidLearnJ28[] = array("WhatDidLearnId" => $vWhatDidLearnRow["WhatDidLearnId"], "WhatDidLearnCode" => $vWhatDidLearnRow["WhatDidLearnCode"], "WhatDidLearnName" => $vWhatDidLearnRow["WhatDidLearnName"]);
                            break;
                        case 2:
                            $vWhatDidLearnN28[] = array("WhatDidLearnId" => $vWhatDidLearnRow["WhatDidLearnId"], "WhatDidLearnCode" => $vWhatDidLearnRow["WhatDidLearnCode"], "WhatDidLearnName" => $vWhatDidLearnRow["WhatDidLearnName"]);
                            break;
                        case 3:
                            $vWhatDidLearnR28[] = array("WhatDidLearnId" => $vWhatDidLearnRow["WhatDidLearnId"], "WhatDidLearnCode" => $vWhatDidLearnRow["WhatDidLearnCode"], "WhatDidLearnName" => $vWhatDidLearnRow["WhatDidLearnName"]);
                            break;
                        case 4:
                            $vWhatDidLearnV28[] = array("WhatDidLearnId" => $vWhatDidLearnRow["WhatDidLearnId"], "WhatDidLearnCode" => $vWhatDidLearnRow["WhatDidLearnCode"], "WhatDidLearnName" => $vWhatDidLearnRow["WhatDidLearnName"]);
                            break;
                    }
                }
            }
        }
    }
    /// ТУТ ИТОГОВЫЕ КОММЕНТАРИИ
    //Комментарий по теме
    if($sJobNameJ9 != ""){
        $sSqlQueryThemeComment = "SELECT
                                ThemeCommentData.ThemeCommentDate AS ThemeCommentDate
                                , ThemeCommentData.ThemeCommentText AS ThemeCommentText
                            FROM (SELECT
                               ThemeComment1.f12750 AS ThemeCommentDate
                               , IFNULL(ThemeComment1.f13180, '') AS ThemeCommentText
                            FROM
                                " . DATA_TABLE . get_table_id(780) . " AS ThemeComment1
                            WHERE
                               ThemeComment1.f12870 = '".$iGroupId."'
                               AND ThemeComment1.f16430 = '".$sTopicJ8."'
                               AND ThemeComment1.status = 0
                            UNION
                            SELECT
                               ThemeComment2.f12750 AS ThemeCommentDate
                               , IFNULL(ThemeComment2.f13250, '') AS ThemeCommentText
                            FROM
                                " . DATA_TABLE . get_table_id(780) . " AS ThemeComment2
                            WHERE
                               ThemeComment2.f12870 = '".$iGroupId."'
                               AND ThemeComment2.f16440 = '".$sTopicJ8."'
                               AND ThemeComment2.status = 0
                            UNION
                            SELECT
                               ThemeComment3.f12750 AS ThemeCommentDate
                               , IFNULL(ThemeComment3.f13320, '') AS ThemeCommentText
                            FROM
                                " . DATA_TABLE . get_table_id(780) . " AS ThemeComment3
                            WHERE
                               ThemeComment3.f12870 = '".$iGroupId."'
                               AND ThemeComment3.f16450 = '".$sTopicJ8."'
                               AND ThemeComment3.status = 0
                            UNION
                            SELECT
                               ThemeComment4.f12750 AS ThemeCommentDate
                               , IFNULL(ThemeComment4.f13400, '') AS ThemeCommentText
                            FROM
                                " . DATA_TABLE . get_table_id(780) . " AS ThemeComment4
                            WHERE
                               ThemeComment4.f12870 = '".$iGroupId."'
                               AND ThemeComment4.f16460 = '".$sTopicJ8."'
                               AND ThemeComment4.status = 0
                            ) AS ThemeCommentData
                        ORDER BY
                            ThemeCommentData.ThemeCommentDate DESC LIMIT 1";
        if($vThemeCommentData = sql_query($sSqlQueryThemeComment)){
            if ($vThemeCommentRow = sql_fetch_assoc($vThemeCommentData)) {
                if($vThemeCommentRow['ThemeCommentText'] != "") {
                    $dDateTheme = new DateTime($vThemeCommentRow['ThemeCommentDate']);
                    $sThemeJ34 = $dDateTheme->format("d.m.Y") ."<br>". $vThemeCommentRow['ThemeCommentText'];
                }
            }
        }
    }
    //Комментарий по теме
    if($sJobNameN9 != "") {
        $sSqlQueryThemeComment = "SELECT
                                ThemeCommentData.ThemeCommentDate AS ThemeCommentDate
                                , ThemeCommentData.ThemeCommentText AS ThemeCommentText
                            FROM (SELECT
                               ThemeComment1.f12750 AS ThemeCommentDate
                               , IFNULL(ThemeComment1.f13180, '') AS ThemeCommentText
                            FROM
                                " . DATA_TABLE . get_table_id(780) . " AS ThemeComment1
                            WHERE
                               ThemeComment1.f12870 = '".$iGroupId."'
                               AND ThemeComment1.f16430 = '".$sTopicN8."'
                               AND ThemeComment1.status = 0
                            UNION
                            SELECT
                               ThemeComment2.f12750 AS ThemeCommentDate
                               , IFNULL(ThemeComment2.f13250, '') AS ThemeCommentText
                            FROM
                                " . DATA_TABLE . get_table_id(780) . " AS ThemeComment2
                            WHERE
                               ThemeComment2.f12870 = '".$iGroupId."'
                               AND ThemeComment2.f16440 = '".$sTopicN8."'
                               AND ThemeComment2.status = 0
                            UNION
                            SELECT
                               ThemeComment3.f12750 AS ThemeCommentDate
                               , IFNULL(ThemeComment3.f13320, '') AS ThemeCommentText
                            FROM
                                " . DATA_TABLE . get_table_id(780) . " AS ThemeComment3
                            WHERE
                               ThemeComment3.f12870 = '".$iGroupId."'
                               AND ThemeComment3.f16450 = '".$sTopicN8."'
                               AND ThemeComment3.status = 0
                            UNION
                            SELECT
                               ThemeComment4.f12750 AS ThemeCommentDate
                               , IFNULL(ThemeComment4.f13400, '') AS ThemeCommentText
                            FROM
                                " . DATA_TABLE . get_table_id(780) . " AS ThemeComment4
                            WHERE
                               ThemeComment4.f12870 = '".$iGroupId."'
                               AND ThemeComment4.f16460 = '".$sTopicN8."'
                               AND ThemeComment4.status = 0
                            ) AS ThemeCommentData
                        ORDER BY
                            ThemeCommentDate DESC LIMIT 1";
        if($vThemeCommentData = sql_query($sSqlQueryThemeComment)){
            if ($vThemeCommentRow = sql_fetch_assoc($vThemeCommentData)) {
                if($vThemeCommentRow['ThemeCommentText'] != "") {
                    $dDateTheme = new DateTime($vThemeCommentRow['ThemeCommentDate']);
                    $sThemeN34 = $dDateTheme->format("d.m.Y") ."<br>". $vThemeCommentRow['ThemeCommentText'];
                }
            }
        }
    }
    if($sJobNameR9 != ""){
        //Комментарий по теме
        $sSqlQueryThemeComment = "SELECT
                                ThemeCommentData.ThemeCommentDate AS ThemeCommentDate
                                , ThemeCommentData.ThemeCommentText AS ThemeCommentText
                            FROM (SELECT
                               ThemeComment1.f12750 AS ThemeCommentDate
                               , IFNULL(ThemeComment1.f13180, '') AS ThemeCommentText
                            FROM
                                " . DATA_TABLE . get_table_id(780) . " AS ThemeComment1
                            WHERE
                               ThemeComment1.f12870 = '".$iGroupId."'
                               AND ThemeComment1.f16430 = '".$sTopicR8."'
                               AND ThemeComment1.status = 0
                            UNION
                            SELECT
                               ThemeComment2.f12750 AS ThemeCommentDate
                               , IFNULL(ThemeComment2.f13250, '') AS ThemeCommentText
                            FROM
                                " . DATA_TABLE . get_table_id(780) . " AS ThemeComment2
                            WHERE
                               ThemeComment2.f12870 = '".$iGroupId."'
                               AND ThemeComment2.f16440 = '".$sTopicR8."'
                               AND ThemeComment2.status = 0
                            UNION
                            SELECT
                               ThemeComment3.f12750 AS ThemeCommentDate
                               , IFNULL(ThemeComment3.f13320, '') AS ThemeCommentText
                            FROM
                                " . DATA_TABLE . get_table_id(780) . " AS ThemeComment3
                            WHERE
                               ThemeComment3.f12870 = '".$iGroupId."'
                               AND ThemeComment3.f16450 = '".$sTopicR8."'
                               AND ThemeComment3.status = 0
                            UNION
                            SELECT
                               ThemeComment4.f12750 AS ThemeCommentDate
                               , IFNULL(ThemeComment4.f13400, '') AS ThemeCommentText
                            FROM
                                " . DATA_TABLE . get_table_id(780) . " AS ThemeComment4
                            WHERE
                               ThemeComment4.f12870 = '".$iGroupId."'
                               AND ThemeComment4.f16460 = '".$sTopicR8."'
                               AND ThemeComment4.status = 0
                            ) AS ThemeCommentData
                        ORDER BY
                            ThemeCommentDate DESC LIMIT 1";
        if($vThemeCommentData = sql_query($sSqlQueryThemeComment)){
            if ($vThemeCommentRow = sql_fetch_assoc($vThemeCommentData)) {
                if($vThemeCommentRow['ThemeCommentText'] != "") {
                    $dDateTheme = new DateTime($vThemeCommentRow['ThemeCommentDate']);
                    $sThemeR34 = $dDateTheme->format("d.m.Y") ."<br>". $vThemeCommentRow['ThemeCommentText'];
                }
            }
        }
    }
    if($sJobNameV9 != ""){
        //Комментарий по теме
        $sSqlQueryThemeComment = "SELECT
                                ThemeCommentData.ThemeCommentDate AS ThemeCommentDate
                                , ThemeCommentData.ThemeCommentText AS ThemeCommentText
                            FROM (SELECT
                               ThemeComment1.f12750 AS ThemeCommentDate
                               , IFNULL(ThemeComment1.f13180, '') AS ThemeCommentText
                            FROM
                                " . DATA_TABLE . get_table_id(780) . " AS ThemeComment1
                            WHERE
                               ThemeComment1.f12870 = '".$iGroupId."'
                               AND ThemeComment1.f16430 = '".$sTopicV8."'
                               AND ThemeComment1.status = 0
                            UNION
                            SELECT
                               ThemeComment2.f12750 AS ThemeCommentDate
                               , IFNULL(ThemeComment2.f13250, '') AS ThemeCommentText
                            FROM
                                " . DATA_TABLE . get_table_id(780) . " AS ThemeComment2
                            WHERE
                               ThemeComment2.f12870 = '".$iGroupId."'
                               AND ThemeComment2.f16440 = '".$sTopicV8."'
                               AND ThemeComment2.status = 0
                            UNION
                            SELECT
                               ThemeComment3.f12750 AS ThemeCommentDate
                               , IFNULL(ThemeComment3.f13320, '') AS ThemeCommentText
                            FROM
                                " . DATA_TABLE . get_table_id(780) . " AS ThemeComment3
                            WHERE
                               ThemeComment3.f12870 = '".$iGroupId."'
                               AND ThemeComment3.f16450 = '".$sTopicV8."'
                               AND ThemeComment3.status = 0
                            UNION
                            SELECT
                               ThemeComment4.f12750 AS ThemeCommentDate
                               , IFNULL(ThemeComment4.f13400, '') AS ThemeCommentText
                            FROM
                                " . DATA_TABLE . get_table_id(780) . " AS ThemeComment4
                            WHERE
                               ThemeComment4.f12870 = '".$iGroupId."'
                               AND ThemeComment4.f16460 = '".$sTopicV8."'
                               AND ThemeComment4.status = 0
                            ) AS ThemeCommentData
                        ORDER BY
                            ThemeCommentDate DESC LIMIT 1";
        if($vThemeCommentData = sql_query($sSqlQueryThemeComment)){
            if ($vThemeCommentRow = sql_fetch_assoc($vThemeCommentData)) {
                if($vThemeCommentRow['ThemeCommentText'] != "") {
                    $dDateTheme = new DateTime($vThemeCommentRow['ThemeCommentDate']);
                    $sThemeV34 = $dDateTheme->format("d.m.Y") ."<br>". $vThemeCommentRow['ThemeCommentText'];
                }
            }
        }
    }
    if($sJobNameJ9 != "") {
        //Комментарий по заданию
        $sSqlQueryTaskComment = "SELECT
                                TaskCommentData.TaskCommentDate AS TaskCommentDate
                                , TaskCommentData.TaskCommentText AS TaskCommentText
                            FROM (SELECT
                               TaskComment1.f12750 AS TaskCommentDate
                               , TaskComment1.f12960 AS TaskCommentText
                            FROM
                                " . DATA_TABLE . get_table_id(780) . " AS TaskComment1
                            WHERE
                               TaskComment1.f12870 = '".$iGroupId."'
                               AND TaskComment1.f12930 = '".$sJobCodeJ11."'
                               AND TaskComment1.status = 0
                            UNION
                            SELECT
                               TaskComment2.f12750 AS TaskCommentDate
                               , TaskComment2.f13280 AS TaskCommentText
                            FROM
                                " . DATA_TABLE . get_table_id(780) . " AS TaskComment2
                            WHERE
                               TaskComment2.f12870 = '".$iGroupId."'
                               AND TaskComment2.f13260 = '".$sJobCodeJ11."'
                               AND TaskComment2.status = 0
                            UNION
                            SELECT
                               TaskComment3.f12750 AS TaskCommentDate
                               , TaskComment3.f13360 AS TaskCommentText
                            FROM
                                " . DATA_TABLE . get_table_id(780) . " AS TaskComment3
                            WHERE
                               TaskComment3.f12870 = '".$iGroupId."'
                               AND TaskComment3.f13330 = '".$sJobCodeJ11."'
                               AND TaskComment3.status = 0
                            UNION
                            SELECT
                               TaskComment4.f12750 AS TaskCommentDate
                               , TaskComment4.f13430 AS TaskCommentText
                            FROM
                                " . DATA_TABLE . get_table_id(780) . " AS TaskComment4
                            WHERE
                               TaskComment4.f12870 = '".$iGroupId."'
                               AND TaskComment4.f13410  = '".$sJobCodeJ11."'
                               AND TaskComment4.status = 0) AS TaskCommentData
                        ORDER BY
                            TaskCommentDate DESC LIMIT 1";
        if($vTaskCommentData = sql_query($sSqlQueryTaskComment)){
            if ($vTaskCommentRow = sql_fetch_assoc($vTaskCommentData)) {
                if($vTaskCommentRow['TaskCommentText'] != "") {
                    $dDateTheme = new DateTime($vTaskCommentRow['TaskCommentDate']);
                    $sTaskJ35 = $dDateTheme->format("d.m.Y") ."<br>". $vTaskCommentRow['TaskCommentText'];
                }
            }
        }
    }
    if($sJobNameN9 != ""){
        //Комментарий по заданию
        $sSqlQueryTaskComment = "SELECT
                                TaskCommentData.TaskCommentDate AS TaskCommentDate
                                , TaskCommentData.TaskCommentText AS TaskCommentText
                            FROM (SELECT
                               TaskComment1.f12750 AS TaskCommentDate
                               , TaskComment1.f12960 AS TaskCommentText
                            FROM
                                " . DATA_TABLE . get_table_id(780) . " AS TaskComment1
                            WHERE
                               TaskComment1.f12870 = '".$iGroupId."'
                               AND TaskComment1.f12930 = '".$sJobCodeN11."'
                               AND TaskComment1.status = 0
                            UNION
                            SELECT
                               TaskComment2.f12750 AS TaskCommentDate
                               , TaskComment2.f13280 AS TaskCommentText
                            FROM
                                " . DATA_TABLE . get_table_id(780) . " AS TaskComment2
                            WHERE
                               TaskComment2.f12870 = '".$iGroupId."'
                               AND TaskComment2.f13260 = '".$sJobCodeN11."'
                               AND TaskComment2.status = 0
                            UNION
                            SELECT
                               TaskComment3.f12750 AS TaskCommentDate
                               , TaskComment3.f13360 AS TaskCommentText
                            FROM
                                " . DATA_TABLE . get_table_id(780) . " AS TaskComment3
                            WHERE
                               TaskComment3.f12870 = '".$iGroupId."'
                               AND TaskComment3.f13330 = '".$sJobCodeN11."'
                               AND TaskComment3.status = 0
                            UNION
                            SELECT
                               TaskComment4.f12750 AS TaskCommentDate
                               , TaskComment4.f13430 AS TaskCommentText
                            FROM
                                " . DATA_TABLE . get_table_id(780) . " AS TaskComment4
                            WHERE
                               TaskComment4.f12870 = '".$iGroupId."'
                               AND TaskComment4.f13410  = '".$sJobCodeN11."'
                               AND TaskComment4.status = 0) AS TaskCommentData
                        ORDER BY
                            TaskCommentDate DESC LIMIT 1";
        if($vTaskCommentData = sql_query($sSqlQueryTaskComment)){
            if ($vTaskCommentRow = sql_fetch_assoc($vTaskCommentData)) {
                if($vTaskCommentRow['TaskCommentText'] != "") {
                    $dDateTheme = new DateTime($vTaskCommentRow['TaskCommentDate']);
                    $sTaskN35 = $dDateTheme->format("d.m.Y") ."<br>". $vTaskCommentRow['TaskCommentText'];
                }
            }
        }
    }
    if($sJobNameR9 != ""){
        //Комментарий по заданию
        $sSqlQueryTaskComment = "SELECT
                                TaskCommentData.TaskCommentDate AS TaskCommentDate
                                , TaskCommentData.TaskCommentText AS TaskCommentText
                            FROM (SELECT
                               TaskComment1.f12750 AS TaskCommentDate
                               , TaskComment1.f12960 AS TaskCommentText
                            FROM
                                " . DATA_TABLE . get_table_id(780) . " AS TaskComment1
                            WHERE
                               TaskComment1.f12870 = '".$iGroupId."'
                               AND TaskComment1.f12930 = '".$sJobCodeR11."'
                               AND TaskComment1.status = 0
                            UNION
                            SELECT
                               TaskComment2.f12750 AS TaskCommentDate
                               , TaskComment2.f13280 AS TaskCommentText
                            FROM
                                " . DATA_TABLE . get_table_id(780) . " AS TaskComment2
                            WHERE
                               TaskComment2.f12870 = '".$iGroupId."'
                               AND TaskComment2.f13260 = '".$sJobCodeR11."'
                               AND TaskComment2.status = 0
                            UNION
                            SELECT
                               TaskComment3.f12750 AS TaskCommentDate
                               , TaskComment3.f13360 AS TaskCommentText
                            FROM
                                " . DATA_TABLE . get_table_id(780) . " AS TaskComment3
                            WHERE
                               TaskComment3.f12870 = '".$iGroupId."'
                               AND TaskComment3.f13330 = '".$sJobCodeR11."'
                               AND TaskComment3.status = 0
                            UNION
                            SELECT
                               TaskComment4.f12750 AS TaskCommentDate
                               , TaskComment4.f13430 AS TaskCommentText
                            FROM
                                " . DATA_TABLE . get_table_id(780) . " AS TaskComment4
                            WHERE
                               TaskComment4.f12870 = '".$iGroupId."'
                               AND TaskComment4.f13410  = '".$sJobCodeR11."'
                               AND TaskComment4.status = 0) AS TaskCommentData
                        ORDER BY
                            TaskCommentDate DESC LIMIT 1";
        //echo $sSqlQueryTaskComment."<br>";
        if($vTaskCommentData = sql_query($sSqlQueryTaskComment)){
            if ($vTaskCommentRow = sql_fetch_assoc($vTaskCommentData)) {
                if($vTaskCommentRow['TaskCommentText'] != "") {
                    $dDateTheme = new DateTime($vTaskCommentRow['TaskCommentDate']);
                    $sTaskR35 = $dDateTheme->format("d.m.Y") ."<br>". $vTaskCommentRow['TaskCommentText'];
                }
            }
        }
    }
    if($sJobNameV9 != ""){
        //Комментарий по заданию
        $sSqlQueryTaskComment = "SELECT
                                TaskCommentData.TaskCommentDate AS TaskCommentDate
                                , TaskCommentData.TaskCommentText AS TaskCommentText
                            FROM (SELECT
                               TaskComment1.f12750 AS TaskCommentDate
                               , TaskComment1.f12960 AS TaskCommentText
                            FROM
                                " . DATA_TABLE . get_table_id(780) . " AS TaskComment1
                            WHERE
                               TaskComment1.f12870 = '".$iGroupId."'
                               AND TaskComment1.f12930 = '".$sJobCodeV11."'
                               AND TaskComment1.status = 0
                            UNION
                            SELECT
                               TaskComment2.f12750 AS TaskCommentDate
                               , TaskComment2.f13280 AS TaskCommentText
                            FROM
                                " . DATA_TABLE . get_table_id(780) . " AS TaskComment2
                            WHERE
                               TaskComment2.f12870 = '".$iGroupId."'
                               AND TaskComment2.f13260 = '".$sJobCodeV11."'
                               AND TaskComment2.status = 0
                            UNION
                            SELECT
                               TaskComment3.f12750 AS TaskCommentDate
                               , TaskComment3.f13360 AS TaskCommentText
                            FROM
                                " . DATA_TABLE . get_table_id(780) . " AS TaskComment3
                            WHERE
                               TaskComment3.f12870 = '".$iGroupId."'
                               AND TaskComment3.f13330 = '".$sJobCodeV11."'
                               AND TaskComment3.status = 0
                            UNION
                            SELECT
                               TaskComment4.f12750 AS TaskCommentDate
                               , TaskComment4.f13430 AS TaskCommentText
                            FROM
                                " . DATA_TABLE . get_table_id(780) . " AS TaskComment4
                            WHERE
                               TaskComment4.f12870 = '".$iGroupId."'
                               AND TaskComment4.f13410  = '".$sJobCodeV11."'
                               AND TaskComment4.status = 0) AS TaskCommentData
                        ORDER BY
                            TaskCommentDate DESC LIMIT 1";
        if($vTaskCommentData = sql_query($sSqlQueryTaskComment)){
            if ($vTaskCommentRow = sql_fetch_assoc($vTaskCommentData)) {
                if($vTaskCommentRow['TaskCommentText'] != "") {
                    $dDateTheme = new DateTime($vTaskCommentRow['TaskCommentDate']);
                    $sTaskV35 = $dDateTheme->format("d.m.Y") ."<br>". $vTaskCommentRow['TaskCommentText'];
                }
            }
        }
    }
    //f11480 Название группы факт f11580 Дата зачисления f11590 Дата отчисления
    $sSqlQueryStudents = "SELECT ClienCards.id as ChildrenId
                        , ClienCards.f9750 as ChildrenFIO
                        , IFNULL((SELECT MAX(WorkingOff2.id) FROM " . DATA_TABLE . get_table_id(820) . " AS WorkingOff2  WHERE WorkingOff2.f14920 = '" . $iGroupId . "' AND WorkingOff2.f14900 = '".$sSearchDate."' AND WorkingOff2.f14890 = ClienCards.id AND WorkingOff2.status = 0),-1) AS nn
                        , Students0.f11490 AS ClassGroup
                        , IFNULL(PickGives.f11410, '') AS PickGivesName
                        , IFNULL((SELECT SUM(ClassGrades.f14690) FROM " . DATA_TABLE . get_table_id(810) . " AS ClassGrades WHERE ClassGrades.f14680 = ClienCards.id AND ClassGrades.status = 0),0) AS Stars
                        , IFNULL((SELECT SUM(DelivGifts.f16420) FROM " . DATA_TABLE . get_table_id(850) . " AS DelivGifts WHERE DelivGifts.f15270 = ClienCards.id AND DelivGifts.status = 0),0) AS Gifts
                    FROM
                        " . DATA_TABLE . get_table_id(530) . " AS ClienCards
                        LEFT JOIN " . DATA_TABLE . get_table_id(720) . " AS Students0
                            ON (Students0.f11460 = ClienCards.id AND Students0.status = 0)
                        LEFT JOIN " . DATA_TABLE . get_table_id(710) . " AS PickGives
                            ON (Students0.f11510 = PickGives.id AND PickGives.status = 0)
                    WHERE
                        ClienCards.id IN (
                            SELECT Students.f11460 as ChildrenId
                                                FROM
                                                    " . DATA_TABLE . get_table_id(720) . " AS Students
                                                WHERE
                                                    Students.f11480 = '" . $iGroupId . "'
                                                    AND ((Students.f11580 <='" . $sSearchDate . "' AND Students.f11590 IS NULL AND Students.f11580 <>'" . $sDateZero . "')
                                                    OR (Students.f11580 <='" . $sSearchDate . "' AND Students.f11590 = '" . $sDateZero . "' AND Students.f11580 <>'" . $sDateZero . "')
                                                    OR (Students.f11580 <>'" . $sDateZero . "' AND Students.f11580 <='" . $sSearchDate . "' AND Students.f11590 >= '" . $sSearchDate . "' AND Students.f11590 <>'" . $sDateZero . "'))
                                                    AND Students.status = 0
                                                UNION
                                                SELECT
                                                    WorkingOff.f14890  as ChildrenId
                                                FROM
                                                    ".DATA_TABLE.get_table_id(820)." AS WorkingOff
                                                WHERE
                                                    WorkingOff.f14960 = '" . $iGroupId . "'
                                                    AND WorkingOff.f15070 = '" . $sSearchDate . "'
                                                    AND WorkingOff.status = 0
                            )
                        AND ClienCards.status = 0
                    ORDER BY ChildrenFIO";
    //                        AND Students0.f11480 = '" . $iGroupId . "'
    $iPos = 0;
   // echo $sSqlQueryStudents;
    if($vStudentsData = sql_query($sSqlQueryStudents)) {
        while ($vStudentRow = sql_fetch_assoc($vStudentsData)) {
            $iPos++;

            $vTableData['ChildrenId'] = $vStudentRow['ChildrenId'];
            $vTableData['ChildrenFIO'] = form_display($vStudentRow['ChildrenFIO']);
            $vTableData['nn'] = $vStudentRow['nn'];
            if(intval($vStudentRow['nn']) == -1){
                $vTableData['iPosn'] = $iPos;
            } else{
                $vTableData['iPosn'] = "н";
            }
            $vTableData['iPos'] = $iPos;
            $vTableData['ClassGroup'] = $vStudentRow['ClassGroup'];
            $vTableData['PickGivesName'] = $vStudentRow['PickGivesName'];
            $vTableData['Stars'] = $vStudentRow['Stars'];
            $vTableData['Gifts'] = $vStudentRow['Gifts'];

            $iTrialCount = 0;
            $vTableData['Trial'] = "";
            $sSqlQueryTrial = "SELECT
                        WorkingOff3.f14930 AS Trial
                    FROM
                        " . DATA_TABLE . get_table_id(820) . " AS WorkingOff3
                    WHERE
                        WorkingOff3.f14960 = '" . $iGroupId . "'
                        AND WorkingOff3.f14890 = '" . $vStudentRow['ChildrenId'] . "'
                        AND WorkingOff3.f15070 = '" . $sSearchDate . "'
                        AND WorkingOff3.status = 0";
            if($vTrialData = sql_query($sSqlQueryTrial)){
                while ($vTrialRow = sql_fetch_assoc($vTrialData)) {
                    $iTrialCount++;
                    $vTableData['Trial'] = $vTrialRow['Trial'];
                }
            }
            if($iTrialCount > 1){
                $vTableData['Trial'] = "";
            }

            $iWorkingOffCount = 0;
            $vTableData['WorkingOff'] = "";

            $sSqlQueryWorkingOff = "SELECT
                        WorkingOff3.f14900 AS WorkingOff
                    FROM
                        " . DATA_TABLE . get_table_id(820) . " AS WorkingOff3
                    WHERE
                        WorkingOff3.f14960 = '" . $iGroupId . "'
                        AND WorkingOff3.f14890 = '" . $vStudentRow['ChildrenId'] . "'
                        AND WorkingOff3.f15070 = '" . $sSearchDate . "'
                        AND WorkingOff3.f14900 <> '" . $sDateZero . "'
                        AND NOT WorkingOff3.f14900 IS NULL
                        AND WorkingOff3.status = 0";
            if($vWorkingOffData = sql_query($sSqlQueryWorkingOff)){
                while ($vWorkingOffRow = sql_fetch_assoc($vWorkingOffData)) {
                    if($iWorkingOffCount == 0) {
                        $vTableData['WorkingOff'] = $vWorkingOffRow['WorkingOff'];
                    } else {
                        if($vTableData['WorkingOff'] != $vWorkingOffRow['WorkingOff']) {
                            $vTableData['WorkingOff'] = "";
                            break;
                        }
                    }
                    $iWorkingOffCount++;
                }
            }
            if($vTableData['WorkingOff'] != ""){
                $dWorkingOff = new DateTime($vTableData['WorkingOff']);
                $vTableData['WorkingOff'] = $dWorkingOff->format("d.m.Y");
            }

            $iWorkingOffCount2 = 0;
            $vTableData['WorkingOffReport'] = "";

            $sSqlQueryWorkingOff2 = "SELECT
                        IFNULL(WorkingOff3.f15060, '') AS WorkingOffReport
                    FROM
                        " . DATA_TABLE . get_table_id(820) . " AS WorkingOff3
                    WHERE
                        WorkingOff3.f14960 = '" . $iGroupId . "'
                        AND WorkingOff3.f14890 = '" . $vStudentRow['ChildrenId'] . "'
                        AND WorkingOff3.f15070 = '" . $sSearchDate . "'
                        AND WorkingOff3.status = 0";
            if($vWorkingOffData2 = sql_query($sSqlQueryWorkingOff2)){
                while ($vWorkingOffRow2 = sql_fetch_assoc($vWorkingOffData2)) {
                    if($iWorkingOffCount2 == 0) {
                        $vTableData['WorkingOffReport'] = $vWorkingOffRow2['WorkingOffReport'];
                    } else {
                        break;
                    }
                    $iWorkingOffCount2++;
                }
            }

            $sSqlQueryTeacherMessages = "SELECT
                        IFNULL(TeacherMessage.f15050, '') AS TeacherMessageText
                    FROM
                        " . DATA_TABLE . get_table_id(830) . " AS TeacherMessage
                    WHERE
                        TeacherMessage.f15040 = '" . $iGroupId . "'
                        AND TeacherMessage.f15020 = '" . $vStudentRow['ChildrenId'] . "'
                        AND TeacherMessage.f15030 = '" . $sSearchDate . "'
                        AND TeacherMessage.status = 0";
            if($vTeacherMessageData2 = sql_query($sSqlQueryTeacherMessages)){
                while ($vTeacherMessageRow2 = sql_fetch_assoc($vTeacherMessageData2)) {
                    if($vTableData['WorkingOffReport'] != "") {
                        $vTableData['WorkingOffReport'] = $vTableData['WorkingOffReport'].";".$vTeacherMessageRow2['TeacherMessageText'];
                    } else {
                        $vTableData['WorkingOffReport'] = $vTeacherMessageRow2['TeacherMessageText'];
                    }
                }
            }

            $vLines[] = $vTableData;
        }
    }
}

$result = sql_select_field("" . SCHEMES_TABLE . "", "color3", "active='1'");
$row = sql_fetch_assoc($result);

$smarty->assign("color3", $row['color3']);
$smarty->assign("vGroups", $vGroups);
$smarty->assign("iGroupId", $iGroupId);
$smarty->assign("bIsAdmin", $bIsAdmin);
$smarty->assign("sCabinetH1", $sCabinetH1);
$smarty->assign("sFormatPlanL1", $sFormatPlanL1);
$smarty->assign("sFormatFactO1", $sFormatFactO1);
$smarty->assign("sWeekDayV1", $sWeekDayV1);
$smarty->assign("sDateTimeT1", $dSearchDate->format("d.m.Y"));
$smarty->assign("sLessonTimeX1", $sLessonTimeX1);
$smarty->assign("sTeacherFioFactD2", $sTeacherFioFactD2);
$smarty->assign("sDepartmentNameH2", $sDepartmentNameH2);
$smarty->assign("sDepartmentAddressL2", $sDepartmentAddressL2);
$smarty->assign("sProgramAgeX2", $sProgramAgeX2);
$smarty->assign("sAcademicYearU2", $sAcademicYearU2);
$smarty->assign("sWeekLessonR2", $sWeekLessonR2);
$smarty->assign("sProgramForYearL3", $sProgramForYearL3);
$smarty->assign("sSubsectionP3", $sSubsectionP3);
$smarty->assign("sJobNameT3", $sJobNameT3);
$smarty->assign("sPrintOutsPdf", $sPrintOutsPdf);

$smarty->assign("sM5", $sM5);
$smarty->assign("sQ5", $sQ5);
$smarty->assign("sU5", $sU5);
$smarty->assign("sY5", $sY5);

$smarty->assign("sSectionJ6", $sSectionJ6);
$smarty->assign("sSectionN6", $sSectionN6);
$smarty->assign("sSectionR6", $sSectionR6);
$smarty->assign("sSectionV6", $sSectionV6);

$smarty->assign("sSubsectionJ7", $sSubsectionJ7);
$smarty->assign("sSubsectionN7", $sSubsectionN7);
$smarty->assign("sSubsectionR7", $sSubsectionR7);
$smarty->assign("sSubsectionV7", $sSubsectionV7);

$smarty->assign("sTopicJ8", $sTopicJ8);
$smarty->assign("sTopicN8", $sTopicN8);
$smarty->assign("sTopicR8", $sTopicR8);
$smarty->assign("sTopicV8", $sTopicV8);

$smarty->assign("sJobNameJ9", $sJobNameJ9);
$smarty->assign("sJobNameN9", $sJobNameN9);
$smarty->assign("sJobNameR9", $sJobNameR9);
$smarty->assign("sJobNameV9", $sJobNameV9);

$smarty->assign("sJobCodeM9", strval($iJobCodeM9_1)." из ".strval($iJobCodeM9_2));
$smarty->assign("sJobCodeQ9", strval($iJobCodeQ9_1)." из ".strval($iJobCodeQ9_2));
$smarty->assign("sJobCodeU9", strval($iJobCodeU9_1)." из ".strval($iJobCodeU9_2));
$smarty->assign("sJobCodeY9", strval($iJobCodeY9_1)." из ".strval($iJobCodeY9_2));

$smarty->assign("sJobCodeJ11", $sJobCodeJ11);
$smarty->assign("sJobCodeN11", $sJobCodeN11);
$smarty->assign("sJobCodeR11", $sJobCodeR11);
$smarty->assign("sJobCodeV11", $sJobCodeV11);

$smarty->assign("lines", $vLines);

$smarty->assign("sPreviosCommentJ33", $sPreviosCommentJ33);

$smarty->assign("sThemeJ34", $sThemeJ34);
$smarty->assign("sThemeN34", $sThemeN34);
$smarty->assign("sThemeR34", $sThemeR34);
$smarty->assign("sThemeV34", $sThemeV34);

$smarty->assign("sTaskJ35", $sTaskJ35);
$smarty->assign("sTaskN35", $sTaskN35);
$smarty->assign("sTaskR35", $sTaskR35);
$smarty->assign("sTaskV35", $sTaskV35);

$smarty->assign("vTeachers", $vTeachers);
$smarty->assign("vGifts", $vGifts);
$smarty->assign("vWhatDidLearnJ28", $vWhatDidLearnJ28);
$smarty->assign("vWhatDidLearnN28", $vWhatDidLearnN28);
$smarty->assign("vWhatDidLearnR28", $vWhatDidLearnR28);
$smarty->assign("vWhatDidLearnV28", $vWhatDidLearnV28);

$smarty->assign("iWhatDidLearnJ28Count", count($vWhatDidLearnJ28));
$smarty->assign("iWhatDidLearnN28Count", count($vWhatDidLearnN28));
$smarty->assign("iWhatDidLearnR28Count", count($vWhatDidLearnR28));
$smarty->assign("iWhatDidLearnV28Count", count($vWhatDidLearnV28));

$smarty->assign("vRecomendationJ44", $vRecomendationJ44);
$smarty->assign("vRecomendationN44", $vRecomendationN44);
$smarty->assign("vRecomendationR44", $vRecomendationR44);
$smarty->assign("vRecomendationV44", $vRecomendationV44);

$smarty->assign("iRecomendationJ44Count", count($vRecomendationJ44));
$smarty->assign("iRecomendationN44Count", count($vRecomendationN44));
$smarty->assign("iRecomendationR44Count", count($vRecomendationR44));
$smarty->assign("iRecomendationV44Count", count($vRecomendationV44));
$smarty->assign("iChildrensCount", count($vLines));
$smarty->assign("iTeacherFioFactId", $iGroupTeacherId);



$smarty->assign("vTasks", $vTasks);

$smarty->assign("sAcademicYearU2", $sAcademicYearU2);
$smarty->assign("sProgramAgeX2", $sProgramAgeX2);
$smarty->assign("FormaFact", $FormaFact);
$smarty->assign("WeekLesson", $WeekLesson);

function GetWeekPos($sProgramAgeX2, $sJobId, $sFormatFactO1, $iWeek){
    $sSqlQueryProgramForYear4 = "SELECT
                   COUNT(ProgramForYear.f11710) AS Week
                FROM
                    " . DATA_TABLE . get_table_id(730) . " AS ProgramForYear
                WHERE
                   ProgramForYear.f11700 = '".$sProgramAgeX2."'
                   AND ProgramForYear.f12590 = '".$sJobId."'
                   AND ProgramForYear.f11720 = '".$sFormatFactO1."'
                   AND CAST(ProgramForYear.f11710 AS SIGNED) < ".$iWeek."
                   AND ProgramForYear.status = 0";
    $i = 1;
    //echo $sSqlQueryProgramForYear4."<br>";
    if($vProgramForYearData4 = sql_query($sSqlQueryProgramForYear4)){
        if ($vProgramForYearRow4 = sql_fetch_assoc($vProgramForYearData4)) {
           return intval($vProgramForYearRow4['Week']) + 1;
        }
    }
    return $i;
}

function SaveFoto($iInsertId, $sFileFileldName, $sFiledId) {
    if(array_key_exists($sFileFileldName, $_FILES) && $_FILES[$sFileFileldName]['tmp_name'])
    {
        $sFileName = $_FILES[$sFileFileldName]["name"];
        $sContent = file_get_contents($_FILES[$sFileFileldName]['tmp_name']);//Если файл писать в базу
        //unlink($sFilePath.$sFilePath);

        if($iInsertId && !empty($sContent))
        {
            data_update(780,array('f'.$sFiledId=>$sFileName),"id=".$iInsertId);
            //  save_data_file($field_id,$line_id,$fname,$data)
            save_data_file($sFiledId, $iInsertId, $sFileName, $sContent);
        } else{
            echo "<h4><font color=red>Error. Ошибка! Не удалось загрузить файл на сервер!</font></h4>".$sFileName."<br>";
        }

    }
}