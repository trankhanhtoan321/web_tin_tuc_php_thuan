<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "laptrinh321";
$homeurl = "http://laptrinh321.dev:8080";
$home_title = "Trang Chủ - Lập Trình 321";

$conn = mysqli_connect($servername, $username, $password, $dbname);
mysqli_set_charset($conn,'utf8');
date_default_timezone_set("Asia/Ho_Chi_Minh");
ob_start();
error_reporting(0);
//////////////////////////////////////////////////
$sql="SELECT * FROM options";
$result=mysqli_query($conn,$sql);
$row=mysqli_fetch_assoc($result);
/////////////////////////////////
$maxpage=$row['maxpage'];
$maxpage_cmt=$row['maxpage_cmt'];
$option_footer=$row['footer'];
$facebook=$row['facebook'];
$google=$row['google'];
$youtube=$row['youtube'];
////////////////////////////////////////////////
$sql=NULL;$result=NULL;$row=NULL;
///////////////////////////////////////////////////
function rewrite($s)
{
	$a=array("á","à","ả","ã","ạ","Á","À","Ả","Ã","Ạ",
			 "ắ","ằ","ẳ","ẵ","ặ","Ắ","Ằ","Ẳ","Ẵ","Ặ","ă","Ă",
			 "ấ","ầ","ẩ","ẫ","ậ","Ấ","Ầ","Ẩ","Ẫ","Ậ","â","Â",
			 "đ","Đ","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z",
			 "é","è","ẻ","ẽ","ẹ","É","È","Ẻ","Ẽ","Ẹ",
			 "ế","ề","ể","ễ","ệ","Ế","Ề","Ể","Ễ","Ệ","ê","Ê",
			 "í","ì","ỉ","ĩ","ị","Í","Ì","Ỉ","Ĩ","Ị",
			 "ó","ò","ỏ","õ","ọ","Ó","Ò","Ỏ","Õ","Ọ",
			 "ố","ồ","ổ","ỗ","ộ","Ố","Ồ","Ổ","Ỗ","Ộ","ô","Ô",
			 "ớ","ờ","ở","ỡ","ợ","Ớ","Ờ","Ở","Ỡ","Ợ","ơ","Ơ",
			 "ú","ù","ủ","ũ","ụ","Ú","Ù","Ủ","Ũ","Ụ",
			 "ứ","ừ","ử","ữ","ự","Ứ","Ừ","Ử","Ữ","Ự","ư","Ư",
			 "ý","ỳ","ỷ","ỹ","ỵ","Ý","Ỳ","Ỷ","Ỹ","Ỵ",",","+","[","]","-",":","/",
			 "*","(",")","?","\"");

	$b=array("a","a","a","a","a","a","a","a","a","a",
		     "a","a","a","a","a","a","a","a","a","a","a","a",
		     "a","a","a","a","a","a","a","a","a","a","a","a",
		     "d","d","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z",
		     "e","e","e","e","e","e","e","e","e","e",
		     "e","e","e","e","e","e","e","e","e","e","e","e",
		     "i","i","i","i","i","i","i","i","i","i",
		     "o","o","o","o","o","o","o","o","o","o",
		     "o","o","o","o","o","o","o","o","o","o","o","o",
		     "o","o","o","o","o","o","o","o","o","o","o","o",
		     "u","u","u","u","u","u","u","u","u","u",
		     "u","u","u","u","u","u","u","u","u","u","u","u",
		     "y","y","y","y","y","y","y","y","y","y","","","","","","","",
		     "","","","","");

	$s=str_replace($a,$b,$s);
	$s=str_replace(" ","-",$s);
	return $s;
}
function getdate_now()
{
	$now=getdate();
	$date=$now['year']."-".$now['mon']."-".$now['mday'];
	return $date;
}
function thong_bao($uid)
{
	global $conn;
	$sql="SELECT count(*) as num FROM theo_doi WHERE td_uid=$uid AND thong_bao=1";
	$result = mysqli_query($conn,$sql);
	$row=mysqli_fetch_assoc($result);
	$num=$row['num'];
	$sql=NULL;
	$row=NULL;
	$result=NULL;
	return $num;
}
function get_url() 
{
	$pageURL = "http://";
	if($_SERVER['SERVER_PORT']==80) $pageURL .= $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	else $pageURL .= $_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
	return $pageURL;
}
function get_title()
{
	global $homeurl;
	global $home_title;
	global $conn;
	$url=get_url();
	//trang chu
	if($url==$homeurl."/") return $home_title;
	//login
	else if($url==$homeurl."/login.php") return "Đăng Nhập";
	//dang ky
	else if($url==$homeurl."/register.php") return "Đăng Ký Tài Khoản";
	//tim kiem
	else if(strstr($url,$homeurl."/timkiem.php")!='')
	{
		$s='';
		if(isset($_GET['q'])) $s=$_GET['q'];
		return "Tìm Kiếm Trên Web: $s";
	}
	//profile
	else if(strstr($url,$homeurl."/profile.php")!='') return "Profile Member";
	//thong bao
	else if(strstr($url,$homeurl."/thong_bao.php")!='') return "Thông Báo";
	//chuyen muc
	else if($s=strstr($url,$homeurl."/category-")!='')
	{
		$s=substr($url,strlen($homeurl."/category-"),strlen($url)-strlen($homeurl."/category-"));
		$s=(int)$s;
		$sql="SELECT title FROM chuyenmuc WHERE id=$s";
		$result=mysqli_query($conn,$sql);
		$row=mysqli_fetch_assoc($result);
		$sql=NULL;$result==NULL;
		return "Chuyên Mục ".$row['title'];
	}
	//bai viet + trang khac
	else
	{
		$s=substr($url,strlen($homeurl."/"),strlen($url)-strlen($homeurl));
		//bai viet
		if((int)$s>0)
		{
			$s=(int)$s;
			$sql="SELECT subject FROM baiviet WHERE idbv=$s";
			$result=mysqli_query($conn,$sql);
			$row=mysqli_fetch_assoc($result);
			$sql=NULL;$result==NULL;
			return $row['subject'];
		}
		//cac trang khac
		else
		{
			return $home_title;
		}
	}
}
function get_des()
{
	global $homeurl;
	global $home_title;
	global $conn;
	$url=get_url();
	//////////////////////////////
	$s=substr($url,strlen($homeurl."/"),strlen($url)-strlen($homeurl));
	//bai viet
	if((int)$s>0)
	{
		$s=(int)$s;
		$sql="SELECT des FROM baiviet WHERE idbv=$s";
		$result=mysqli_query($conn,$sql);
		$row=mysqli_fetch_assoc($result);
		$sql=NULL;$result==NULL;
		return $row['des'];
	}
	else return '';
}
include "/function/function.php";

?>
