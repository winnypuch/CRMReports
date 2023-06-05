<?php
$aMonths = array(1 => 'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь');
if ($_REQUEST['iYear'] && $_REQUEST['sDateBeg'] && $_REQUEST['sDateEnd']) {
    $iYear = intval($_REQUEST['year']);
    $sDateBeg = date("Y-m-d 00:00:00", strtotime(form_eng_time($_REQUEST['sDateBeg'])));
    $sDateEnd = date("Y-m-d 00:00:00", strtotime(form_eng_time($_REQUEST['sDateEnd'])));
} else {
    $iYear = date("Y");
    $sDateBeg = date("Y-m-d 00:00:00", mktime(0, 0, 0, date("m"), date("d") - 7, date("Y")));
    $sDateEnd = date("Y-m-d 00:00:00", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
}
if ($_REQUEST['iGroupId']) {
    $iGroupId = $_REQUEST['iGroupId'];
} else {
    $iGroupId = 0;
}
if ($_REQUEST['bIsPerfomance']) {
    $bIsPerfomance = $_REQUEST['bIsPerfomance'];
} else {
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
        if(!array_key_exists("iFaultData", $aReportData)){
            $aReportData = ["iFaultData"=> 2.00, "iMinQtyClasses" => 10, "iMinQtyClassesSubdivision" => 3];
            data_update(970, array('status'=>'0', 'f17790'=>json_encode($aReportData)), "`f17780`='",$iReportId,"' AND status ='0'" );
        }
    }
}

