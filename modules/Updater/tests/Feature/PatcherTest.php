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

use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Response as LaravelResponse;
use Modules\Updater\Exceptions\HasWrongPermissionsException;
use Modules\Updater\Exceptions\InvalidPurchaseKeyException;
use Modules\Updater\Exceptions\PurchaseKeyEmptyException;
use Modules\Updater\Exceptions\UpdaterException;
use Modules\Updater\Patch;
use Modules\Updater\Patcher;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Component\Finder\Finder;
use Tests\TestCase;

#[Group('updater')]
class PatcherTest extends TestCase
{
    use TestsUpdater;

    public function test_patches_can_be_retrieved(): void
    {
        $patcher = $this->createPatcherInstance([
            new Response(200, [], $patches = $this->patcherResponse()),
        ]);

        $this->assertCount(count(json_decode($patches, true)), $patcher->getAvailablePatches());
    }

    public function test_a_patch_can_be_fetched(): void
    {
        $path = $this->createZipFromFixtureFiles();

        $patcher = $this->createPatcherInstance([
            new Response(200, [], $this->patcherResponse()),
            new Response(200, [], file_get_contents($path)),
        ]);

        $patches = $patcher->getAvailablePatches();
        $patch = $patches[0];

        $patcher->fetch($patch);

        $this->assertTrue($patch->archive()->exists());
    }

    public function test_a_patch_can_be_fetched_by_token(): void
    {
        $path = $this->createZipFromFixtureFiles();

        $patcher = $this->createPatcherInstance([
            new Response(200, [], $this->patcherResponse()),
            new Response(200, [], file_get_contents($path)),
        ]);

        $patches = $patcher->getAvailablePatches();
        $patch = $patches[0];

        $patcher->fetch($patch->token());

        $this->assertTrue($patch->archive()->exists());
    }

    public function test_a_patch_can_be_applied(): void
    {
        $path = $this->createZipFromFixtureFiles();

        $patcher = $this->createPatcherInstance([
            new Response(200, [], $this->patcherResponse()),
            new Response(200, [], file_get_contents($path)),
        ]);

        $patches = $patcher->getAvailablePatches();
        $patcher->apply($patches[0]);
        $this->assertFileExists(config_path('test-config.php'));
        $this->assertFileExists(base_path('test-root-file.php'));
        $this->assertFileExists(base_path('routes/test-routes.php'));
    }

    public function test_a_patch_can_be_applied_by_token(): void
    {
        $path = $this->createZipFromFixtureFiles();

        $patcher = $this->createPatcherInstance([
            new Response(200, [], $this->patcherResponse()),
            new Response(200, [], file_get_contents($path)),
        ]);

        $patches = $patcher->getAvailablePatches();
        $patcher->apply($patches[0]->token());
        $this->assertFileExists(config_path('test-config.php'));
        $this->assertFileExists(base_path('test-root-file.php'));
        $this->assertFileExists(base_path('routes/test-routes.php'));
    }

    public function test_it_can_find_patch_by_token(): void
    {
        $patcher = $this->createPatcherInstance([
            new Response(200, [], json_encode([
                [
                    'date' => '2021-08-24T18:52:54.000000Z',
                    'description' => 'Fixes issue with activities',
                    'token' => $token = '96671235-ddb3-40ab-8ab9-3ca5df8de6b7',
                    'version' => '1.0.0',
                ],
            ])),
        ]);

        $patch = $patcher->find($token);

        $this->assertInstanceOf(Patch::class, $patch);
        $this->assertSame($token, $patch->token());
    }

    public function test_it_does_not_download_patch_if_the_archive_exists(): void
    {
        $token = '96671235-ddb3-40ab-8ab9-3ca5df8de6b7';
        $this->createZipFromFixtureFiles(storage_path('updater/'.$token.'.zip'));

        $patcher = $this->createPatcherInstance([
            new Response(200, [], json_encode([
                [
                    'date' => '2021-08-24T18:52:54.000000Z',
                    'description' => 'Fixes issue with activities',
                    'token' => $token,
                    'version' => '1.0.0',
                ],
            ])),
        ]);

        try {
            $patcher->fetch($token);
            $this->assertTrue($patcher->find($token)->archive()->exists());
        } catch (\OutOfBoundsException $e) {
            $this->assertFalse(true, 'A patch was fetched, but it was not supposed to.');
        }
    }

    public function test_purchase_key_is_added_as_bearer_authorization_header_in_request(): void
    {
        $path = $this->createZipFromFixtureFiles();

        $purchaseKey = 'f327aafb-21af-4ddc-9148-a82b7b7dd027';

        config(['updater.purchase_key' => $purchaseKey]);

        $patcher = $this->createPatcherInstance([
            new Response(200, [], $this->patcherResponse()),
            new Response(200, [], file_get_contents($path)),
        ]);

        $patches = $patcher->getAvailablePatches();
        $patcher->fetch($patches[0]);

        $this->assertEquals(
            'Bearer '.$purchaseKey,
            $this->guzzleMock->getLastRequest()->getHeaders()['Authorization'][0]
        );
    }

