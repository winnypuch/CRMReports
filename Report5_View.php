{literal}

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
            <td>&nbsp;&nbsp;
                <input type="submit" value="Обновить" class="no_print btn btn-default btn-sm"
                       onclick="document.getElementById('report_form').submit(); return false"/>
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
                        <td class="sticky-col first-col" width="50%" style="width:300px !important;border-top-color: #fff; border-left: none"
                            rowspan="2">Педагог</td>
                        <td style="width:75px;border-top-color: #fff; border-left: none"
                            rowspan="2">Кол-во занятий</td>
                        <td style="border-right: none;  border-top-color: #fff"
                            colspan="5">Очно</td>
                        <td style="border-right: none;  border-top-color: #fff"
                            colspan="8">Онлайн</td>
                        <td style="border-right: none;  border-top-color: #fff"
                            rowspan="2">Личные расходы</td>
                        <td style="border-right: none;  border-top-color: #fff"
                            rowspan="2">Подработка</td>
                        <td style="border-right: none;  border-top-color: #fff"
                            rowspan="2">ЗП ИТОГО план</td>
                        <td style="border-right: none;  border-top-color: #fff"
                            rowspan="2">ЗП ИТОГО факт</td>
                        <td rowspan="2"/>
                    </tr>
                    <tr style="height: 120px; background-color: #2196F3">
                        <td style="width:83px;border-right: none;  border-top-color: #fff">Кол-во занятий &lt;=2 чел.</td>
                        <td style="width:75px;border-right: none;  border-top-color: #fff">Кол-во занятий &gt;2 чел.</td>
                        <td style="width:89px;border-right: none;  border-top-color: #fff">Кол-во человек на занятиях, где &gt;2 чел.</td>
                        <td style="width:61px;border-right: none;  border-top-color: #fff">Индивид</td>
                        <td style="width:40px;border-right: none;  border-top-color: #fff">ЗП</td>
                        <td style="width:94px;border-right: none;  border-top-color: #fff">Кол-во занятий &lt;=1.5 чел. Сад</td>
                        <td style="width:86px;border-right: none;  border-top-color: #fff">Кол-во занятий &gt;1.5 Сад</td>
                        <td style="width:89px;border-right: none;  border-top-color: #fff">Кол-во человек на занятиях, где &gt;1.5 Сад</td>
                        <td style="width:94px;border-right: none;  border-top-color: #fff">Кол-во занятий &lt;=2.5 чел. Школа</td>
                        <td style="width:86px;border-right: none;  border-top-color: #fff">Кол-во занятий &gt;2.5 чел. Школа</td>
                        <td style="width:89px;border-right: none;  border-top-color: #fff">Кол-во человек на занятиях, где &gt;2.5 чел. Школа</td>
                        <td style="width:61px;border-right: none;  border-top-color: #fff">Индивид</td>
                        <td style="width:40px;border-right: none;  border-top-color: #fff">ЗП</td>
                        <td style="width:59px;border-right: none;  border-top-color: #fff"/>
                    </tr>
                    {foreach from=$lines item=data name="rows"}
                        <tr style="height: 20px">
                            <td class="sticky-col first-col">{$data.A}</td>
                            <td>{$data.B}</td>
                            <td>{$data.D}</td>
                            <td>{$data.E}</td>
                            <td>{$data.F}</td>
                            <td>{$data.H}</td>
                            <td>{$data.I}</td>
                            <td>{$data.J}</td>
                            <td>{$data.K}</td>
                            <td>{$data.L}</td>
                            <td>{$data.M}</td>
                            <td>{$data.N}</td>
                            <td>{$data.O}</td>
                            <td>{$data.Q}</td>
                            <td>{$data.R}</td>
                            <td>{$data.S}</td>
                            <td>{$data.T}</td>
                            <td>{$data.Y}</td>
                            <td>{$data.Z}</td>
                            <td>
                                <a target="_blank" href="">выплатить</a>
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
</div>