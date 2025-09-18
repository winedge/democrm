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
use Illuminate\Http\Request;
use Modules\Core\Facades\Fields;
use Modules\Core\Http\Controllers\ApiController;

class FieldSettingsController extends ApiController
{
    /**
     * Get the group and view fields that are intended for the settings.
     */
    public function settings(string $group, string $view): JsonResponse
    {
        return $this->response(
            Fields::getForSettings($group, $view)
        );
    }

    /**
     * Get the view fields for settings in bulk for the given resources.
     */
    public function bulkSettings(string $view, Request $request): JsonResponse
    {
        return $this->response(
            $request->collect('groups')->mapWithKeys(
                fn ($group) => [$group => Fields::getForSettings($group, $view)]
            )
        );
    }

    /**
     * Save the user customized fields group for the given view.
     */
    public function update(string $group, string $view, Request $request): JsonResponse
    {
        Fields::customize($request->post(), $group, $view);

        return $this->response('', JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Reset the user customized group fields for the given view.
     */
    public function destroy(string $group, string $view): JsonResponse
    {
        Fields::customize([], $group, $view);

        return $this->response(
            Fields::getForSettings($group, $view)
        );
    }
}
