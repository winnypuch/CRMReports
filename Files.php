<?php
$user_id = $user['id'];
$group_id = $user['group_id'];
$request_id = $line['ID'];
$request_theme = $line['Тема обращения'];
$request_number = $line['Заявка'];
$company_id = $line['Организация']['ID'];
$company_name = $line['Организация']['Название'];
$autor_id = $line['Кто добавил'];
$managers = $line['Ответственный'];//Множественный
$client_id = $line['Организация']['ID'];
$client_login_id = $line['Организация']['Логин'];

/***********Определим цветовую схему****************/
$sqlQuery1 = "SELECT `color1`,`color2`,`color3` FROM `".SCHEMES_TABLE."` WHERE `active`='1'";
$result = sql_query($sqlQuery1) or user_error(mysql_error()."<br>".$sqlQuery1."<br>", E_USER_ERROR);
$row = sql_fetch_assoc($result);
$color1 = $row['color1'];
$color2 = $row['color2'];
$color3 = $row['color3'];
/**************Автор**********/
$sql_autor = "
SELECT `fio` FROM `".USERS_TABLE."`
WHERE `id` = ".$autor_id."
";
$res_autor = sql_query($sql_autor);
while ($row_autor=sql_fetch_assoc($res_autor))
  $autor_name = $row_autor['fio'];

