<?php

namespace App;

interface CalendarServiceInterface
{
    public function listEvents(
        string $query = '',
        \DateTime $startDate = null,
        \DateTime $endDate = null,
        string $calendarId = 'primary'
    ): array;
}
