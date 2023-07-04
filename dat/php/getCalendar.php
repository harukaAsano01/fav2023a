<?php
require('KsuCalendar.php');

$year = 2023;
$month = 10;
$facility = 12216;

$dates = getAvailability($year, $month, $facility);
$dat_facility = include('dat_facilities.php');
$timeslots = $dat_facility[$facility]['timeslots'];

printf("facility: %s\n", $facility);
printf("timeslots: [%s]\n", implode(',', array_keys($timeslots)));

foreach ($timeslots as $id => $v) {
    printf(" %d: %s - %s\n", $id, $v['start_time'], $v['end_time']);
}

echo "\n\n";

$cal = new KsuCalendar($year, $month);
printf("%d年%d月\n========\n", $cal->year, $cal->month);
$cal->output($dates);

function getAvailability($year, $month, $facility)
{
    $dat_calendar = include('dat_calendar.php');
    $dat_reservation = include('dat_reservation.php');

    $cal = new KsuCalendar($year, $month);
    $dates = [];

    if (isset($dat_calendar[$year])) {
        $cal_dates = $cal->parseCalendar($dat_calendar[$year]);

        if (isset($dat_reservation[$facility])) {
            $rev_dates = $cal->parseReservation($dat_reservation[$facility], $facility);
            $dates = $cal_dates;

            foreach ($rev_dates as $d => $v) {
                $dates[$d] = isset($dates[$d]) ? array_merge($dates[$d], $v) : $v;
            }
        } else {
            $dates = $cal_dates;
        }
    }

    ksort($dates);
    return $dates;
}

