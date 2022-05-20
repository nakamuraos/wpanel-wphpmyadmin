<?php
session_start();
$act = isset($_GET['act']) ? trim($_GET['act']) : '';
if($act == 'logout'){
session_destroy();
header("Location: index.php");
exit;
}elseif($act == 'noimg') {
$_SESSION['noimg'] = '1';
header("Location: ".$_SERVER["HTTP_REFERER"]);
}elseif($act == 'img') {
unset($_SESSION['noimg']);
header("Location: ".$_SERVER["HTTP_REFERER"]);
} else {    header("Location: index.php");    }