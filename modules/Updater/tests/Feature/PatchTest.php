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

namespace Modules\Updater\Tests\Feature;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Carbon;
use Modules\Updater\Patch;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('updater')]
class PatchTest extends TestCase
{
    public function test_can_determine_whether_patch_is_applied(): void
    {
        $patch = app(Patch::class, ['patch' => $this->createPatch()]);

        $this->assertFalse($patch->isApplied());

        $patch->markAsApplied();

        $this->assertTrue($patch->isApplied());
    }

    public function test_patch_has_token(): void
    {
        $patch = app(Patch::class, ['patch' => $this->createPatch('some-token')]);

        $this->assertSame('some-token', $patch->token());
    }

    public function test_patch_has_to_array_for_frontend_serialization(): void
    {
        $patch = app(Patch::class, ['patch' => $this->createPatch()]);
        $patch->markAsApplied();

        $this->assertInstanceOf(Arrayable::class, $patch);

        $this->assertEquals([
            'description' => 'Fixes issue with activities',
            'date' => '2021-08-24T18:52:54.000000Z',
            'token' => '96671235-ddb3-40ab-8ab9-3ca5df8de6b7',
            'isApplied' => true,
            'isCritical' => true,
        ], $patch->toArray());
    }

    protected function createPatch($token = '96671235-ddb3-40ab-8ab9-3ca5df8de6b7')
    {
        $patch = new \stdClass;
        $patch->date = Carbon::parse('2021-08-24T18:52:54.000000Z');
        $patch->token = $token;
        $patch->description = 'Fixes issue with activities';
        $patch->version = '1.0.0';
        $patch->critical = true;

        return $patch;
    }
}
