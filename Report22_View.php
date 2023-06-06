{literal}

    <script type="text/javascript">
        $(function () {
            var $start = $('#sDateBeg'),
                $end = $('#sDateEnd');

            $start.datepicker({
                maxDate: '+ 3 month',
                onSelect: function (selectedDate) {
                    $end.datepicker('option', 'minDate', selectedDate);
                }
            });
            $end.datepicker({
                maxDate: '+ 3 month',
                onSelect: function (selectedDate) {
                    $start.datepicker('option', 'maxDate', selectedDate);
                }
            });
        });

        function highlightTr(tdName, trId, hlMode) {
            if (hlMode) hlColor = "{/literal}{$color3}{literal}";
            else hlColor = "#ffffff";
            document.getElementById(tdName + trId).style.background = hlColor;
        }
        function Report(iChildrenId, iReportState) {
            sConfirm = "Открыть отчёт?";

            href_post = confirm(sConfirm);
            if (href_post) {
                document.getElementById('SendReport').value = '0';
                document.getElementById('Report').value = '1';
                document.getElementById('ChildrenId').value = iChildrenId;
                document.getElementById('report_form').submit();
                document.getElementById('Report').value = '0';
            }
            return href_post;
        }
        function SendReport(iChildrenId, iReportState) {
            sConfirm = "Отправить отчёт?";

            href_post = confirm(sConfirm);
            if (href_post) {
                document.getElementById('Report').value = '0';
                document.getElementById('SendReport').value = '1';
                document.getElementById('ChildrenId').value = iChildrenId;
                document.getElementById('report_form').submit();
                document.getElementById('SendReport').value = '0';
            }
            return href_post;
        }
        function SendAllReport() {
            sConfirm = "Отправить всем?";

            href_post = confirm(sConfirm);
            if (href_post) {
                document.getElementById('Report').value = '0';
                document.getElementById('SendReport').value = '0';
                document.getElementById('ChildrenId').value = '0';
                document.getElementById('SendAllReport').value = '1';
                document.getElementById('report_form').submit();
                document.getElementById('SendAllReport').value = '0';
            }
            return href_post;
        }
        function SubmitData() {
            document.getElementById('Report').value = '0';
            document.getElementById('SendReport').value = '0';
            document.getElementById('ChildrenId').value = '0'
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
      <a class="header__user-item header__user-item_img header__user-item--settings" href="edit_report.php?report=410" title="Настройки"></a>
</span>
    Отчёты
</div>
<div class="top input_element">
    <table style="margin: 0px auto;">
        <tr>
            <!--<td><label for="year">Год:</label>
                {$yearArray = range(2022, 2030)}
                <select name="iYear" style="width:50px; padding: 5px 5px 5px 5px">
                    {foreach $yearArray as $selyear}
                        {if $selyear== $iYear}
                            <option selected value="{$selyear}">{$selyear}</option>
                        {else}
                            <option value="{$selyear}">{$selyear}</option>
                        {/if}
                    {/foreach}
                </select>
                <div style="clear: both"></div>
            </td>-->
            <td>&nbsp;&nbsp;Дата от:&nbsp;&nbsp;<input type="text" name="sDateBeg" id="sDateBeg" value="{$sDateBeg}" size="10" class="datepicker form-control form-control-160"/><div style="clear: both"></div></td>
            <td>&nbsp;&nbsp;Дата до:&nbsp;&nbsp;<input type="text" name="sDateEnd" id="sDateEnd" value="{$sDateEnd}" size="10" class="datepicker form-control form-control-160"/><div style="clear: both"></div></td>
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
    </table>
</div>
<div class="top input_element">
    <table style="margin: 0px auto;">
        {if $bIsAdmin}
        <tr>
            <td>*Погрешность:&nbsp;&nbsp;</td>
            <td><input type="number" name="iFaultData" id="iFaultData" value="{$iFaultData}"/></td>
        </tr>
        <tr>
            <td>*Минимальное кол-во занятий:&nbsp;&nbsp;</td>
            <td><input type="number" name="iMinQtyClasses" id="iMinQtyClasses" value="{$iMinQtyClasses}"/></td>
        </tr>
        <tr>
            <td>*Минимальное кол-во занятий по подразделу:&nbsp;&nbsp;</td>
            <td><input type="number" name="iMinQtyClassesSubdivision" id="iMinQtyClassesSubdivision" value="{$iMinQtyClassesSubdivision}"/></td>
        </tr>
        {/if}
        <tr>
            <td>Добавить успеваемость в отчет:&nbsp;&nbsp;</td>
            <td>
                {if $bIsPerfomance}
                <input type="checkbox" name="bIsPerfomance" id="bIsPerfomance" checked value="1"/>
                {else}
                <input type="checkbox" name="bIsPerfomance" id="bIsPerfomance"  value="0"/>
                {/if}
           </td>
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
            {$bIsReportAll = false}
            {$sAllReport= "0"}
            {foreach from=$lines item=data name="rows"}
                <tr style="height: 20px">
                    <td class="sticky-col first-col">{$data.ChildrenFIO}</td>
                    <td>{$data.QtyClasses}</td>
                    {if $data.QtyClasses >= $iMinQtyClasses }
                        <td><a onclick="return Report({$data.ChildrenId}, {$data.ReportState});" href="#">Отчёт</a></td>
                        <td><a onclick="return SendReport({$data.ChildrenId}, {$data.ReportState});" href="#">Отправить</a></td>
                        {$bIsReportAll = true}
                        {$sAllReport= $sAllReport.";".$data.ChildrenId}
                    {else}
                        <td></td>
                        <td></td>
                    {/if}
                </tr>
            {/foreach}
            {if $bIsReportAll }
            <tr style="height: 20px">
                <td colspan="3"></td>
                <td><a onclick="return SendAllReport();" href="#">Отправить всем</a></td>
            </tr>
            {/if}
        </tbody>
    </table>
</div>
<input type=hidden name="Report" id="Report" value="0"/>
<input type=hidden name="ChildrenId" id="ChildrenId" value="0"/>
<input type=hidden name="SendReport" id="SendReport" value="0"/>
<input type=hidden name="SendAllReport" id="SendAllReport" value="0"/>
{if $bIsReportAll }
<input type=hidden name="SendAllReportData" id="SendAllReportData" value="{$sAllReport}"/>
{/if}
<input type=hidden name=csrf value='{$csrf}'/>