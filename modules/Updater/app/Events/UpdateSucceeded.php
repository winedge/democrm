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

namespace Modules\Updater\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Modules\Updater\Release;

class UpdateSucceeded
{
    use Dispatchable, InteractsWithSockets;

    /**
     * Initialize new UpdateSucceeded instance.
     */
    public function __construct(public Release $release) {}

    /**
     * Get the release.
     */
    public function getRelease(): Release
    {
        return $this->release;
    }

    /**
     * Get the version number the installation was updated to.
     */
    public function getVersion(): string
    {
        return $this->release->getVersion();
    }
}