if(!array_key_exists("iFaultData", $aReportData)){
    $aReportData = ["iFaultData"=> 2.00, "iMinQtyClasses" => 10, "iMinQtyClassesSubdivision" => 3];
    data_insert(970, array('status'=>'0', 'f17780'=>$iReportId, 'f17790'=>json_encode($aReportData)));
}
//сохраняем данные
if ($_REQUEST['iFaultData'] && $_REQUEST['iMinQtyClasses'] && $_REQUEST['iMinQtyClassesSubdivision']) {
    if($aReportData['iFaultData'] != floatval($_REQUEST['iFaultData']) || $aReportData['iMinQtyClasses'] != intval($_REQUEST['iMinQtyClasses']) || $aReportData['iMinQtyClassesSubdivision'] != intval($_REQUEST['iMinQtyClassesSubdivision'])){
        data_update(970, array('status'=>'0', 'f17790'=>json_encode($aReportData)), "`f17780`='",$iReportId,"' AND status ='0' ");
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
if (intval($_REQUEST['ChildrenId']) > 0) {
    if(intval($_REQUEST['Report']) == 1){

    } else{
        if(intval($_REQUEST['SendReport']) == 1){

        }
    }
    GenerateReport($vGroups, $iGroupId, intval($_REQUEST['ChildrenId']), $bIsPerfomance, $aReportData);
    //Расходы Декарт 940
    //f17420 Дата
    //f17480 Кому (ФИО)
    //f17450 Сумма
    //f17470 Статья
    //f17430 Пользователь
    //echo $_REQUEST['update_sum'] ."-". $_REQUEST['update_teacherid'] ."-". $_REQUEST['csrf'];
    //data_insert(940, array('status'=>'0', 'f17430'=>$iUserId, 'f17480' => intval($_REQUEST['update_teacherid']),'f17420'=>$sDateNow, 'f17470'=>'ЗП', 'f17450' => intval($_REQUEST['update_sum'])));
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
if($vStudentsData = sql_query($sSqlQueryStudents)) {
    while ($vStudentRow = sql_fetch_assoc($vStudentsData)) {
        $iQtyClasses = 0;
        $iReportState = 0;
    //810 Оценки на занятии f14680 ФИО ребенка f17280 Формат Факт f17260 Пр. возраст Дата f14820 Был? f14700
        $sSqlQueryGradesClass = "SELECT IFNULL(COUNT(*), 0) AS QtyClasses FROM " . DATA_TABLE . get_table_id(810) . " AS GradesClass
            WHERE
                GradesClass.f14680 = ".$vStudentRow['ChildrenId']."
                AND GradesClass.f17280 ='".$vGroups['g'.$iGroupId]['ClassFormat']."'
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
        $vLines[] = array("ChildrenId" => $vStudentRow['ChildrenId'], "ChildrenFIO"=> $vStudentRow['ChildrenFIO'], "QtyClasses" => $iQtyClasses, "ReportState" => $iReportState);
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

function GenerateReport($vGroups, $iGroupId, $iChildrenId, $bIsPerfomance, $aReportData){
    //$vGroups['g'.$iGroupId]['TeacherFioMin']
    //$vGroups['g'.$iGroupId]['ClassFormat']
    //$vGroups['g'.$iGroupId]['ProgramAge']
    //header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    $arData = [$vGroups['g'.$iGroupId]['TeacherFioMin'], "ffff", $vGroups['g'.$iGroupId]['ClassFormat'], $vGroups['g'.$iGroupId]['ProgramAge']];
    $arData[4] = "от до";
    $arData[5] = "3,54";
    $arData[6] = "4,38";
    $arLessons[0] = "Внимание и память";
    $arLessons[1] = "счёт";
    $arRows[0] = "yellow";
    $arRows[0] = "green";
    $arValuesAvg[0]="94";
    $arValuesAvg[1]="97";
    $arValues[0]="75";
    $arValues[1]="100";

    echo madeReport($arData, $bIsPerfomance, $arRows, $arLessons, $arValues, $arValuesAvg);
    header('Content-Type:text/html; charset=UTF-8');
    header("Content-Disposition: attachment; filename=Report.html"); //File name extension was wrong
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private", false);
    exit;
}



function madeReport($arData, $bIsPerfomance, $arRows, $arLessons, $arValues, $arValuesAvg) {

    require_once ('jpgraph/jpgraph.php');
    require_once ('jpgraph/jpgraph_radar.php');

    $arMaxValues = array_fill(0,count($arLessons),"100");

    $header = '<p>Педагог: '.$arData[0]. '</p>';
    $header .= '<p>Ученик: '.$arData[1]. '</p>';
    $header .= '<p>Формат занятий: '.$arData[2]. '</p>';
    $header .= '<p>Программный возраст: '.$arData[3]. '</p>';
    $header .= '<p>Период обучения: '.$arData[4]. '</p>';

    if($bIsPerfomance) {
        $header .= '<p>Успеваемость (5-бальная шкала): '.$arData[5]. '</p>';
        $header .= '<p>Средняя успеваемость: '.$arData[6]. '</p>';
    }

    $theader = '<html><head>
    <style type="text/css">
     body {font-size: 120%; font-family: Verdana, Arial, Helvetica, sans-serif; color: #333366;}
     .green {background-color: #cee2d3;}
     .yellow {background-color: #FAF3D2;}
     td.center {text-align: center;}
     td.left {text-align: left;}
    </style>
  </head><body>'.$header.'<div><table border="1" cellpadding="0" cellspacing="0">
    <tbody><tr height="20" style="">
     <td height="20" class="center" width="40%" style="">Тема</td>
     <td class="center" width="20%" style="">Максимальный уровень</td>
     <td class="center" width="20%" style="">Среднее по возрасту**</td>
     <td class="center" width="20%" style="">Уровень ребенка*</td>
    </tr>';

    foreach ($arRows as $key => $value) {
      $tbody .= '<tr height="20" class="'.$value.'" style="">
      <td height="20" class="left" style="">'.$arLessons[$key].'</td>
      <td class="center" style="">'.$arMaxValues[$key].'</td>
      <td class="center" style="">'.$arValuesAvg[$key].'</td>
      <td class="center" style="">'.$arValues[$key].'</td>
      </tr>
      ';
    }

    $tfooter = '</tbody></table></div><br><div>
   <table border="0" cellpadding="0" cellspacing="0">
   <tr height="20" style="">
        <td width="30%">*Уровень ребенка</td>
        <td width="70%">- способность ребенка справляться с заданиями на занятии. Определяется на основании заметок педагога, которые фиксируются в течении занятия. Если ребенка не было на занятии, то такие задания не учитывались в </td>
    </tr>
    <tr>
    <td width="30%">**Среднее по возрасту</td>
    <td width="70%">- Средняя способность детей данного возраста справляться с заданиями на занятии</td>
    </tr>
   </table></div><br><div>
   <table border="0" cellpadding="0" cellspacing="0">
   <tr height="20" style="">
        <td class="green" width="30%">&nbsp;</td>
        <td width="70%">-  ребенок справляется с заданиями отлично</td>
    </tr>
    <tr>
        <td class="yellow" width="30%">&nbsp;</td>
        <td width="70%">- Средняя способность детей данного возраста справляться с заданиями на занятии</td>
    </tr>
   </table>
    </div>
   </body>
   </html>';

    return $theader.$tbody.$tfooter;

    // Create the basic rtadar graph
    $graph = new RadarGraph(300,200);

    // Set background color and shadow
    $graph->SetColor("white");
    $graph->SetShadow();

    // Position the graph
    $graph->SetCenter(0.4,0.55);

    // Setup the axis formatting
    $graph->axis->SetFont(FF_FONT1,FS_BOLD);
    $graph->axis->SetWeight(2);

    // Setup the grid lines
    $graph->grid->SetLineStyle("longdashed");
    $graph->grid->SetColor("navy");
    $graph->grid->Show();
    $graph->HideTickMarks();

    // Setup graph titles
    $graph->title->Set("ДИАГРАММА УСВОЕНИЯ МАТЕРИАЛОВ НА ЗАНЯТИЯХ");
    $graph->title->SetFont(FF_FONT1,FS_BOLD);
    $graph->SetTitles($arLessons);
    // Create the first radar plot
    $plot = new RadarPlot($arMaxValues);
    $plot->SetLegend("Максимально возможный");
    $plot->SetColor("red","lightred");
    $plot->SetFill(false);
    $plot->SetLineWeight(1);

    // Create perosnal level area
    $plot2 = new RadarPlot($arValues);
    $plot2->SetLegend("Уровень ребенка");
    $plot2->SetColor("green","lightgreen");
    $plot2->SetLineWeight(2);

    // Create the average levels area
    $plot3 = new RadarPlot($arValues);
    $plot3->SetLegend("Средний уровень по возрасту");
    $plot3->SetColor("blue","lightblue");
    $plot->SetFill(false);
    $plot->SetLineWeight(1);

    // Add the plots to the graph
    $graph->Add($plot3);
    $graph->Add($plot2);
    $graph->Add($plot);

    // And output the graph
    $graph->Stroke();

}