<?php

/*
Y : 年。4 桁の数字。例: 1999または2003
y : 年。2 桁の数字。例: 99 または 03
d : 日。二桁の数字（先頭にゼロがつく場合も）, 01 から 31
j : 日。先頭にゼロをつけない, 1 から 31
w : 曜日。数値。0 (日曜)から 6 (土曜)
m : 月。数字。先頭にゼロをつける。 01 から 12
n : 月。数字。先頭にゼロをつけない。1 から 12
t : 日数。指定した月の日数。28 から 31
*/
 echo date('Y年m月d日');
 $y = date('Y') ;//西暦年
 $n = '平成';
 $j = $y - 1988;
 if (($y==2019 and $m>4) or ($y > 2019)){
  $n = '令和';
  $j = $y - 2018;
 } 
 echo "<br>{$n}{$j}年";
 $md0 = date('t'); //当月の日数を求める
 echo "<br>今月は{$md0}日あります。";
 $y = 2240; $m = 6;
 $date = $y . '-' . $m;
 $md1= date('t', strtotime($date));
 echo "<br>{$y}年{$m}月は{$md1}日あります。" ;

 echo date('Y-m-d')."<br/>\n";
 $y = date('Y')."<br/>\n" ;//西暦年
 function getAvailability($year){
    $availability = array(
        '$year-06-07' => '元旦',
    );
    return $year; 
 }
 echo '<h2>空き状況確認</h2>';
 echo '<p>以下の日付の空き状況を確認します。</p>';
 echo '<input type=number name= "year" value='.date('Y').'>年';
 echo '<input type=number name= "month"  value='.date('m').'>月';
 echo '<input type=number name= "day" value='.date('d').'>日'; 
?>