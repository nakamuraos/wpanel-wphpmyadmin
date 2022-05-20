<?php
error_reporting(E_ALL ^ E_NOTICE);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(-1);

session_start();
$pma = new stdClass();
$pma->tpl="template/";
$pma->version="211";
$pma->user=$_SESSION['user'];
$pma->host=$_SESSION['host'];
$pma->pass=$_SESSION['pass'];

	/// mysql fix
	if(!extension_loaded('mysqli')){
		require_once dirname(dirname(__FILE__))."/lib/mysqli.so.php";		
	}
$noimg = isset($_SESSION['noimg']) ? true : false;
$act = isset($_GET['act']) ? trim($_GET['act']) : '';
$issql = $isexport = $isimport = false;

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header('Content-type: text/html; charset=utf-8');
include dirname(dirname(__FILE__))."/lang/index.php";
$lang=(object)$lang;
include dirname(dirname(__FILE__))."/lib/vars.php";
include dirname(dirname(__FILE__))."/lib/functions.php";
remove_magic_quotes();