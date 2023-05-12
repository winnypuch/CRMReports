{literal}
    <script type="text/javascript" src="include/jqplot/jquery.jqplot.min.js"></script>
    <script type="text/javascript" src="include/jqplot/plugins/jqplot.categoryAxisRenderer.min.js"></script>
    <script type="text/javascript" src="include/jqplot/plugins/jqplot.pointLabels.min.js"></script>
    <script type="text/javascript" src="include/jqplot/plugins/jqplot.dateAxisRenderer.min.js"></script>
    <script type="text/javascript" src="include/jqplot/plugins/jqplot.cursor.min.js"></script>
    <script type="text/javascript" src="include/jqplot/plugins/jqplot.enhancedLegendRenderer.js"></script>
    <link rel="stylesheet" type="text/css" href="include/jqplot/jquery.jqplot.css"/>
    <script type="text/javascript">    
        $(function () {
            $('.datepicker').datepicker({
                showOn: "button",
                showAlways: true,
                buttonImage: "images/calbtn.png",
                buttonImageOnly: true,
                buttonText: "Calendar",
                showAnim: (('\v' == 'v') ? "" : "show")  // в ie не включаем анимацию, тормозит
            })
        });
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
      
      <a class="header__user-item header__user-item_img" href="javascript:window.print()"><img src="images/print.gif" title="Печать отчёта"></a>
      <a class="header__user-item header__user-item_img header__user-item--settings" href="edit_report.php?report=380" title="Настройки"></a>
    </span>
        Заполнение онлайн групп
</div>
<div class="top">
    <table style="margin: 0px auto;">
        <tr>
            <td>
        <span class="input_element">
          Период с <input type="text" name="date1" id="date1" value="{$date1}" size="10" class="datepicker form-control form-control-160"/> 
          по <input type="text" name="date2" id="date2" value="{$date2}" size="10" class="datepicker form-control form-control-160"/> 
        </span>

                <div style="clear: both"></div>
            </td>
            <td>
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
                    <div class="fields__cell fields__cell--header"  style="width: 264px; max-width: 264px; min-width: 264px; height: 25px !important;">
                        <b>Группа</b>
                    </div>                                       
                    {foreach $dateformate as $ondate}
                    <div class="fields__cell fields__cell--header" style="width: 50px; max-width: 100px; min-width: 50px; height: 25px !important;">
                        <b>{$ondate}</b>                                                                                                                                           
                    </div>
                    {/foreach}
                </div>
                {foreach from=$lines item=data name="rows"}
                    <div class="fields__row fields__row--info fields__row--simple" id="submain_td{$smarty.foreach.rows.iteration}" style="background-color: rgb(255, 255, 255); width: 2692.59px; height: 25px !important;"
                        onmouseover="highlightTr('submain_td',{$smarty.foreach.rows.iteration}, 1)"
                        onmouseout="highlightTr('submain_td',{$smarty.foreach.rows.iteration}, 0)">
                        <div class="fields__cell number__max_width-table" style="width: 200px; cursor: pointer; white-space: nowrap; background-color: rgb(255, 255, 255); min-width: 264px; height: 25px !important;">
                            <div class="fields__cell-inner" style="width:200px; height: 25px !important;">
                                <div class="fields__simple-text fields__value">
                                <a  href="view_line2.php?table=700&amp;line={$data.GroupId}&amp;back_url=" target="_blank">
                                {$data.GroupName}
                                </a>
                                </div>
                            </div>
                        </div>
                        {foreach $dateformate as $ondate}
                            {if array_key_exists($ondate, $data.WorkingOff)}
                                {if $data.MaxChildrenInGroup <= $data.ChildrenInGroup + $data.WorkingOff[$ondate]}
                            <div class="fields__cell" style="text-align: center; white-space: nowrap; cursor: pointer; width: 50px; min-width: 50px;  height: 25px !important; background-color: rgba(0, 0, 0, 0);">
                                <div class="fields__cell-inner" style="min-width:100%; height: 25px !important;">
                                    <div class="fields__value" style="text-align: center;overflow: hidden; padding-right: 0px;color:red;">{$data.ChildrenInGroup + $data.WorkingOff[$ondate]} из {$data.MaxChildrenInGroup}</div>
                                </div>
                            </div>                          
                                    
                                {else}                              
                            <div class="fields__cell" style="text-align: center; white-space: nowrap; cursor: pointer; width: 50px; min-width: 50px;  height: 25px !important; background-color: rgba(0, 0, 0, 0);">
                                <div class="fields__cell-inner" style="min-width:100%; height: 25px !important;">
                                    <div class="fields__value" style="text-align: center;overflow: hidden; padding-right: 0px;">{$data.ChildrenInGroup + $data.WorkingOff[$ondate]} из {$data.MaxChildrenInGroup}</div>
                                </div>
                            </div>                              
                                {/if}
                            {else}
                            <div class="fields__cell" style="text-align: center; white-space: nowrap; cursor: pointer; width: 50px; min-width: 50px;  height: 25px !important; background-color: rgba(0, 0, 0, 0);">
                                <div class="fields__cell-inner" style="min-width:100%; height: 25px !important;">
                                    <div class="fields__value" style="text-align: center;overflow: hidden; padding-right: 0px;"></div>
                                </div>
                            </div> 
                            {/if}
                        {/foreach}
                    </div>
                {/foreach}      
            </div>
        </div>
    </div>
</main>
<input type=hidden name="_date1" value="{$date1}">
<input type=hidden name="_date2" value="{$date2}">
