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

namespace Modules\Core\Http\Requests;

use Modules\Core\Fields\FieldsCollection;

class CreateResourceRequest extends ResourceRequest
{
    use InteractsWithResourceFields;

    /**
     * Get the fields for the current request.
     */
    public function fields(): FieldsCollection
    {
        return $this->resource()->fieldsForCreate()->withoutReadonly();
    }
}
