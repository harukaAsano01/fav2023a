<?php
if (isset($_POST['year']) && isset($_POST['month']) && isset($_POST['day'])) {
    $y = $_POST['year'];
    $m = sprintf("%02d", $_POST['month']);
    $d = sprintf("%02d", $_POST['day']);
    $date = $y . '-' . $m . '-' . $d;
    $md1 = date('t', strtotime($date));
  
    $spHoliday = [
      '01-01' => '元日',
      '01-02' => '振替休日',
      '01-09' => '成人の日',
      '02-11' => '建国記念の日',
      '02-23' => '天皇誕生日',
      '03-21' => '春分の日',
      '04-29' => '昭和の日',
      '05-03' => '憲法記念日',
      '05-04' => 'みどりの日',
      '05-05' => 'こどもの日',
      '07-17' => '海の日',
      '08-11' => '山の日',
      '09-18' => '敬老の日',
      '09-23' => '秋分の日',
      '10-09' => 'スポーツの日',
      '11-03' => '文化の日',
      '11-23' => '勤労感謝の日'
    ];
  
    // 施設予約情報
    $facilityReservation = [
      $date => '施設A',
      '2023-06-20' => '施設B',
      '2023-06-25' => '施設C'
    ];
  
    echo "<br>{$y}年{$m}月は{$md1}日あります。";
  
    function getWeekday($date)
  
    {
  
      $weekday = date('w', strtotime($date));
  
      $weekdays = ['日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日'];
  
      return $weekdays[$weekday];
  
    }
  
  
  
  
    $wd = getWeekday($date);
  
    echo "{$date}は{$wd}です。";
  
  
  
  
    $firstDayOfMonth = date("w", mktime(0, 0, 0, $m, 1, $y));
  
    $lastDayOfMonth = date("t", mktime(0, 0, 0, $m, 1, $y));
  
  
  
  
    // カレンダーの表示
  
    echo "<h2>{$y}年{$m}月</h2>";
  
  
  
  
    // 日にちと曜日の出力
  
    for ($day = 1; $day <= $lastDayOfMonth; $day++) {
  
      $currentDayOfWeek = date("w", mktime(0, 0, 0, $m, $day, $y));
  
      $dayOfWeek = ['日', '月', '火', '水', '木', '金', '土'];
  
  
  
  
      $formattedDate = sprintf("%04d-%02d-%02d", $y, $m, $day);
  
      $holidayKey = sprintf("%02d-%02d", $m, $day);
  
      $holidayName = isset($spHoliday[$holidayKey]) ? $spHoliday[$holidayKey] : '';
  
  
  
  
      $reservationInfo = isset($facilityReservation[$formattedDate]) ? "{$facilityReservation[$formattedDate]}が予約されました" : '';
  
  
  
  
      echo "{$day} ({$dayOfWeek[$currentDayOfWeek]}):";
  
  
  
  
      if ($holidayName !== '') {
  
        echo " {$holidayName}";
  
      }
  
  
  
  
      echo " {$reservationInfo}<br>";
  
    }
  
  }
?>