    public function test_patch_cannot_be_applied_to_other_versions(): void
    {
        $patcher = $this->createPatcherInstance([
            new Response(200, [], json_encode([
                [
                    'date' => '2021-08-24T18:52:54.000000Z',
                    'description' => 'Fixes issue with activities',
                    'token' => '96671235-ddb3-40ab-8ab9-3ca5df8de6b7',
                    'version' => '1.2.0',
                ],
            ])),
        ]);

        $this->expectException(UpdaterException::class);
        $this->expectExceptionCode(LaravelResponse::HTTP_CONFLICT);
        $this->expectExceptionMessage('This patch does not belongs to the current version.');

        $patch = $patcher->find('96671235-ddb3-40ab-8ab9-3ca5df8de6b7');
        $patcher->apply($patch);
    }

    public function test_cannot_apply_patch_with_invalid_files_permissions(): void
    {
        Patcher::checkPermissionsUsing(fn () => false);

        $path = $this->createZipFromFixtureFiles();

        $patcher = $this->createPatcherInstance([
            new Response(200, [], $this->patcherResponse()),
            new Response(200, [], file_get_contents($path)),
        ]);

        $patches = $patcher->getAvailablePatches();
        $this->expectException(HasWrongPermissionsException::class);

        $patcher->apply($patches[0]);
    }

    public function test_exception_is_thrown_when_no_patches_url_provided(): void
    {
        $this->expectException(UpdaterException::class);

        $this->createPatcherInstance(new Response, ['patches_url' => ''])->getAvailablePatches();
    }

    public function test_it_can_download_patch(): void
    {
        $path = $this->createZipFromFixtureFiles();

        $patcher = $this->createPatcherInstance([
            new Response(200, [], $this->patcherResponse()),
            new Response(200, [], file_get_contents($path)),
        ]);

        $patch = $patcher->getAvailablePatches()[0];
        $patcher->fetch($patch);

        $this->assertFileExists($patch->getStoragePath());
    }

    public function test_cannot_fetch_patch_when_purchase_key_is_invalid(): void
    {
        $this->expectException(InvalidPurchaseKeyException::class);

        $patcher = $this->createPatcherInstance([
            new Response(200, [], $this->patcherResponse()),
            new Response(Patcher::INVALID_PURCHASE_KEY_CODE),
        ]);

        $patcher->fetch($patcher->getAvailablePatches()[0]);
    }

    public function test_cannot_fetch_patch_when_purchase_key_is_empty(): void
    {
        $this->expectException(PurchaseKeyEmptyException::class);

        $patcher = $this->createPatcherInstance([
            new Response(200, [], $this->patcherResponse()),
            new Response(Patcher::PURCHASE_KEY_EMPTY_CODE),
        ]);

        $patcher->fetch($patcher->getAvailablePatches()[0]);
    }

    public function test_can_direct_download_patch(): void
    {
        $patcher = $this->createPatcherInstance([
            new Response(200, [], $this->patcherResponse()),
            new Response(200, [], $this->patcherResponse()),
        ]);

        $patches = $patcher->getAvailablePatches();

        $expectedResponseToInclude = "Content-Disposition: attachment; filename=v1.0.0-{$patches[0]->token()}.zip";

        $this->assertTrue(
            str_contains((string) $patcher->download($patches[0]->token()), $expectedResponseToInclude)
        );
    }

    public function test_cannot_direct_download_patch_when_purchase_key_is_invalid(): void
    {
        $this->expectException(InvalidPurchaseKeyException::class);

        $patcher = $this->createPatcherInstance([
            new Response(200, [], $this->patcherResponse()),
            new Response(Patcher::INVALID_PURCHASE_KEY_CODE),
        ]);

        $patcher->download($patcher->getAvailablePatches()[0]->token());
    }

    public function test_cannot_direct_download_patch_when_purchase_key_is_empty(): void
    {
        $this->expectException(PurchaseKeyEmptyException::class);

        $patcher = $this->createPatcherInstance([
            new Response(200, [], $this->patcherResponse()),
            new Response(Patcher::PURCHASE_KEY_EMPTY_CODE),
        ]);

        $patcher->download($patcher->getAvailablePatches()[0]->token());
    }

    protected function fixtureFilesPath()
    {
        return module_path('Core', 'tests/Fixtures/patch');
    }

    protected function zipPathForFixtureFiles()
    {
        return storage_path('updater/patch.zip');
    }

    protected function setUp(): void
    {
        parent::setUp();
        // Provide fake Finder to the actual fixtures to avoid looping over thousands of files
        // to check whether they have the necessary permissions
        Patcher::providePermissionsCheckerFinderUsing(function ($path) {
            return (new Finder)->in(module_path('Core', 'tests/Fixtures/patch'));
        });

        $this->cleanFixturesFiles();
    }
}
