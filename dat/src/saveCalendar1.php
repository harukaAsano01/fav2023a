<?php

function getWeekday($date)
{
    $wday = date('w', strtotime($date));
    $wdays = ['日', '月', '火', '水', '木', '金', '土'];
    return $wdays[$wday];
}

$coreTimes = [
    1 => '9:00~11:50',
    2 => '12:00~14:50',
    3 => '15:00~16:50'
];

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

$facility = [ //
    '01-05' => ['name' => '会議室', 'coreTime' => [1]],
    '02-10' => ['name' => '会議室', 'coreTime' => [2]],
    '03-05' => ['name' => '面接室A', 'coreTime' => [1]],
    '04-05' => ['name' => '会議室', 'coreTime' => [1]],
    '05-10' => ['name' => '面接室A', 'coreTime' => [2]],
    '06-05' => ['name' => '会議室', 'coreTime' => [1]],
    '07-10' => ['name' => '会議室', 'coreTime' => [1]],
    '08-07' => ['name' => '面接室B', 'coreTime' => [1, 2, 3]],
    '09-05' => ['name' => '会議室', 'coreTime' => [2, 3]],
    '10-10' => ['name' => '面接室B', 'coreTime' => [3]],
    '11-05' => ['name' => '会議室', 'coreTime' => [1]],
    '12-05' => ['name' => '会議室', 'coreTime' => [1]],
    '01-07' => ['name' => '面接室A', 'coreTime' => [2]],
    '02-07' => ['name' => '会議室', 'coreTime' => [1]],
    '02-08' => ['name' => '面接室A', 'coreTime' => [2, 3]],
    '03-08' => ['name' => '会議室', 'coreTime' => [1]],
    '04-08' => ['name' => '会議室', 'coreTime' => [2]],
    '05-09' => ['name' => '面接室B', 'coreTime' => [3]],
    '06-09' => ['name' => '面接室A', 'coreTime' => [2]],
    '07-09' => ['name' => '会議室', 'coreTime' => [1, 2]],
    '08-09' => ['name' => '会議室', 'coreTime' => [1]],
    '08-10' => ['name' => '面接室A', 'coreTime' => [2]],
    '09-10' => ['name' => '面接室B', 'coreTime' => [3]],
    '10-15' => ['name' => '会議室', 'coreTime' => [1]],
];


