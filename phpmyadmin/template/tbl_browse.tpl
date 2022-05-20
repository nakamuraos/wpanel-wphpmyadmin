<?php


echo "<div class='query'>".highlight_sql($_q)."</div>";
echo "<div class='left'>".$_var->home."<a href='tables.php?db=".urlencode($db_name)."'>".pma_img('database.png').htmlentities($db_name)."</a> &#187; <a href='table.php?$_url'>".pma_img('b_tbl.png').htmlentities($tb_name)."</a> ";


if($act=='edit'){
	echo "&#187; <a href='?$_url'>".pma_img("b_tbl.png").$lang->Browse." </a> &#187; <a href='?act=view&$_url'>".pma_img("show_table_row.png").$lang->view."</a> &#187; ".pma_img("b_edit.png").$lang->Edit."<hr size='1px'>
	</div><br/>";
	
	if($_POST){

		// echo "<div class='query'>".highlight_sql($_q)."</div>";
		echo $_err ? "<div class='notice'> ".pma_img('s_error.png')." $lang->Error : $_err </div>" : "<div class='success'> ".pma_img('s_success.png')." $lang->sql_ok </div>";
}else{
	echo "<form action='?act=edit&$_url' method='post'>
	<table class='tb_border' cellspacing='0' width='100%'>";
	foreach($col as $k => $v){
		echo "<tr><td><b>".$k."</b></td> <td>".$v['type']."</td><td>";
		if($v['type'] == 'text'){
			echo "<textarea name='i[]' rows='10' style='width:97%;'>".htmlentities($v['Default'])."</textarea>";
			}elseif($v['type'] == 'enum' || $v['type'] == 'set'){
				echo make_select(0,"i[]",$v['enum_set_values'],'test4',"<option></option>");
			}else{
				echo "<input type='text' name='i[]' value='".htmlentities($v['Default'],ENT_QUOTES)."'>";
			}
	
		echo "</td></tr>";
	}
	echo "</table>
	<input type='submit' name='ok' value='$lang->Save'>
	</form>";
}
	


}elseif($act=='drop'){
	echo "&#187; <a href='?$_url'>".pma_img("b_tbl.png").$lang->Browse." </a> &#187; <a href='?act=view&$_url'>".pma_img("show_table_row.png").$lang->view."</a> &#187; ".pma_img("b_drop.png").$lang->Drop."<hr size='1px'>
	</div><br/>";
	if($_POST['ok']){
		// echo "<div class='query'>".highlight_sql($_q)."</div>";
		echo $_err ? "<div class='notice'> ".pma_img('s_error.png')." $lang->Error : $_err </div>" : "<div class='success'> ".pma_img('s_success.png')." $lang->sql_ok </div>"; 
	}else {	
		echo "<form action='?act=drop&$_url' method='post'>
		$lang->DROP_DELETE_this_record <br/>
		<input type='submit' value='$lang->Yes' name='ok'> <a href='?$_url'> $lang->No </a>
		</form>";
	}
}elseif($act=='view'){
	echo "&#187; <a href='?$_url'>".pma_img("b_tbl.png").$lang->Browse." </a> &#187; ".pma_img("show_table_row.png").$lang->view." <hr size='1px'>
	<a href='?act=edit&$_url'>".pma_img('b_edit.png')."$lang->Edit</a> | <a href='?act=drop&$_url'>".pma_img('b_drop.png')."$lang->delete</a> | <a href='tbl_insert.php?$_url'>".pma_img('b_insrow.png')."$lang->Copy</a>
	</div><br/><br/>
	<table class='tb_border' cellspacing='0'>";

	foreach($r_data as $k => $v){
		echo "<tr><td> ".htmlentities($k)." </td> <td> ".nl2br(htmlentities($v))." </td> </tr>";
	}
	
	echo "</table>";

}elseif($act=='multi'){

	echo "&#187; ".pma_img("b_tbl.png")."<a href='?$_url'>$lang->Browse </a> &#187; ".pma_img("b_drop.png")."$lang->Drop <hr size='1px'></div>";
	if(!$_POST['ok']) {
		printf("<div class='left'>".$lang->You_selected_records,count($_POST['i']));
		echo "<br/> ".$_msg[0]." <br/>";
		echo $lang->DO_YOU_WANT_TO_DELETE_RECORDS;
		echo "<br/> <form action='?act=multi&".$_url."' method='post'><input type='hidden' name='do' value='".$_POST['do']."'>".$_msg[1]."<input type='submit' name='ok' value='".$lang->Yes."'> <a href='?$_url'>".$lang->No."</a></div>";
		
	}else {
		if($_err){
			foreach($_err as $e)
				echo "<div class='notice'>".pma_img("s_error.png")." $lang->Error : $e </div>";
		}
		if($_msg){
			foreach($_msg as $m)
				echo "<div class='success'>".pma_img("s_success.png")."$m $lang->Record_droped </div>";
		}
	}
}else {
?>
<script type="text/javascript">
function toggle(source) {
  checkboxes = document.getElementsByName('i[]');
  for(var i in checkboxes)
    checkboxes[i].checked = source.checked;
}
</script>
<?php
echo "&#187; ".pma_img("b_tbl.png")." $lang->Browse </div><hr size='1px'>
	<div class='topbar'>
	<span class='selected'><a href='tbl_browse.php?$_url_s'>".pma_img("b_tbl.png")." $lang->Browse </a></span>
	<span><a href='table.php?$_url_s'>".pma_img("b_props.png")." $lang->Structure </a></span>
	<span><a href='tbl_search.php?$_url'>".pma_img("b_search.png")." $lang->Search </a></span>
	</div>";
	if($_SESSION['search'] && isset($_GET['search2']))
		printf("<div class='success'> $lang->exit_search_mode </div>",$_url_s);
if($data->num_rows > 0) {	
	echo "<form action='?'>".get_hidden(array('col','search'))."<input type='text' size='4' name='search' value='".htmlentities($_GET['search'],ENT_QUOTES)."'><select name='col'>";
	foreach($_cols as $c)
		echo "<option value='".urlencode($c)."'".($column == $c ? "SELECTED":"").">".htmlentities($c)."</option>";
	echo "</select>
	<input type='submit' value='$lang->Show'><br/>	&nbsp;&nbsp;&nbsp;";
	if($sort == "ASC")
		echo "$lang->Asc | <a href='?$_url&sort=1'>$lang->Desc</a>";
		else
		echo "<a href='?$_url&sort=0'>$lang->Asc</a> | $lang->Desc";
	echo "<br/><br/></form><form action='?act=multi&$_url' method='post'>	";
	$i=0;
 	foreach($tb_data as $d){
		echo "<input type='checkbox' name='i[]' value='".base64_encode($_unq[$i])."'><input type='hidden' name='j[]' value='".urlencode($d[$column])."'><a href='?act=view&unq=".base64_encode($_unq[$i])."&$_url'>".($d[$column] == '' ? "<i style='color:#ccc;'>".$lang->null."</i>" : htmlentities($d[$column]))."</a><br/>"; ++$i;
	}
	echo "<div class='left'>
	- - -<br/><input type='checkbox' onClick='toggle(this)'> 
	".make_select(1,'do',$_var->tb_br_do)."
	<input type='submit' value='".$lang->Go."'>
	</form>";
	if($total_pages > 1){
		echo "<div class='pag'>".pag($total_num_rows,$page,"?$_url&page=",true,$perP)."</div>
		<form action='?'>".get_hidden(array('page'))."
		<input name='page' type='text' size='3' value='".($page==$total_pages ? $page-1 : $page+1)."'> <input type='submit' value='$lang->Jump'>
		</form>";
	}
	echo "</div>";
	

	 
	echo "<form action='?perp&";foreach($_GET as $k =>$v) {if($k !='perp' && $k != 'page'){echo urlencode($k)."=".urlencode($v)."&";} }echo "' method='post'>
	$lang->Show <select name='perp'>";
	foreach($_var->perp as $nr) {
		echo $nr == $_SESSION['perp'] ? "<option value='$nr' SELECTED>$nr</option>" : "<option value='$nr'>$nr</option>";}
	echo "</select><input type='submit' value='$lang->Per_Page'>
	</form><br/><br/>".$total_num_rows." ".$lang->records;
	
	
} else {
	echo "<div class='success'> ".pma_img('s_success.png')." $lang->returned_0_rows </div>";
	// if it's a search refresh to normal page after a few seconds
	if(isset($_GET['search']))
		echo "<meta http-equiv='refresh' content='5; url=?$_url_s'>";
};
	echo "<div class='footbar'>	
	<span><a href='tbl_insert.php?$_url'>".pma_img("b_insrow.png")." $lang->Insert </a></span>
	<span><a href='table.php?".$_url."&act=empty'>".pma_img("bd_empty.png")." $lang->Empty </a></span>
	<span><a href='table.php?".$_url."&act=drop'>".pma_img("b_drop.png")." $lang->Drop </a></span>
	</div>";

}