<?php
require_once 'src\main\domain\model\exception\users\InvalidBirthdayFormatException.php';
require_once 'src\main\domain\model\exception\users\InvalidDateYearException.php';
require_once 'src\main\domain\model\exception\users\InvalidDateMonthException.php';
require_once 'src\main\domain\model\exception\users\InvalidDateDayException.php';

class DateParser {
    public function validate($date): string {
        $formattedDate = null;
        $tempDate = explode("-", $date);
        if (count($tempDate) !== 3) throw new InvalidBirthdayFormatException;
        if (strlen(strval($tempDate[0])) !== 4 || $tempDate[0] < date("Y")-150 || $tempDate[0] > date("Y")) {
            throw new InvalidDateYearException;
        };
        if (strlen(strval($tempDate[1])) !== 2 || $tempDate[1] < 1 || $tempDate[1] > 12) throw new InvalidDateMonthException;
        if (strlen(strval($tempDate[2])) !== 2 || $tempDate[2] < 1 || $tempDate[2] > 31) throw new InvalidDateDayException;

        try {
            $formattedDate = new DateTime($date, new DateTimeZone(date_default_timezone_get()));
            $formattedDate = $formattedDate->format("Y/m/d");
        } catch (Exception $e) {throw new InvalidBirthdayFormatException;}
        return $formattedDate;
    }
}