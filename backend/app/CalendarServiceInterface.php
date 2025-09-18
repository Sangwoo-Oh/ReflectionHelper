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

    public function getCalendarEtag(): string;

    public function getListEventsEtag(): string;

    public function loadAllEvents(): void;

    public function loadDeltaEvents(): void;
}
