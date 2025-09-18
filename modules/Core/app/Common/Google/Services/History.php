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

namespace Modules\Core\Common\Google\Services;

use Google\Client;

class History extends Service
{
    /**
     * Initialize new History service instance.
     */
    public function __construct(Client $client)
    {
        parent::__construct($client, \Google\Service\Gmail::class);
    }

    /**
     * https://developers.google.com/gmail/api/v1/reference/users/history/list
     *
     * Get the Gmail account history
     *
     * @param  array  $params  Additional params for the request
     * @return \Google\Service\Gmail\History
     */
    public function get($params = [])
    {
        /** @var \Google\Service\Gmail\History */
        $service = $this->service;

        return $service->users_history->listUsersHistory('me', $params);
    }
}
