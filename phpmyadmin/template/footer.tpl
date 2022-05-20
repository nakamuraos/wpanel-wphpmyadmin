<?php


?>
</div>
<div class='footer'>
<?php
if($pma->host){
echo "<a href='files.php?".($db_name ? "db=".urlencode($db_name) : "")."'>$lang->Files</a> | ".($noimg ? "<a href='action.php?act=img'> $lang->show_img </a>" : "<a href='action.php?act=noimg'> $lang->hide_img </a>")." | <a href='action.php?act=logout'> $lang->logout </a>
<hr size='1px'>";
}
?>
<a href='docs.php'><?php echo $lang->docs;?> </a> | <a href='https://xcydia.tk'>xCydia.Tk</a> | <a href='translate.php'><?php echo $lang->translate;?></a></div>
</body>
</html>