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

namespace Modules\Core\Fields;

use Modules\Core\Http\Requests\ResourceRequest;
use Modules\Core\Models\Model;
use Modules\Core\Table\Column;

class VisibilityGroup extends Field
{
    /**
     * @var bool|callable
     */
    public $applicableForIndex = false;

    /**
     * @var bool|callable
     */
    public $applicableForDetail = false;

    /**
     * Indicates whether this field is excluded from setttings.
     */
    public bool|string|array $excludeFromSettings = true;

    /**
     * Indicates whether to exclude the field from import.
     */
    public bool $excludeFromImport = true;

    /**
     * Indicates whether the field should be included in sample data.
     */
    public bool $excludeFromImportSample = true;

    /**
     * Indicates whether to exclude the field from export.
     */
    public bool $excludeFromExport = true;

    /**
     * Indicates whether the field is excluded from Zapier response.
     */
    public bool $excludeFromZapierResponse = true;

    /**
     * Field component.
     */
    protected static $component = 'visibility-group-field';

    /**
     * Additional relationships to eager load when quering the resource.
     */
    public array $with = ['visibilityGroup.teams', 'visibilityGroup.users'];

    /**
     * Indicates if the field is searchable.
     */
    protected bool $searchable = false;

    /**
     * Initialize new VisibilityGroup instance class.
     */
    public function __construct()
    {
        parent::__construct(...func_get_args());

        $this->rules(['nullable', 'array'])
            ->fillUsing(function (Model $model, string $attribute, ResourceRequest $request, mixed $value, string $requestAttribute) {
                return function () use ($model, $value) {
                    if (! is_null($value)) {
                        $model->saveVisibilityGroup($value);
                    }
                };
            })->resolveForJsonResourceUsing(function (Model $model, string $attribute) {
                if ($model->relationLoaded('visibilityGroup')) {
                    return [$attribute => $model->visibilityGroupData()];
                }
            });
    }

    /**
     * Get the mailable template placeholder.
     *
     * @param  \Modules\Core\Models\Model|null  $model
     */
    public function mailableTemplatePlaceholder($model)
    {
        return null;
    }

    /**
     * Provide the column used for index.
     *
     * @return null
     */
    public function indexColumn(): ?Column
    {
        return null;
    }

    /**
     * Resolve the displayable field value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     */
    public function resolveForDisplay($model)
    {
        return null;
    }

    /**
     * Resolve the field value for export.
     *
     * @param  \Modules\Core\Models\Model  $model
     */
    public function resolveForExport($model)
    {
        return null;
    }
}