if($_REQUEST['save']==1) //Сохраняем
{
  $attach_files = Array();
  $file_path= $_SERVER['DOCUMENT_ROOT']."/cb/temp/";
  /********Проверим, а нет ли вложений*********************/

  if($_FILES['UserFile']['tmp_name'])
  {

        //$attach_files = Array();
        if (@!copy($_FILES['UserFile']['tmp_name'], $file_path.$_FILES["UserFile"]["name"]))
        {
          $error_upload = "<h4><font color=red>Error. Ошибка! Не удалось загрузить файл на сервер!</font></h4>".$file_path.$_FILES["UserFile"]["name"]; //exit;
          echo $error_upload;
          exit;
        }
        else
        {
          $tmp_name = $_FILES["UserFile"]["name"];
          $save_file_content = file_get_contents($file_path.$tmp_name);//Если файл писать в базу
          $save_file_name = $tmp_name;//Имя записываемого файла

          $one_attach['name'] = $tmp_name;
          $one_attach['disp'] = "attachment";
          $one_attach['type'] = get_file_type($tmp_name);
          $one_attach['content'] = file_get_contents($file_path.$tmp_name);
          //display_notification($one_attach['content'], $type=1);
          //echo $one_attach['content'];
          if(!empty($one_attach['content']))
                $attach_files[] = $one_attach;
          unlink($file_path.$tmp_name);
        }
  }
  //$request_id
  $answer_txt = $_REQUEST['answer'];
  $answer_arr = array(
  'f14480'=>$request_id,  //Заявка
  'f14510'=>$user_id, //Отвечает
  'f14520'=>date("Y-m-d H:i:s"), //Дата
  'f14490'=>$answer_txt //Сообщение
  //'f14500'=>$tmp_name
  //''=>
  //''=>
  //''=>
  );
  $new_answ = data_insert(590,EVENTS_ENABLE,$answer_arr);
//  save_data_file($field_id,$line_id,$fname,$data)
//drop_data_file($field_id,$line_id,$fname)
//drop_files_by_field($field_id)
  if(($new_answ)&&(count($attach_files)))
  {
        $fn = $attach_files[0]['name'];
        data_update(590,array('f14500'=>$fn),"id=".$new_answ);
        $f_content = $attach_files[0]['content'];

        save_data_file(14500, $new_answ, $fn, $f_content);
  }

  $subject = 'Новое сообщение по заявке №'.$request_number;
  $content = $answer_txt."<br><br>\r\n\r\n".'  <a href=https://adress.ru/cb/view_line2.php?table=590&line='.$new_answ.' target=_blank>Перейти</a>';;

  if($group_id != 777) //Не клиент
  {
        //Пошлем письмо клиенту
        $sql_client = "
           SELECT `e_mail` FROM `".USERS_TABLE."`
           WHERE `id` = ".$client_login_id ."
        ";
        $res_client = sql_query($sql_client);
        while ($row_client=sql_fetch_assoc($res_client))
          $client_mail = $row_client['e_mail'];

        if($client_mail)
        {
          //display_notification('<br>TEST<br>'.$client_mail , $type=1);
          sendmail($subject,$content,$client_mail,"","","","text/html","utf-8",array(), $attach_files);
        }
  }
  else//Пишет клиент
  {
        /***Найдем  ответственных******/
        $managers_arr_tmp = explode('-',$managers);
        foreach($managers_arr_tmp as $val)
        {
          if($val > 0)
                $managers_arr[] = intval($val);
        }
        $managers_str = implode(',',$managers_arr);


        $sql_manag = "
        SELECT `id`,`fio`,`e_mail` FROM `".USERS_TABLE."`
        WHERE `id` IN(".$managers_str.")
        ";

        $res_manag  = sql_query($sql_manag) or user_error(mysql_error()."<br>".$sql_manag."<br>", E_USER_ERROR);
        while($row_manag=sql_fetch_assoc($res_manag))
        {
          $manager_mail = trim($row_manag['e_mail']);
          if($manager_mail)
          {
                sendmail($subject,$content,$manager_mail,"","","","text/html","utf-8",array(), $attach_files);
          }
        }
  }

  echo "<script>window.opener.location.reload();window.close();</script>";
}
else
{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>Написать сообщение</title>
<!--
<script type="text/javascript" src="/include/js/find.js"></script>
<script type="text/javascript" src="/include/js/kp_new.js"></script>
<script type="text/javascript" src="/modules/public/transfer/transfer_pos.js"></script>
-->
<script type="text/javascript">
function getObj(objID)
{
  if (document.getElementById) {return document.getElementById(objID);}
  else if (document.all) {return document.all[objID];}
  else if (document.layers) {return document.layers[objID];}
}
</script>
<style>
BODY {
  font-family:areal,tahoma,verdana;
  font-weight:normal;
  font-size: 12px;
  }
table {
  font-size: 12px;
  }
.link {
        text-decoration: underline;
        color: #0075CE;
}
</style>
</head>
<body>
<div style="background-color:#FFFFFF;width:1000px;margin:0 auto;" align=center>

<form name=answer_form id=answer_form action="" method="post" enctype=multipart/form-data>
<!--<div style="width:940px;height:100px;background-color:<?php echo $color2; ?>;">-->
<table width="90%" cellspacing="3" cellpadding = "5">
   <tr>
         <td width="20%" bgcolor=<?php echo $color2; ?> style="color:white;font-weight:bold;text-align:right;">
                Обращение №:
         </td>
         <td bgcolor=<?php echo $color3; ?>>
                <?php echo $request_number; ?>
         </td>
   </tr>
   <tr>
         <td width="20%" bgcolor=<?php echo $color2; ?> style="color:white;font-weight:bold;text-align:right;">
                Организация:
         </td>
         <td bgcolor=<?php echo $color3; ?>>
           <?php echo $company_name; ?>
         </td>
   </tr>
   <tr>
         <td width="20%" bgcolor=<?php echo $color2; ?> style="color:white;font-weight:bold;text-align:right;">
                Тема обращения:
         </td>
         <td bgcolor=<?php echo $color3; ?>>
                <?php echo $request_theme; ?>
         </td>
   </tr>
   <tr>
         <td width="20%" bgcolor=<?php echo $color2; ?> style="color:white;font-weight:bold;text-align:right;">
                Автор обращения:
         </td>
         <td bgcolor=<?php echo $color3; ?>>
                <?php echo $autor_name; ?>
         </td>
   </tr>
</table>
<h4>Написать сообщение.</h4>
<textarea name=answer id=answer cols=126 rows=20></textarea>
<table width="90%" cellspacing="3" cellpadding = "5">
   <!--<tr>
        <td width="10%">
        &nbsp;
        </td>
        <td width="70%">
          &nbsp;
        </td>
        <td width="10%">
          &nbsp;
        </td>
        <td width="10%">
          &nbsp;
        </td>
   </tr>
   <tr>
        <td width="10%">
        &nbsp;
        </td>
        <td width="70%" align=right>
          &nbsp;
        </td>
        <td width="10%">
          &nbsp;
        </td>
        <td width="10%">
          &nbsp;
        </td>
   </tr>-->

   <tr>
        <td width="10%">
        <?php
          if($group_id !=777)
          {
        ?>
          <input type=button id=quest value="Типовые ответы" onClick="window.open('modules/public/questions.php','newwin','status=1,resizable=1,width=1080,height=550'); ">
        <?php
          }
        ?>
        </td>
        <td width="70%" align=right>
          <input type="FILE" name="UserFile">
          <input type=hidden name=send value="1">
        </td>
        <td width="10%">
          <input type=button id=cancel value="Отменить" onClick="window.close();">
        </td>
        <td width="10%">
          <input type=submit id=save value="Сохранить">
        </td>
   </tr>
   <tr>
        <td colspan=4>
        Примечание:
        </td>
   </tr>
   <tr>
        <td colspan=4>
        <font color=red><i>Прикрепить можно один файл. Если нужно  отправить несколько файлов, создайте один архивный файл (ZIP или RAR) со всеми вложениями.</i></font>
        </td>
   </tr>
</table>
</div>
<input type=hidden name=save id=save value=1>
<input type=hidden name=csrf value="<?php echo $csrf; ?>">
</form>
</body>

}
?>