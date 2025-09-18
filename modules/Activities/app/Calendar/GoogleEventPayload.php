<?php
/**
 * Concord CRM - https://www.concordcrm.com
 *
 * @version   1.6.0
 *
 * @link      Releases - https://www.concordcrm.com/releases
 * @link      Terms Of Service - https://www.concordcrm.com/terms
 *
 * @copyright Copyright (c) 2022-2025 KONKORD DIGITAL
 */

namespace Modules\Activities\Calendar;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Carbon;
use Modules\Activities\Models\Activity;
use Modules\Core\Support\Makeable;

class GoogleEventPayload implements Arrayable
{
    use Makeable;

    /**
     * Initialize new GoogleEventPayload class
     */
    public function __construct(protected Activity $activity) {}

    /**
     * Get the event summary
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->activity->title;
    }

    /**
     * Get the event reminder minutes before start
     *
     * @return int|null
     */
    public function getReminders()
    {
        $minutes = $this->activity->reminder_minutes_before;

        // makes sure no reminders are configured for the event
        if (! $minutes) {
            return ['useDefault' => false];
        }

        return [
            'useDefault' => false,
            'overrides' => [
                ['method' => 'email', 'minutes' => $minutes],
                ['method' => 'popup', 'minutes' => $minutes],
            ],
        ];
    }

    /**
     * Get the event start date
     *
     * @return array
     */
    public function getStartDate()
    {
        $startDate = Carbon::parse($this->activity->full_due_date);
        $allDay = $this->activity->isAllDay();

        $dateKey = $allDay ? 'date' : 'dateTime';

        return [
            $dateKey => $allDay ? $startDate->format('Y-m-d') : $startDate->format('Y-m-d\TH:i:s'),
            'timeZone' => config('app.timezone'),
        ];
    }

    /**
     * Get the event end date
     *
     * @return array
     */
    public function getEndDate()
    {
        $endDate = Carbon::parse($this->activity->full_end_date);

        $allDay = $this->activity->isAllDay();

        if (! $this->activity->end_time && ! $allDay) {
            $startDate = Carbon::parse($this->activity->full_due_date);

            $endDate->seconds($startDate->second)->minutes($startDate->minute)->hours($startDate->hour);
        }

        $dateKey = $allDay ? 'date' : 'dateTime';

        return [
            $dateKey => $allDay ? $endDate->format('Y-m-d') : $endDate->format('Y-m-d\TH:i:s'),
            'timeZone' => config('app.timezone'),
        ];
    }

    /**
     * Get the event attendees
     *
     * @return array
     */
    public function getAttendees()
    {
        return $this->activity->guests->map(function ($guest) {
            return [
                'email' => $guest->guestable->getGuestEmail(),
                'displayName' => $guest->guestable->getGuestDisplayName(),
            ];
        })->all();
    }

    /**
     * Get the event description
     *
     * @return array
     */
    public function getDescription()
    {
        return $this->activity->description ?: '';
    }

    /**
     * toArray
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'summary' => $this->getSummary(),
            'description' => $this->getDescription(),
            'start' => $this->getStartDate(),
            'end' => $this->getEndDate(),
            'attendees' => $this->getAttendees(),
            'reminders' => $this->getReminders(),
        ];
    }
}
