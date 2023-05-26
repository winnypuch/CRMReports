<?php
$months = array(1 => 'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь');
if ($_REQUEST['year'] && $_REQUEST['month']) {
    $month = $_REQUEST['month'];
    $year = $_REQUEST['year'];
} else {
    $month = date("m");
    $year = date("Y");
}

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
$sTeachersSqlQuery = "SELECT
                    Users.Id AS UserId
                    , Users.fio AS UserFIO
                    , Teachers.Id AS TeacherId
                    , Teachers.f9660 AS TeacherFIO
                FROM
                " . USERS_TABLE . " AS Users
                    INNER JOIN " . DATA_TABLE . get_table_id(520) . " AS Teachers
                        ON Users.id = Teachers.f9630
                WHERE
                    group_id = 790
                    AND arc =0";
//Преподователи
if ($vTeachersRes = sql_query($sTeachersSqlQuery)) {
    if ($vTeachersRow = sql_fetch_assoc($vTeachersRes)) {
        //780 - таблица  занятия
        //f12790 ФИО педагога сокр. 1 f12790] => 30
        //f12850 ФИО педагога сокр. 2
        // f13470 Оплата занятия
        // f12750 Дата
        // f17500 Дети к оплате
        // f12830 Формат план
        // f13450 Тип Группы
        $sClassesSqlQuery = "SELECT
            Teacher.id as id
           ,Teacher.f9660 AS fiosearch
         FROM
            " . DATA_TABLE . get_table_id(780) . " AS Classes
         WHERE
            (Classes.f12790= '" . $vTeachersRow['TeacherId'] . "'
            OR Classes.f12850= '" . $vTeachersRow['TeacherId'] . "')
            AND Classes.f13470='Оплата'
             AND Classes.status = 0";
        //смотрим занятия
        if($vClassesData = sql_query($sClassesSqlQuery)){
            while ($vClassesRow = sql_fetch_assoc($vClassesData)) {
                //$sFioTeacher = $vTeacherRow['fiosearch'];
                $iTeacherId = $vClassesRow['id'];
            }
        }
    }
}


$date_zero = '0000-00-00 00:00:00';
$date_now = form_eng_time(date("d.m.Y", mktime(0, 0, 0, date("m"), date("d"), date("Y"))). ' 00:00:00');

$sqlQuery = "SELECT ClienCards.f9750 as fio
     , GradesClassData.was AS was
     , GradesClassData.wasSum AS wasSum
     , GradesClassData.omissions AS omissions
     , IFNULL(WorkingOutData.WorkingOut, 0) AS WorkingOut
     , IFNULL(WorkingOutData.WorkingOutSum, 0) AS WorkingOutSum
     , IFNULL(SUM(Parents.f10810), 0) AS ParentSum
 FROM
    ".DATA_TABLE.get_table_id(720)." AS Students
        INNER JOIN ".DATA_TABLE.get_table_id(530)." AS ClienCards
            ON Students.f11460 = ClienCards.id
        INNER JOIN ".DATA_TABLE.get_table_id(680)." AS WhereToPay
            ON Students.f15460 = WhereToPay.id
        INNER JOIN (
                    SELECT
                        GradesClass.f14680 AS std_id
                        , SUM(if(GradesClass.f14700 = '' OR GradesClass.f14700 IS NULL, 1, 0)) AS was
                        , SUM(if(GradesClass.f14700 = 'н', 1, 0)) AS omissions
                        , SUM(if(GradesClass.f14700 = '' OR GradesClass.f14700 IS NULL, GradesClass.f14780, 0)) AS wasSum
                    FROM
                        ".DATA_TABLE.get_table_id(810)." AS GradesClass
                    WHERE
                        GradesClass.f16290 = 'На счет Сергею'
                    GROUP BY
                        GradesClass.f14680) AS GradesClassData
            ON Students.f11460 = GradesClassData.std_id
        LEFT JOIN (
                    SELECT
                        GradesClass.f14680 AS std_id
                        , IFNULL(SUM(if((WorkingOut.f16280 IS NULL OR WorkingOut.f16280 <> 'Неоплата'), GradesClass.f14780, 0)), 0) AS WorkingOutSum
                        , IFNULL(SUM(if((WorkingOut.f16280 IS NULL OR WorkingOut.f16280 <> 'Неоплата'), 1, 0)), 0) AS WorkingOut
                    FROM
                        ".DATA_TABLE.get_table_id(810)." AS GradesClass
                            LEFT JOIN ".DATA_TABLE.get_table_id(820)." AS WorkingOut
                                ON GradesClass.f14680 = WorkingOut.f14890 AND GradesClass.f15990 = WorkingOut.f15980
                    WHERE
                        GradesClass.f14700 = 'н'
                        AND GradesClass.f16290 = 'На счет Сергею'
                    GROUP BY
                        GradesClass.f14680) AS WorkingOutData
            ON Students.f11460 = WorkingOutData.std_id

        LEFT JOIN ".DATA_TABLE.get_table_id(650)." AS Parents
            ON Students.f11460 = Parents.f10820
     WHERE
         WhereToPay.f10990 = 'На счет Сергею'
         AND ((Students.f11580 <='".$date_now."' AND Students.f11590 IS NULL)
            OR (Students.f11580 <='".$date_now."' AND Students.f11590 = '".$date_zero."')
            OR (Students.f11580 <='".$date_now."' AND Students.f11590 >= '".$date_now."'))
         AND Students.`status` = 0
     GROUP BY
         Students.f11460
         , ClienCards.f9750
     ORDER BY fio";

$result = sql_query($sqlQuery);

while ($row = sql_fetch_assoc($result)) {
    $data['fio'] = form_display($row['fio']);
    $data['was'] = $row['was'];
    $data['WorkingOut'] = $row['WorkingOut'];
    $data['omissions'] = $row['omissions'];
    $data['Column3'] = $row['WorkingOut']." из ".$row['omissions'];
    $data['Column4'] = $row['was'] + $row['WorkingOut'];
    $data['Column5'] = form_local_number($row['wasSum'] + $row['WorkingOutSum'], '2/10');
    $data['wasSum'] = form_local_number($row['wasSum'], '2/10');
    $data['WorkingOutSum'] = form_local_number($row['WorkingOutSum'], '2/10');
    $data['Column6'] = form_local_number($row['ParentSum'], '2/10');
    $data['Column7'] = form_local_number($row['ParentSum'] - $row['wasSum'] - $row['WorkingOutSum'], '2/10');
    $lines[] = $data;
}

$result = sql_select_field("" . SCHEMES_TABLE . "", "color3", "active='1'");
$row = sql_fetch_assoc($result);

$smarty->assign("color3", $row['color3']);
$smarty->assign("months", $months);
$smarty->assign("month", $month);
$smarty->assign("year", $year);
$smarty->assign("lines", $vLines);