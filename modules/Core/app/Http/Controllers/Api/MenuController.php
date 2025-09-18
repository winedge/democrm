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

namespace Modules\Core\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Modules\Core\Facades\Menu;
use Modules\Core\Http\Controllers\ApiController;

class MenuController extends ApiController
{
    /**
     * Get the application menu metrics.
     */
    public function metrics(): JsonResponse
    {
        return $this->response(Menu::metrics());
    }
}
