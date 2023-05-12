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
    </style>
{/literal}

<div class="title">
<span style="float: right; font-size: 12px; margin-top: 5px;" class="no_print">
  <a href="javascript:window.print()"><img src="images/print.gif" style="vertical-align: bottom; border: none; margin-right: 3px;">Печать отчёта</a>
</span>
    Балансы
</div>



<div class="main">
    <table class="mainTable" cellspacing="0" cellpadding="0">
        <tr class="fields__row fields__row--header" style="background-color: #2196F3">
            <td style="text-align:left; width: 20%; border-right: 1px solid #B3BFC9; border-left: 0px; border-top: 0px;"><b>Фио ребенка</b></td>
            <td style="width: 10%; border-top-color: #fff; border-left: none"><b>Был</b></td>
            <td style="width: 10%; border-top-color: #fff"><b>Оплата (пропуски)</b></td>
            <td style="width: 10%; border-right: none;  border-top-color: #fff"><b>Занятия к оплате</b></td>
            <td style="width: 15%; border-right: none;  border-top-color: #fff"><b>Сумма к оплате</b></td>
            <td style="width: 20%; border-right: none;  border-top-color: #fff"><b>Поступление</b></td>
            <td style="width: 15%; border-right: none;  border-top-color: #fff"><b>Баланс</b></td>
        </tr>
        {foreach from=$lines item=data name="rows"}
            <tr id="submain_td{$smarty.foreach.rows.iteration}"
                onmouseover="highlightTr('submain_td',{$smarty.foreach.rows.iteration}, 1)"
                onmouseout="highlightTr('submain_td',{$smarty.foreach.rows.iteration}, 0)">
                <td style="text-align:left; border-left: 0px; border-right: 1px solid #B3BFC9;">{$data.fio}</td>
                <td>{$data.was}</td>
                <td>{$data.Column3}</td>
                <td>{$data.Column4}</td>
                <td style="text-align:right;">{$data.Column5}</td>
                <td style="text-align:right;">{$data.Column6}</td>
                {if $data.Column7 < 0 }
                    <td style="text-align:right; border-right: none;color:red;"><b>{$data.Column7}</b></td>
                {else}
                <td style="text-align:right; border-right: none;"><b>{$data.Column7}</b></td>
                {/if}
            </tr>
        {/foreach}
    </table>
</div>
<div class="title">
    Должники
</div>


<div class="main">
    <table class="mainTable" cellspacing="0" cellpadding="0">
        <tr class="fields__row fields__row--header" style="background-color: #2196F3">
            <td style="text-align:left; width: 20%; border-right: 1px solid #B3BFC9; border-left: 0px; border-top: 0px;"><b>Фио ребенка</b></td>
            <td style="width: 10%; border-top-color: #fff; border-left: none"><b>Был</b></td>
            <td style="width: 10%; border-top-color: #fff"><b>Оплата (пропуски)</b></td>
            <td style="width: 10%; border-right: none;  border-top-color: #fff"><b>Занятия к оплате</b></td>
            <td style="width: 15%; border-right: none;  border-top-color: #fff"><b>Сумма к оплате</b></td>
            <td style="width: 20%; border-right: none;  border-top-color: #fff"><b>Поступление</b></td>
            <td style="width: 15%; border-right: none;  border-top-color: #fff"><b>Баланс</b></td>
        </tr>
        {foreach from=$lines2 item=data name="rows"}
            <tr id="submain_td_2{$smarty.foreach.rows.iteration}"
                onmouseover="highlightTr('submain_td_2',{$smarty.foreach.rows.iteration}, 1)"
                onmouseout="highlightTr('submain_td_2',{$smarty.foreach.rows.iteration}, 0)">
                <td style="text-align:left; border-left: 0px; border-right: 1px solid #B3BFC9;">{$data.fio}</td>
                <td>{$data.was}</td>
                <td>{$data.Column3}</td>
                <td>{$data.Column4}</td>
                <td style="text-align:right;">{$data.Column5}</td>
                <td style="text-align:right;">{$data.Column6}</td>
                {if $data.Column7 < 0 }
                    <td style="text-align:right; border-right: none;color:red;"><b>{$data.Column7}</b></td>
                {else}
                <td style="text-align:right; border-right: none;"><b>{$data.Column7}</b></td>
                {/if}
            </tr>
        {/foreach}
    </table>
</div>