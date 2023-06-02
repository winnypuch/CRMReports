{literal}

    <script type="text/javascript">

            var $start = $('#dDateBeg'),
                $end = $('#dDateEnd');

            $start.datepicker({
                onSelect: function (fd, date) {
                    $end.data('datepicker')
                        .update('minDate', date);

                    $end.focus();
                }
            })
            $end.datepicker({
                onSelect: function (fd, date) {
                    $start.data('datepicker')
                        .update('maxDate', date)
                }
            })
            //$('.datepicker').datepicker({
            //    showOn: "button",
            //    showAlways: true,
            //    buttonImage: "images/calbtn.png",
            //    buttonImageOnly: true,
            //    buttonText: "Calendar",
            //    showAnim: (('\v' == 'v') ? "" : "show")  // в ie не включаем анимацию, тормозит
            //})

        function highlightTr(tdName, trId, hlMode) {
            if (hlMode) hlColor = "{/literal}{$color3}{literal}";
            else hlColor = "#ffffff";
            document.getElementById(tdName + trId).style.background = hlColor;
        }
        function Report(iChildrenId) {
            sConfirm = "Выплатить?";
            //if (iSumPlan == iSumFact) {
            //    sConfirm = "Выплаченная сумма " + iSumFact + " равна запланированной, всё равно выплатить?";
            //} else {
            //    if (iSumPlan < iSumFact)
            //    sConfirm = "Выплаченная сумма " + iSumFact + " больше запланированной " + iSumPlan + ", всё равно выплатить?";
            //}

            href_post = confirm(sConfirm);
            if (href_post) {
                document.getElementById('update_data').value = '1';
                document.getElementById('update_sum').value = iSumPlan;
                document.getElementById('update_teacherid').value = iTeacherId;
                document.getElementById('report_form').submit();
                document.getElementById('update_data').value = '0';
            }
            return href_post;
        }
        function SendReport(iChildrenId) {
            sConfirm = "Выплатить?";
            //if (iSumPlan == iSumFact) {
            //    sConfirm = "Выплаченная сумма " + iSumFact + " равна запланированной, всё равно выплатить?";
            //} else {
            //    if (iSumPlan < iSumFact)
            //    sConfirm = "Выплаченная сумма " + iSumFact + " больше запланированной " + iSumPlan + ", всё равно выплатить?";
            //}

            href_post = confirm(sConfirm);
            if (href_post) {
                document.getElementById('update_data').value = '1';
                document.getElementById('update_sum').value = iSumPlan;
                document.getElementById('update_teacherid').value = iTeacherId;
                document.getElementById('report_form').submit();
                document.getElementById('update_data').value = '0';
            }
            return href_post;
        }
        function SubmitData() {
            document.getElementById('update_data').value = '0';
            document.getElementById('report_form').submit();
            return false;
        }

    </script>

    <style type="text/css">
        .title {
            margin: 20px 0px;
            font-size: 23px;
            font-weight: bold;
        }

        .top {
            border-top: 1px solid #E3E3E3;
            border-bottom: 1px solid #E3E3E3;
            background-color: #F5F5F5;
            text-align: center;
            padding: 15px 52px;
        }

        .top table {
            margin: 0px;
            padding: 0px;
            border: none;
            border-collapse: collapse;
        }

        .top td {
            border: none;
            padding: 0px;
            margin: 0px;
        }

        span.input_element {
            white-space: nowrap;
            padding: 0px 35px 0px 0px;
            line-height: 32px;
            float: left;
        }

        .mainTable {
            overflow: auto;
            position: relative;
        }
        .mainTable table {
          position: relative;
          overflow: auto;
         }

        .mainTable td {
            padding: 5px 5px;
            vertical-align: middle;
            border-top: 1px solid #B3BFC9;
            border-bottom: 1px solid #B3BFC9;
            border-left: 1px dashed #B3BFC9;
            border-right: 1px dashed #B3BFC9;
            text-align: center;
        }
        .sticky-col {
          position: -webkit-sticky;
          position: sticky;
        }
        .first-col {
          width: 300px;
          min-width: 300px;
          max-width: 300px;
        }
    </style>
{/literal}

