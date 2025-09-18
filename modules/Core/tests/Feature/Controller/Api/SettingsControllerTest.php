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

namespace Modules\Core\Tests\Feature\Controller\Api;

use Modules\Core\Settings\DefaultSettings;
use Tests\TestCase;

class SettingsControllerTest extends TestCase
{
    public function test_unauthenticated_user_cannot_access_settings_endpoints(): void
    {
        $this->postJson('/api/settings')->assertUnauthorized();
        $this->getJson('/api/settings')->assertUnauthorized();
    }

    public function test_unauthorized_cannot_access_settings_endpoint(): void
    {
        $this->asRegularUser()->signIn();

        $this->postJson('/api/settings')->assertForbidden();
        $this->getJson('/api/settings')->assertForbidden();
    }

    public function test_all_settings_can_be_retrieved(): void
    {
        $this->signIn();

        $this->getJson('/api/settings')->assertOk()->assertJson(settings()->all());
    }

    public function test_user_can_update_settings(): void
    {
        $this->signIn();

        // Create new test setting
        settings()->set('-test-key-', 'test-value')->save();

        $this->postJson('/api/settings', [
            '-test-key-' => 'updated-test-value',
        ])->assertOk();

        $this->assertEquals('updated-test-value', settings()->get('-test-key-'));
    }

    public function test_new_setting_is_created_when_not_exist_in_database_but_exist_in_request_payload(): void
    {
        $this->signIn();

        $this->postJson('/api/settings', [
            '-test-key-' => 'new-test-value',
        ])->assertOk();

        $this->assertEquals('new-test-value', settings()->get('-test-key-'));
    }

    public function test_required_settings_are_not_updated_when_empty_value_is_passed(): void
    {
        $this->signIn();

        $required = DefaultSettings::getRequired();

        $randomKey = array_rand($required);
        $valueBeforeToTryToUpdate = settings()->get($required[$randomKey]);

        $this->postJson('/api/settings', [
            $required[$randomKey] => '',
        ])->assertOk();

        $this->assertEquals($valueBeforeToTryToUpdate, settings()->get($required[$randomKey]));
    }
}
