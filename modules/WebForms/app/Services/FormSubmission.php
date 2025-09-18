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

namespace Modules\WebForms\Services;

use Illuminate\Support\Carbon;
use Modules\Core\Facades\Innoclapps;
use Modules\Core\Models\Changelog;

class FormSubmission
{
    /**
     * Initialize new FormSubmission instance.
     */
    public function __construct(protected Changelog $changelog) {}

    /**
     * Get the web form submission data.
     *
     * @return \Illuminate\Support\Collection
     */
    public function data()
    {
        return $this->changelog->properties;
    }

    /**
     * Parse the displayable value.
     *
     * @param  string|null  $value
     * @param  array  $property
     * @return string
     */
    protected function parseValue($value, $property)
    {
        if (! empty($value)) {
            if (isset($property['dateTime'])) {
                $value = Carbon::parse($value)->formatDateTimeForUser();
            } elseif (isset($property['date'])) {
                $value = Carbon::parse($value)->formatDateForUser();
            }
        }

        return $value !== null ? $value : '/';
    }

    /**
     * __toString
     */
    public function __toString(): string
    {
        $payload = '';

        foreach ($this->data() as $property) {
            $payload .= '<div>';
            $payload .= Innoclapps::resourceByName($property['resourceName'])->singularLabel();
            $payload .= '  '.'<span style="font-weight:bold;">'.$property['label'].'</span>';
            $payload .= '</div>';
            $payload .= '<p style="margin-top:5px;">';
            $payload .= $this->parseValue($property['value'], $property);
            $payload .= '</p>';
        }

        return $payload;
    }
}
