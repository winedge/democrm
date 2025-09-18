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

namespace Modules\Core\Contracts\Synchronization;

use Modules\Core\Models\Synchronization;

interface Synchronizable
{
    /**
     * Synchronize the data for the given synchronization
     */
    public function synchronize(Synchronization $synchronization): void;
}
