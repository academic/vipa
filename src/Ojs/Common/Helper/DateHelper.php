<?php

namespace Ojs\Common\Helper;

class DateHelper
{

    /**
     * caclulate remaining and overdue days from given articleStep object
     * @param \DateTime $date1
     * @param \DateTime $date2
     * @param Boolean $both if true return both daysRemaining and daysOverdue values in an array
     * @return string | array  a string value formatted like +12 or -12 |  an array of remaining and overdue days count
     */
    public static function calculateDaysDiff($date1, $date2, $both = false)
    {
        $dateDiffRemaining = $date1->diff($date2);
        if ($both) {
            $daysRemaining = $dateDiffRemaining->format('%R') == '+' || $dateDiffRemaining->format('%a') == 0 ?
                    $dateDiffRemaining->format('%a') :
                    false;
            $daysOverDue = $dateDiffRemaining->format('%R') == '-' ?
                    $dateDiffRemaining->format('%a') :
                    false;
            return array($daysRemaining, $daysOverDue);
        }
        return $dateDiffRemaining->format('%R%a');
    }

}