<div class="title">
<span style="float: right; font-size: 12px; margin-top: 5px;" class="no_print">
      <a class="header__user-item header__user-item_img" href="javascript:window.print()"><img src="images/print.gif" title="Печать отчёта"></a>
      <a class="header__user-item header__user-item_img header__user-item--settings" href="edit_report.php?report=400" title="Настройки"></a>
</span>
    ЗП Педагоги
</div>
<div class="top input_element">
    <table style="margin: 0px auto;">
        <tr>
            <td><label for="year">Год:</label>
                {$yearArray = range(2022, 2030)}
                <select name="year" style="width:50px; padding: 5px 5px 5px 5px">
                    {foreach $yearArray as $selyear}
                        {if $selyear== $year}
                            <option selected value="{$selyear}">{$selyear}</option>
                        {else}
                            <option value="{$selyear}">{$selyear}</option>
                        {/if}
                    {/foreach}
                </select>
                <div style="clear: both"></div>
            </td>
            <td><label for="dDateBeg">Дата от:</label><input type="text" name="dDateBeg" id="dDateBeg" value="{$dDateBeg}" size="10" class="datepicker form-control form-control-160"/><div style="clear: both"></div></td>
            <td><label for="dDateEnd">Дата до:</label><input type="text" name="dDateEnd" id="dDateEnd" value="{$dDateEnd}" size="10" class="datepicker form-control form-control-160"/><div style="clear: both"></div></td>
            <td>&nbsp;&nbsp;
                <input type="submit" value="Обновить" class="no_print btn btn-default btn-sm"
                       onclick="return SubmitData();"/>
            </td>
            <td>&nbsp;&nbsp;Группа&nbsp;&nbsp;
                <select name="iGroupId" style="width:300px; padding: 5px 5px 5px 5px" onchange="return  SubmitData();">
                    {foreach from=$vGroups item=data name="rows"}
                        {if $data.GroupId == $iGroupId}
                            <option selected value="{$data.GroupId}">{$data.GroupName}</option>
                        {else}
                            <option value="{$data.GroupId}">{$data.GroupName}</option>
                        {/if}
                    {/foreach}
                </select>
                <div style="clear: both"></div>
            </td>
        </tr>
        {if $bisAdmin}
        <tr>
            <td>*Погрешность:</td>
            <td><input type="text" name="FaultData" id="FaultData" value="{$iFaultData}"/></td>
        </tr>
        <tr>
            <td>*Минимальное кол-во занятий:</td>
            <td><input type="text" name="iMinQtyClasses" id="iMinQtyClasses" value="{$iMinQtyClasses}"/></td>
        </tr>
        <tr>
            <td>*Минимальное кол-во занятий по подразделу:</td>
            <td><input type="text" name="iMinQtyClassesSubdivision" id="iMinQtyClassesSubdivision" value="{$iMinQtyClassesSubdivision}"/></td>
        </tr>
        {/if}
        <tr>
            <td><label for="bPerfomance">Добавить успеваемость в отчет:  </label></td>
            <td><input type="checkbox" name="bPerfomance" id="bPerfomance" value="0"/></td>
        </tr>

    </table>
</div>


<div class="mainTable">
    <table
        cellspacing="0"
        cellpadding="0">
        <tbody>
            <tr class="" style="height: 20px; background-color: #2196F3">
                <td class="sticky-col first-col">ФИО ребенка</td>
                <td style="width:75px;border-top-color: #fff; border-left: none">Кол-во занятий</td>
                <td style="border-right: none;  border-top-color: #fff">Отчет</td>
                <td style="border-right: none;  border-top-color: #fff">Отправить отчёт</td>
            </tr>
            {foreach from=$lines item=data name="rows"}
                <tr style="height: 20px">
                    <td class="sticky-col first-col">{$data.ChildrenFIO}</td>
                    <td>{$data.NumberLesson}</td>
                    <td><a onclick="return Report({$data.ChildrenId});" href="#">Отчёт</a></td>
                    <td><a onclick="return SendReport({$data.ChildrenId});" href="#">Отправить отчёт</a></td>
                </tr>
            {/foreach}
        </tbody>
    </table>
</div>
<input type=hidden name="Report" id="Report" value="0"/>
<input type=hidden name="SendReport" id="SendReport" value="0"/>
<input type=hidden name=csrf value='{$csrf}'/>