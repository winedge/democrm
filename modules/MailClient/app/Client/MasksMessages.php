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

namespace Modules\MailClient\Client;

use Illuminate\Support\Collection;

trait MasksMessages
{
    /**
     * Mask given messages into a given class
     *
     * @param  array|\Illuminate\Support\Collection  $messages
     * @param  string  $maskIntoClass
     * @return \Illuminate\Support\Collection
     */
    protected function maskMessages($messages, $maskIntoClass)
    {
        if (! $messages) {
            $messages = [];
        }

        if (! $messages instanceof Collection) {
            $messages = collect($messages);
        }

        return $messages->map(function ($message) use ($maskIntoClass) {
            return $this->maskMessage($message, $maskIntoClass);
        });
    }

    /**
     * Mask a given message
     *
     * @param  mixed  $message
     * @param  string  $maskIntoClass
     * @return \Modules\MailClient\Client\Contracts\MessageInterface
     */
    protected function maskMessage($message, $maskIntoClass)
    {
        return new $maskIntoClass($message);
    }
}
