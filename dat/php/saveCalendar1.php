<?php

function getWeekday($date)
{
    $wday = date('w', strtotime($date));
    $wdays = ['日', '月', '火', '水', '木', '金', '土'];
    return $wdays[$wday];
}

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

$facility = [
    '01-05' => '会議室',
    '02-10' => '会議室',
    '03-05' => '面接室A',
    '04-05' => '会議室',
    '05-10' => '面接室A',
    '06-05' => '会議室',
    '07-10' => '会議室',
    '08-05' => '面接室B',
    '09-05' => '会議室',
    '10-10' => '面接室B',
    '11-05' => '会議室',
    '12-05' => '会議室',
    '01-07' => '面接室A',
    '02-07' => '会議室',
    '02-08' => '面接室A',
    '03-08' => '会議室',
    '04-08' => '会議室',
    '05-09' => '面接室B',
    '06-09' => '面接室A',
    '07-09' => '会議室',
    '08-09' => '会議室',
    '08-10' => '面接室A',
    '09-10' => '面接室B',
    '10-15' => '会議室',
];

if (isset($_POST['year']) && ($_POST['month'] || $_POST['day'] || $_POST['facility'])) {
    $y = $_POST['year'];
    $m = sprintf("%02d", $_POST['month']);
    $d = sprintf("%02d", $_POST['day']);
    $date = "{$y}-{$m}-{$d}"; //年月日

    $wd = getWeekday($date);

    $firstDayOfMonth = date("w", mktime(0, 0, 0, $m, 1, $y)); //曜日
    $lastDayOfMonth = date("t", mktime(0, 0, 0, $m, 1, $y)); //月日数

    echo "[空き状況確認]<br>";

    if ($d == 0) {
        echo "検索: {$y}年{$m}月<br>";

        $f = isset($_POST['facility']) ? $_POST['facility'] : 0;
        $facilities = [
            1 => '会議室',
            2 => '面接室A',
            3 => '面接室B'
        ];

        if ($f >= 1 && $f <= 3) {
            echo "施設名: {$facilities[$f]}<br>";
        } else {
            echo "施設名: 未設定<br>";
        }

        echo "時限: <br>";

        echo "<br>==========<br>";
        echo "　{$y}年{$m}月<br>";
        echo "==========<br>";

        for ($day = 1; $day <= $lastDayOfMonth; $day++) {
            //カレンダー
            $currentDayWeek = date('w', mktime(0, 0, 0, $m, $day, $y));
            $wdays = ['日', '月', '火', '水', '木', '金', '土'];

            $formattedDate = sprintf("%04d-%02d-%02d", $y, $m, $day);
            $holidayKey = sprintf("%02d-%02d", $m, $day);
            $holidayName = isset($spHoliday[$holidayKey]) ? $spHoliday[$holidayKey] : '';

            //施設
            //$reserveKey = sprintf("%02d-%02d", $m, $day);
            $reserveName = isset($facility[$holidayKey]) ? $facility[$holidayKey] : '';

            $dayCount = sprintf("%02d", $day);
            echo "{$dayCount} ({$wdays[$currentDayWeek]}):";

            if ($holidayName != '') {
                echo "<br>　-name: {$holidayName}<br>";
                echo "　-type: public_holiday<br>";
            } elseif (($currentDayWeek == 0) || ($currentDayWeek == 6)) {
                echo "<br>　-name: 定休日<br>";
                echo "　-type: local_holiday<br>";
            } else {
                if ($reserveName != '') {
                    echo "<br>　*Reserve facility: {$reserveName}<br>";
                    echo "　-type: reserveRoom<br>";
                } else {
                    echo "<br>";
                }
            }
        }
        
    } else {
        echo "検索: {$y}年{$m}月{$d}日({$wd})<br>";

        $f = isset($_POST['facility']) ? $_POST['facility'] : 0;
        $facilities = [
            1 => '会議室',
            2 => '面接室A',
            3 => '面接室B'
        ];

        if ($f >= 1 && $f <= 3) {
            echo "施設名: {$facilities[$f]}<br>";
        } else {
            echo "施設名: - <br>";
        }

        echo "時限: - <br>";

        echo "=============";

        $currentDayWeek = date('w', mktime(0, 0, 0, $m, $d, $y));
        $holidayKey = sprintf("%02d-%02d", $m, $d);
        $holidayName = isset($spHoliday[$holidayKey]) ? $spHoliday[$holidayKey] : '';

        //施設
        //$reserveKey = sprintf("%02d-%02d", $m, $day);
        $reserveName = isset($facility[$holidayKey]) ? $facility[$holidayKey] : '';

        if ($holidayName != '') {
            echo "-name: {$holidayName}<br>";
            echo "-type: public_holiday<br>";
        } elseif (($currentDayWeek == 0) || ($currentDayWeek == 6)) {
            echo "-name: 定休日<br>";
            echo "-type: local_holiday<br>";
        } else {
            if ($reserveName != '') {
                echo "<br>*Reserve facility: {$reserveName}<br>";
                echo "-type: reserveRoom<br>";
            } else {
                echo "<br>";
            }
        }
    }

}

?>
