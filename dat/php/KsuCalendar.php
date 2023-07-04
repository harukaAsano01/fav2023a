<?php

class KsuCalendar
{
    public $year;
    public $month;

    public function __construct($year, $month)
    {
        $this->year = $year;
        $this->month = $month;
    }

    public function parseCalendar($calendarData)
    {
        $dates = [];
        foreach ($calendarData as $entry) {
            if (isset($entry['days'])) {
                foreach ($entry['days'] as $day => $name) {
                    $date = $this->year . '-' . $this->month . '-' . $day;
                    $dates[$date] = [
                        'name' => $name,
                        'type' => $entry['type'],
                        'timeslots' => []
                    ];
                }
            } elseif (isset($entry['month'], $entry['week'], $entry['weekday'])) {
                $month = $entry['month'];
                $week = $entry['week'];
                $weekday = $entry['weekday'];
                $firstDay = date('N', strtotime($this->year . '-' . $this->month . '-01'));
                $lastDay = date('t', strtotime($this->year . '-' . $this->month . '-01'));
                for ($day = 1; $day <= $lastDay; $day++) {
                    $date = $this->year . '-' . $this->month . '-' . $day;
                    $currentDay = date('N', strtotime($date));
                    if (in_array($currentDay, $weekday) && in_array(ceil($day / 7), $week)) {
                        $dates[$date] = [
                            'name' => '',
                            'type' => $entry['type'],
                            'timeslots' => []
                        ];
                    }
                }
            }
        }
        return $dates;
    }

    public function parseReservation($reservationData, $facility)
    {
        $reservations = [];
        foreach ($reservationData as $reservation) {
            if ($reservation['facility_id'] == $facility) {
                $date = $reservation['date'];
                $timeslots = $reservation['timeslot'];
                if (!isset($reservations[$date])) {
                    $reservations[$date] = [];
                }
                foreach ($timeslots as $timeslot) {
                    $reservations[$date][] = $timeslot;
                }
            }
        }
        return $reservations;
    }

    public function getWeekSlice($week)
    {
        $firstDay = date('N', strtotime($this->year . '-' . $this->month . '-01'));
        $lastDay = date('t', strtotime($this->year . '-' . $this->month . '-01'));
        $startDay = ($week - 1) * 7 + (1 - $firstDay);
        $endDay = min($startDay + 6, $lastDay);
        return range($startDay, $endDay);
    }

    public function output($dates)
    {
        $monthName = date('F', strtotime($this->year . '-' . $this->month . '-01'));
        printf("%d年%d月\n========\n", $this->year, $this->month);
        for ($day = 1; $day <= 31; $day++) {
            $date = $this->year . '-' . $this->month . '-' . $day;
            if (!isset($dates[$date])) {
                break;
            }
            $name = $dates[$date]['name'];
            $type = $dates[$date]['type'];
            $timeslots = $dates[$date]['timeslots'];
            printf("%02d (%s):\n", $day, date('D', strtotime($date)));
            if (!empty($name)) {
                printf(" * name: %s\n", $name);
                printf(" - type: %s,\n", $type);
                printf(" - timeslots: [ ]\n");
            } else {
                printf(" - timeslots: [%s]\n", implode(',', $timeslots));
            }
        }
    }
}
