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

namespace Modules\Core\Tests\Feature\Models;

use Illuminate\Support\Facades\Lang;
use Modules\Core\Models\CustomField;
use Modules\Core\Models\CustomFieldOption;
use Tests\TestCase;

class CustomFieldOptionTest extends TestCase
{
    public function test_custom_field_option_has_field(): void
    {
        $field = $this->makeField();
        $field->save();

        $option = new CustomFieldOption(['name' => 'Option 1', 'display_order' => 1]);
        $field->options()->save($option);

        $this->assertInstanceof(CustomField::class, $option->field);
    }

    public function test_custom_field_option_can_be_translated_with_custom_group(): void
    {
        $field = tap($this->makeField())->save();
        $field->options()->save($option = new CustomFieldOption(['name' => 'Original', 'display_order' => 1]));

        Lang::addLines(['custom.custom_field.options.'.$option->id => 'Changed'], 'en');

        $this->assertSame('Changed', $option->name);
    }

    public function test_lost_reason_can_be_translated_with_lang_key(): void
    {
        $field = tap($this->makeField())->save();
        $field->options()->save($option = new CustomFieldOption(['name' => 'custom.custom_field.options.some', 'display_order' => 1]));

        Lang::addLines(['custom.custom_field.options.some' => 'Changed'], 'en');

        $this->assertSame('Changed', $option->name);
    }

    public function test_it_uses_database_name_when_no_custom_trans_available(): void
    {
        $field = tap($this->makeField())->save();
        $field->options()->save($option = new CustomFieldOption(['name' => 'Database Name', 'display_order' => 1]));

        $this->assertSame('Database Name', $option->name);
    }

    protected function makeField($attrs = [])
    {
        return new CustomField(array_merge([
            'field_id' => 'field_id',
            'field_type' => 'Text',
            'resource_name' => 'resource',
            'label' => 'Label',
        ], $attrs));
    }
}
