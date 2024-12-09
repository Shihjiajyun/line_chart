<?php
@session_start();

$host = 'hkg1.clusters.zeabur.com';
$port = '32073';
$dbuser = 'root';
$dbpw = 'cuPoe5XnrSl2693iA8I704GkyqapYTj1';
$dbname = 'line_chart';


$conn = mysqli_connect($host, $dbuser, $dbpw, $dbname, $port);
if ($conn) {
  mysqli_query($conn, "SET NAMES utf8");
  // 將連線物件存儲在 session 中
  $_SESSION['link'] = $conn;
  // echo "已正確連線";
} else {
  echo '無法連線mysql資料庫 :<br/>' . mysqli_connect_error();
}