if (isset($_POST['year']) && isset($_POST['month']) && isset($_POST['day'])) {
    $y = $_POST['year'];
    $m = sprintf("%02d", $_POST['month']);
    $d = sprintf("%02d", $_POST['day']);
    $date = "{$y}-{$m}-{$d}"; // 年月日

    $wd = getWeekday($date);

    $firstDayOfMonth = date("w", mktime(0, 0, 0, $m, 1, $y)); // 曜日
    $lastDayOfMonth = date("t", mktime(0, 0, 0, $m, 1, $y)); // 月日数

    echo "[空き状況確認]<br>";

    $f = isset($_POST['facility']) ? $_POST['facility'] : 0;
    $facilities = [
        1 => '会議室',
        2 => '面接室A',
        3 => '面接室B'
    ];
    $facilityName = ($f >= 1 && $f <= 3) ? $facilities[$f] : '-';

    $holidayWdays= [0, 6]; // 土日の配列

    //$r = $reservedDates[$facilityName] ?? [];

    //if(isset($r[]))

    if ($d == 0) {
        echo "検索: {$y}年{$m}月<br>";
        echo "施設名: {$facilityName}<br>";

        echo "<br>==========<br>";
        echo "　{$y}年{$m}月<br>";
        echo "==========<br>";

        for ($day = 1; $day <= $lastDayOfMonth; $day++) {
            $currentDayWeek = date('w', mktime(0, 0, 0, $m, $day, $y));
            $wdays = ['日', '月', '火', '水', '木', '金', '土'];

            $formattedDate = sprintf("%04d-%02d-%02d", $y, $m, $day);
            $dateKey = sprintf("%02d-%02d", $m, $day);
            $holidayName = $spHoliday[$dateKey] ?? '';
            $reserveData = $facility[$dateKey] ?? ''; //Null合体演算子

            $dayCount = sprintf("%02d", $day);
            echo "{$dayCount} ({$wdays[$currentDayWeek]}):";

            //施設検索

            if (in_array($holidayName, $spHoliday)) {
                echo "<br>　-name: {$holidayName}<br>";
                echo "　-type: public_holiday<br>";
            } elseif (in_array($currentDayWeek, $holidayWdays)) {
                echo "<br>　-name: 定休日<br>";
                echo "　-type: local_holiday<br>";
            } else {
                if (isset($facility[$dateKey]) && $facility[$dateKey]['name'] === $facilityName) {
                    $coreTime = $facility[$dateKey]['coreTime'];
                
                    //echo "　-core time:";
                
                    if (is_array($coreTime)) {
                        foreach ($coreTime as $time) {
                            echo " <span style='color: blue;'>{$coreTimes[$time]}</span>";
                        }
                    } else {
                        echo " <span style='color: blue;'>{$coreTimes[$coreTime]}</span>";
                    }
                
                    echo "<br>";
                } elseif ($facilityName === '-') {
                    if (!empty($reserveData)) {
                        $reserveName = $reserveData['name'];
                        $coreTime = $reserveData['coreTime'];
                        echo "<br>　*Reserve facility: <span style='color: blue;'>{$reserveName}</span><br>";
                        echo "　-core time: ";
                
                        if (is_array($coreTime)) {
                            foreach ($coreTime as $time) {
                                echo " <span style='color: blue;'>{$coreTimes[$time]}</span>";
                            }
                        } else {
                            echo " <span style='color: blue;'>{$coreTimes[$coreTime]}</span>";
                        }
                
                        echo "<br>";
                    } else {
                        echo "<br>";
                    }
                } else {
                    echo "<br>";
                }
                
                
                /*
                if (!empty($reserveData)) {
                    $reserveName = $reserveData['name'];
                    $coreTime = $reserveData['coreTime'];
                    echo "<br>　*Reserve facility: {$reserveName}<br>";
                    echo "　-core time: {$coreTimes[$coreTime]}<br>";
                    echo "　-type: reserveRoom<br>";
                } else {
                    echo "<br>";
                }*/
            }
        }
    } else {
        echo "検索: {$y}年{$m}月{$d}日({$wd})<br>";
        echo "施設名: {$facilityName}<br>";

        echo "=============<br>";
        $currentDayWeek = date('w', mktime(0, 0, 0, $m, $d, $y));
        $dateKey = sprintf("%02d-%02d", $m, $d);
        $holidayName = $spHoliday[$dateKey] ?? '';
        $reserveData = $facility[$dateKey] ?? ''; //

        if (in_array($holidayName, $spHoliday)) {
            echo "-name: {$holidayName}<br>";
            echo "-type: public_holiday<br>";
        } elseif (in_array($currentDayWeek, $holidayWdays)) {
            echo "-name: 定休日<br>";
            echo "-type: local_holiday<br>";
        } else {
            /*
            if (!empty($reserveData)) {
                $reserveName = $reserveData['name'];
                $coreTime = $reserveData['coreTime'];
                echo "[予約あり]<br>";
                echo "*Reserve facility: {$reserveName}<br>";
                echo "-core time: {$coreTimes[$coreTime]}<br>";
                echo "-type: reserveRoom<br>";
            } else {
                echo "[予約なし]<br>";
            }*/
            if (isset($facility[$dateKey]) && $facility[$dateKey]['name'] === $facilityName) {
                $coreTime = $facility[$dateKey]['coreTime'];
            
                echo "core time:";
            
                if (is_array($coreTime)) {
                    foreach ($coreTime as $time) {
                        echo "{$coreTimes[$time]}　";
                    }
                } else {
                    echo "{$coreTimes[$coreTime]}　";
                }
            
                echo "<br>";
            } elseif ($facilityName === '-') {
                if (!empty($reserveData)) {
                    $reserveName = $reserveData['name'];
                    $coreTime = $reserveData['coreTime'];
                    echo "<br>　*Reserve facility: <span style='color: blue;'>{$reserveName}</span><br>";
                    echo "　-core time: ";
            
                    if (is_array($coreTime)) {
                        foreach ($coreTime as $time) {
                            echo " <span style='color: blue;'>{$coreTimes[$time]}</span>";
                        }
                    } else {
                        echo " <span style='color: blue;'>{$coreTimes[$coreTime]}</span>";
                    }
            
                    echo "<br>";
                } else {
                    echo "[予約なし]<br>";
                }
            }
        }
    }
}
?>
