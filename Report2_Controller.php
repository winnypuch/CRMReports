<?php
if ($_REQUEST['date1'])
    $date1 = date("d.m.Y", strtotime(form_eng_time($_REQUEST['date1'])));
else
    $date1 = date("d.m.Y", mktime(0, 0, 0, date("m"), date("d") - 7, date("Y")));

if ($_REQUEST['date2'])
    $date2 = date("d.m.Y", strtotime(form_eng_time($_REQUEST['date2'])));
else
    $date2 = date("d.m.Y");

$date_zero = '0000-00-00 00:00:00';
$date_now = form_eng_time(date("d.m.Y", mktime(0, 0, 0, date("m"), date("d"), date("Y"))). ' 00:00:00');

$sSqlQueryGroup = "SELECT Groups.f11090 AS GroupName
    , Groups.f11130 AS ChildrenInGroup
     , Groups.f16300 AS MaxChildrenInGroup
 FROM
    ".DATA_TABLE.get_table_id(700)." AS Groups
		INNER JOIN ".DATA_TABLE.get_table_id(790)." AS Schedule
			ON Groups.f11090 = Schedule.f13550
 WHERE
	Schedule.f13580 = 'онлайн'
    AND ((Groups.f11240 <='".$date_now."' AND Groups.f11240 <>'".$date_zero."' AND (Groups.f11270 IS NULL OR Groups.f11270 = '".$date_zero."'))
        OR (Groups.f11240 <='".$date_now."' AND Groups.f11240 <>'".$date_zero."' AND Groups.f11270 >= '".$date_now."' AND Groups.f11270 <> '".$date_now."' AND NOT Groups.f11270 IS NULL))
     AND Groups.`status` = 0
 ORDER BY
    GroupName";

$vGroupData = sql_query($sSqlQueryGroup);

$interval = date_interval_create_from_date_string('1 day');
$daterange = new DatePeriod(new DateTime($date1), date_interval_create_from_date_string('1 day'), date_add(new DateTime($date2), $interval));

$dateformate = [];
foreach ($daterange as $dater) {
    array_push($dateformate, $dater->format('d.m.y'));
}

while ($vGroupRow = sql_fetch_assoc($vGroupData)) {
    $vTableData['GroupName'] = form_display($vGroupRow['GroupName']);
    $vTableData['ChildrenInGroup'] = $vGroupRow['ChildrenInGroup'];
    $vTableData['MaxChildrenInGroup'] = $vGroupRow['MaxChildrenInGroup'];
    $vTableData['WorkingOff'] = [];

    foreach ($daterange as $ondate) {
        $iWorkingOff = 0;

        if($result2 = data_select_field(820, "COUNT(*) AS WorkingOffCount", "f14960='", $vGroupRow['GroupName'], "' AND f15070<='", $ondate->format('d.m.Y 00:00:00'), "' AND status='0'")) {
            if($row2 = sql_fetch_assoc($result2)){
                $iWorkingOff = $row2['WorkingOffCount'];
            }
        }
        array_push($vTableData['WorkingOff'], $iWorkingOff);
    }

    $vLines[] = $vTableData;
}

$result = sql_select_field("" . SCHEMES_TABLE . "", "color3", "active='1'");
$row = sql_fetch_assoc($result);
$smarty->assign("color3", $row['color3']);
$smarty->assign("date1", $date1);
$smarty->assign("date2", $date2);
$smarty->assign("lines", $vLines);
$smarty->assign("dateformate", $dateformate);