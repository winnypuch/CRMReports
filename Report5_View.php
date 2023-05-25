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



<div class="mainTable">
                <table
                   cellspacing="0"
                   cellpadding="0">

                <tbody>

                    <tr class="" style="height: 20px; background-color: #2196F3">
                        <td class="sticky-col first-col" width="50%" style="width:300px !important;border-top-color: #fff; border-left: none"
                            dir="ltr"
                            rowspan="2">Педагог</td>
                        <td class="s2" style="width:75px;border-top-color: #fff; border-left: none"
                            rowspan="2">Кол-во занятий</td>
                        <td class="s3" style="border-right: none;  border-top-color: #fff"
                            colspan="5">Очно</td>
                        <td class="s4" style="border-right: none;  border-top-color: #fff"
                            colspan="8">Онлайн</td>
                        <td class="s5" style="border-right: none;  border-top-color: #fff"
                            rowspan="2">Личные расходы</td>
                        <td class="s5" style="border-right: none;  border-top-color: #fff"
                            rowspan="2">Подработка</td>
                        <td class="s6" style="border-right: none;  border-top-color: #fff"
                            rowspan="2">ЗП ИТОГО план</td>
                        <td class="s7"/>
                    </tr>
                    <tr sclass="" style="height: 120px; background-color: #2196F3">
                        <td class="s8" style="width:83px;border-right: none;  border-top-color: #fff">Кол-во занятий &lt;=2 чел.</td>
                        <td class="s8" style="width:75px;border-right: none;  border-top-color: #fff">Кол-во занятий &gt;2 чел.</td>
                        <td class="s8" style="width:89px;border-right: none;  border-top-color: #fff">Кол-во человек на занятиях, где &gt;2 чел.</td>
                        <td class="s8" style="width:61px;border-right: none;  border-top-color: #fff">Индивид</td>
                        <td class="s8" style="width:40px;border-right: none;  border-top-color: #fff">ЗП</td>
                        <td class="s9" style="width:94px;border-right: none;  border-top-color: #fff">Кол-во занятий &lt;=1.5 чел. Сад</td>
                        <td class="s9" style="width:86px;border-right: none;  border-top-color: #fff">Кол-во занятий &gt;1.5 Сад</td>
                        <td class="s9" style="width:89px;border-right: none;  border-top-color: #fff">Кол-во человек на занятиях, где &gt;1.5 Сад</td>
                        <td class="s9" style="width:94px;border-right: none;  border-top-color: #fff">Кол-во занятий &lt;=2.5 чел. Школа</td>
                        <td class="s9" style="width:86px;border-right: none;  border-top-color: #fff">Кол-во занятий &gt;2.5 чел. Школа</td>
                        <td class="s9" style="width:89px;border-right: none;  border-top-color: #fff">Кол-во человек на занятиях, где &gt;2.5 чел. Школа</td>
                        <td class="s9" style="width:61px;border-right: none;  border-top-color: #fff">Индивид</td>
                        <td class="s9" style="width:40px;border-right: none;  border-top-color: #fff">ЗП</td>
                        <td class="s7" style="width:59px;border-right: none;  border-top-color: #fff"/>
                    </tr>
                    <tr style="height: 20px">
                        <td class="sticky-col first-col">Шишкина Ксения</td>
                        <td class="s11">7</td>
                        <td class="s3">0</td>
                        <td class="s3">4</td>
                        <td class="s3">19</td>
                        <td class="s3">0</td>
                        <td class="s3">6 612</td>
                        <td class="s4">0</td>
                        <td class="s4">1</td>
                        <td class="s4">3</td>
                        <td class="s4">2</td>
                        <td class="s4">0</td>
                        <td class="s4">0</td>
                        <td class="s4">0</td>
                        <td class="s4">3 306</td>
                        <td class="s12">0</td>
                        <td class="s12">0</td>
                        <td class="s13">9 918</td>
                        <td class="s14"
                            dir="ltr">
                            <a target="_blank"
                               href="https://ru.wikipedia.org/wiki/%D0%9D%D0%B0%D1%83%D1%87%D0%BD%D0%B0%D1%8F_%D1%84%D0%B0%D0%BD%D1%82%D0%B0%D1%81%D1%82%D0%B8%D0%BA%D0%B0">выплатить</a>
                        </td>
                    </tr>
                </tbody>
            </table>
</div>