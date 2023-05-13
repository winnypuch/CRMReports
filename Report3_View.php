{literal}
    <script type="text/javascript" src="include/jqplot/jquery.jqplot.min.js"></script>
    <script type="text/javascript" src="include/jqplot/plugins/jqplot.categoryAxisRenderer.min.js"></script>
    <script type="text/javascript" src="include/jqplot/plugins/jqplot.pointLabels.min.js"></script>
    <script type="text/javascript" src="include/jqplot/plugins/jqplot.dateAxisRenderer.min.js"></script>
    <script type="text/javascript" src="include/jqplot/plugins/jqplot.cursor.min.js"></script>
    <script type="text/javascript" src="include/jqplot/plugins/jqplot.enhancedLegendRenderer.js"></script>
    <link rel="stylesheet" type="text/css" href="include/jqplot/jquery.jqplot.css"/>
    <script type="text/javascript">
        function highlightTr(tdName, trId, hlMode) {
            if (hlMode) hlColor = "{/literal}{$color3}{literal}";
            else hlColor = "#ffffff";
            document.getElementById(tdName + trId).style.background = hlColor;
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

        .main {
            margin-top: 35px;
            margin-bottom: 25px;
        }

        .mainTable {
            border-collapse: collapse;
            border: none;
            text-align: center;
            width: 100%;
        }

        .mainTable td {
            padding: 10px 20px;
            vertical-align: middle;
            border-top: 1px solid #B3BFC9;
            border-bottom: 1px solid #B3BFC9;
            border-left: 1px dashed #B3BFC9;
            border-right: 1px dashed #B3BFC9;
            text-align: center;
        }
        .fields__cell_ {
    padding: 5px 5px 5px 5px;
    display: table-cell;
    vertical-align: middle;
    text-align: center;
    }
    </style>

{/literal}

<div class="title">
    <span style="float: right; font-size: 12px; margin-top: 5px;" class="no_print">

      <a class="header__user-item header__user-item_img" href="javascript:window.print()"><img src="images/print.gif" title="Печать отчёта"></a>
      <a class="header__user-item header__user-item_img header__user-item--settings" href="edit_report.php?report=380" title="Настройки"></a>
    </span>
        Табели
</div>
<div class="top">
    <table style="margin: 0px auto;">
        <tr>
            <td>Год&nbsp;&nbsp;
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
            <td>&nbsp;&nbsp;Месяц&nbsp;&nbsp;
                <select name="month" style="width:100px; padding: 5px 5px 5px 5px">
                    {section name=nummonth loop=12}
                        {if $smarty.section.nummonth.iteration== $month}
                            <option selected value="{$smarty.section.nummonth.iteration}">{$months[$smarty.section.nummonth.iteration]}</option>
                        {else}
                            <option value="{$smarty.section.nummonth.iteration}">{$months[$smarty.section.nummonth.iteration]}</option>
                        {/if}
                    {/section}
                </select>
                <div style="clear: both"></div>
            </td>
            <td>&nbsp;&nbsp;Группа&nbsp;&nbsp;
                <select name="iGroupId" style="width:300px; padding: 5px 5px 5px 5px">
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
            <td>&nbsp;&nbsp;
                <input type="submit" value="Обновить" class="no_print btn btn-default btn-sm"
                       onclick="document.getElementById('report_form').submit(); return false"/>
            </td>
        </tr>
    </table>
</div>
</div>
<main class="fields__body">
    <div class="fields">
        <div id="inner_block" class="fields__wrap" style="overflow: auto hidden;">
            <div class="fields__table fields__table--stable" id="fields_content_table" tabindex="-1" style="opacity: 1; overflow: hidden; min-width: -webkit-fill-available; height: 25px !important;">
                <div class="fields__row fields__row--header" id="f_row" style="min-width: -webkit-fill-available; display: table-row; z-index: 0; height: 25px !important;">
                    <div class="fields__cell_ fields__cell--header"  style="width: 264px; max-width: 264px; min-width: 264px; height: 25px !important;">
                        <b>ФИО</b>
                    </div>
                    {foreach $dateformate as $ondate}
                    <div class="fields__cell_ fields__cell--header" style="width: 50px; max-width: 100px; min-width: 50px; height: 25px !important;">
                        <b>{$ondate}</b>
                    </div>
                    {/foreach}
                    <div class="fields__cell_ fields__cell--header" style="width: 50px; max-width: 100px; min-width: 50px; height: 25px !important;">
                        <b>Пропущено</b>
                    </div>
                    <div class="fields__cell_ fields__cell--header" style="width: 130px; max-width: 130px; min-width: 50px; height: 25px !important;">
                        <b>Пропущено к оплате</b>
                    </div>
                    <div class="fields__cell_ fields__cell--header" style="width: 130px; max-width: 130px; min-width: 50px; height: 25px !important;">
                        <b>Всего к оплате</b>
                    </div>
                </div>
                {foreach from=$lines item=data name="rows"}
                    <div class="fields__row fields__row--info fields__row--simple" id="submain_td{$smarty.foreach.rows.iteration}" style="background-color: rgb(255, 255, 255); width: 2692.59px; height: 25px !important;"
                        onmouseover="highlightTr('submain_td',{$smarty.foreach.rows.iteration}, 1)"
                        onmouseout="highlightTr('submain_td',{$smarty.foreach.rows.iteration}, 0)">
                        <div class="fields__cell_ number__max_width-table" style="width: 200px; cursor: pointer; white-space: nowrap; background-color: rgb(255, 255, 255); min-width: 264px; height: 25px !important;">
                            <div style="width:200px; height: 25px !important;">
                                <div class="fields__simple-text fields__value">
                                <a  href="view_line2.php?table=530&amp;line={$data.StudentId}&amp;back_url=" target="_blank">
                                {$data.StudentFIO}
                                </a>
                                </div>
                            </div>
                        </div>
                        {foreach $dateformate as $ondate}
                            <div class="fields__cell_" style="text-align: center !important; white-space: nowrap; cursor: pointer; width: 50px; min-width: 50px;  height: 25px !important;background-color: {$data.DayColor[$ondate]};">
                                <div style="min-width:100%; height: 25px !important;">
                                    <div class="fields__value" style="text-align: center !important;overflow: hidden; padding-right: 0px;background-color: {$data.DayColor[$ondate]};">{$data.DayData[$ondate]}</div>
                                </div>
                            </div>
                        {/foreach}
                        <div class="fields__cell_" style="text-align: center; white-space: nowrap; cursor: pointer; width: 50px; min-width: 50px;  height: 25px !important;">
                            <div style="min-width:100%; height: 25px !important;">
                                <div class="fields__value" style="text-align: center;overflow: hidden; padding-right: 0px;">{$data.Skipped}</div>
                            </div>
                        </div>
                        <div class="fields__cell_" style="text-align: center; white-space: nowrap; cursor: pointer; width: 50px; min-width: 50px;  height: 25px !important;">
                            <div style="min-width:100%; height: 25px !important;">
                                <div class="fields__value" style="text-align: center !important;overflow: hidden; padding-right: 0px;">{$data.SkippedPay}</div>
                            </div>
                        </div>
                        <div class="fields__cell_" style="text-align: center; white-space: nowrap; cursor: pointer; width: 50px; min-width: 50px;  height: 25px !important;">
                            <div style="min-width:100%; height: 25px !important;">
                                <div class="fields__value" style="text-align: center;overflow: hidden; padding-right: 0px;">{$data.TotalPay}</div>
                            </div>
                        </div>
                    </div>
                {/foreach}
            </div>
        </div>
    </div>
</main>
<input type=hidden name="_date1" value="{$date1}">
<input type=hidden name="_date2" value="{$date2}">
