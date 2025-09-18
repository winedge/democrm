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

namespace Modules\Installer\Tests\Feature;

use Akaunting\Money\Currency;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Mockery;
use Mockery\MockInterface;
use Modules\Core\Facades\Innoclapps;
use Modules\Core\Models\Country;
use Modules\Core\Settings\DefaultSettings;
use Modules\Installer\Environment;
use Modules\Installer\EnvironmentManager;
use Modules\Installer\Events\InstallationSucceeded;
use Modules\Installer\Http\Controllers\InstallController;
use Modules\Installer\Installer;
use Modules\Installer\PermissionsChecker;
use Modules\Installer\RequirementsChecker;
use Modules\Updater\DatabaseMigrator;
use Modules\Users\Models\User;
use Tests\TestCase;

class InstallControllerTest extends TestCase
{
    public function test_it_shows_the_requirements_step(): void
    {
        $requirements = new RequirementsChecker;

        $this->get('/install')
            ->assertOk()
            ->assertViewIs('installer::requirements')
            ->assertViewHas('step', 1)
            ->assertViewHas('php', $requirements->checkPHPversion())
            ->assertViewHas('requirements', $requirements->check())
            ->assertSee('<a href="'.url('install/permissions').'"', false);
    }

    public function test_it_shows_error_when_requirements_fails(): void
    {
        $checksReturn = (new RequirementsChecker)->check();
        $checksReturn['errors'] = true;

        $this->instance(
            RequirementsChecker::class,
            Mockery::mock(new RequirementsChecker, function (MockInterface $mock) use ($checksReturn) {
                $mock->shouldReceive('check')->andReturn($checksReturn);
            })->makePartial()
        );

        $this->get('/install')
            ->assertOk()
            ->assertSeeText('Please fix the requirements to proceed further with the installation process.')
            ->assertDontSeeText('Next');
    }

    public function test_it_shows_the_permissions_step(): void
    {
        $permissions = new PermissionsChecker;

        $this->get('/install/permissions')
            ->assertOk()
            ->assertViewIs('installer::permissions')
            ->assertViewHas('step', 2)
            ->assertViewHas('permissions', $permissions->check())
            ->assertSee('<a href="'.url('install/setup').'"', false);
    }

    public function test_it_shows_error_when_permissions_fails(): void
    {
        $checksReturn = [
            'errors' => true,
            'results' => [],
        ];

        $this->instance(
            PermissionsChecker::class,
            Mockery::mock(new PermissionsChecker, function (MockInterface $mock) use ($checksReturn) {
                $mock->shouldReceive('check')->andReturn($checksReturn);
            })->makePartial()
        );

        $this->get('/install/permissions')
            ->assertOk()
            ->assertSeeText('Please fix the requirements to proceed further with the installation process.')
            ->assertDontSeeText('Next');
    }

    public function test_it_shows_the_setup_step(): void
    {
        $_SERVER['HTTPS'] = 'off';
        $_SERVER['HTTP_HOST'] = 'example.com';
        $_SERVER['SCRIPT_NAME'] = 'index.php';

        $this->get('/install/setup')
            ->assertOk()
            ->assertViewIs('installer::setup')
            ->assertViewHas('step', 3)
            ->assertViewHas('guessedUrl', 'http://example.com')
            ->assertViewHas('countries', Country::list())
            ->assertViewHas('currencies', Currency::getCurrencies())
            ->assertSee('Test Connection & Configure');
    }

    public function test_setup_store_fails_validation_and_redirects_with_errors(): void
    {
        $this
            ->post('/install/setup', [
                'app_url' => '',
                'app_name' => '',
                'currency' => '',
                'country' => '',
                'database_hostname' => '',
                'database_port' => '',
                'database_name' => '',
                'database_username' => '',
            ])
            ->assertSessionHasErrors([
                'app_url',
                'app_name',
                'currency',
                'country',
                'database_hostname',
                'database_port',
                'database_name',
                'database_username',
            ])
            ->assertRedirectToRoute('installer.setup')
            ->assertSessionHasErrors();
    }

    public function test_setup_store_doesnt_requires_database_password(): void
    {
        $this
            ->post('/install/setup', [
                'app_url' => '',
                'app_name' => '',
                'currency' => '',
                'country' => '',
                'database_hostname' => '',
                'database_port' => '',
                'database_name' => '',
                'database_username' => '',
            ])
            ->assertSessionDoesntHaveErrors('database_password');
    }

    public function test_it_performs_setup(): void
    {
        $this->instance(
            EnvironmentManager::class,
            Mockery::mock(new EnvironmentManager, function (MockInterface $mock) {
                $mock->shouldReceive('saveEnvFile')
                    ->withArgs(function (Environment $env) {
                        $this->assertEquals(config('app.key'), $env->getKey());
                        $this->assertEquals(config('core.key'), $env->getIdentificationKey());
                        $this->assertEquals('https://example.com', $env->getUrl());
                        $this->assertEquals('Concord CRM', $env->getName());
                        $this->assertEquals('localhost', $env->getDbHost());
                        $this->assertEquals('3306', $env->getDbPort());
                        $this->assertEquals('testing', $env->getDbName());
                        $this->assertEquals('laravel', $env->getDbUser());

                        return true;
                    })
                    ->andReturn(true);
            })->makePartial()
        );

        $this
            ->post('/install/setup', [
                'app_url' => 'https://example.com',
                'app_name' => 'Concord CRM',
                'currency' => 'USD',
                'country' => '1',
                'database_hostname' => 'localhost',
                'database_port' => '3306',
                'database_name' => 'testing',
                'database_username' => 'laravel',
            ])
            ->assertSessionHas('install_currency', 'USD')
            ->assertSessionHas('install_country', 1)
            ->assertRedirect('https://example.com/install/database');
    }

    public function test_it_can_fail_to_write_environment_file(): void
    {
        $this->instance(
            EnvironmentManager::class,
            Mockery::mock(new EnvironmentManager, function (MockInterface $mock) {
                $mock->shouldReceive('saveEnvFile')->andReturn(false);
            })->makePartial()
        );

        $this
            ->post('/install/setup', [
                'app_url' => 'https://example.com',
                'app_name' => 'Concord CRM',
                'currency' => 'USD',
                'country' => '1',
                'database_hostname' => 'localhost',
                'database_port' => '3306',
                'database_name' => 'testing',
                'database_username' => 'laravel',
            ])
            ->assertRedirectToRoute('installer.setup')
            ->assertSessionHasErrors([
                'general' => InstallController::ENV_WRITE_FAILED_MESSAGE,
            ]);
    }

    public function test_it_migrates_the_database(): void
    {
        $this->app->bind(DatabaseMigrator::class, function () {
            return $this->partialMock(DatabaseMigrator::class, function (MockInterface $mock) {
                $mock->shouldReceive('run');
            });
        });

        $this->get('/install/database')->assertRedirectToRoute('installer.user');
    }

    public function test_it_shows_the_user_step(): void
    {
        $this->get('/install/user')
            ->assertOk()
            ->assertViewIs('installer::user')
            ->assertViewHas('step', 4)
            ->assertSeeTextInOrder([
                'Configure Admin User',
                'Name (Full Name)',
                'E-Mail Address',
                'Timezone',
                'Password',
                'Confirm Password',
                'Install',
            ]);
    }

    public function test_user_store_fails_validation_and_redirects_with_errors(): void
    {
        $this
            ->post('/install/user', [
                'name' => '',
                'email' => 'invalid-email',
                'timezone' => 'invalid-timezone',
                'password' => 'short',
                'password_confirmation' => 'mismatch',
            ])
            ->assertSessionHasErrors(['name', 'email', 'timezone', 'password'])
            ->assertRedirectToRoute('installer.user')
            ->assertSessionHasErrors();
    }

    public function test_user_can_be_stored(): void
    {
        $this->post('/install/user', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'timezone' => 'UTC',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirectToRoute('installer.finalize');

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'timezone' => 'UTC',
            'email' => 'test@example.com',
            'super_admin' => true,
            'access_api' => true,
            'time_format' => DefaultSettings::get('time_format'),
            'date_format' => DefaultSettings::get('date_format'),
        ]);
    }

    public function test_it_finalizes_installation(): void
    {
        Event::fake();

        $captureEnvDate = Carbon::parse(date('Y-m-d H:i:s'));
        Carbon::setTestNow($captureEnvDate);

        Innoclapps::spy();

        Innoclapps::shouldReceive('createStorageLink')->once()->andReturn(true);

        $this->instance(
            Installer::class,
            Mockery::mock(Installer::class, function (MockInterface $mock) {
                $mock->shouldReceive('markAsInstalled')->once()->andReturn(true);
            })
        );

        Innoclapps::shouldReceive('optimize');

        session(['install_currency' => 'MKD', 'install_country' => '1']);

        $this->get('/install/finalize')
            ->assertRedirectToSignedRoute('installer.finished')
            ->assertSessionMissing(['install_currency', 'install_country']);

        Event::assertDispatched(InstallationSucceeded::class, function (InstallationSucceeded $event) {
            return count($event->getErrors()) === 0;
        });

        $this->assertSame('MKD', settings('currency'));
        $this->assertSame('1', settings('company_country_id'));
        $this->assertSame($captureEnvDate->toISOString(), settings('_env_captured_at'));
    }

    public function test_it_does_not_dispatch_installation_succeeded_event_if_fails_to_create_installation_file(): void
    {
        Event::fake();

        Innoclapps::spy();

        Innoclapps::shouldReceive('createStorageLink')->once()->andReturn(true);

        $this->instance(
            Installer::class,
            Mockery::mock(Installer::class, function (MockInterface $mock) {
                $mock->shouldReceive('markAsInstalled')->once()->andReturn(false);
                $mock->shouldReceive('installedFileLocation')->once()->andReturn(storage_path('.installed'));
            })
        );

        $this->get('/install/finalize')
            ->assertRedirectToSignedRoute('installer.finished')
            ->assertSessionHasErrors(['general' => 'Failed to create the installed file. ('.storage_path('.installed').').']);

        Event::assertNotDispatched(InstallationSucceeded::class);
    }

    public function test_it_may_fail_to_create_storage_link(): void
    {
        Event::fake();

        Innoclapps::spy();
        Innoclapps::shouldReceive('createStorageLink')->once()->andThrow(new \Exception('E'));

        $this->instance(
            Installer::class,
            Mockery::mock(Installer::class, function (MockInterface $mock) {
                $mock->shouldReceive('markAsInstalled')->once()->andReturn(true);
            })
        );

        $this->get('/install/finalize')
            ->assertRedirectToSignedRoute('installer.finished')
            ->assertSessionHasErrors(['general' => 'Failed to create storage symlink.']);
    }

    public function test_it_shows_the_finished_step(): void
    {
        $user = User::factory()->create();

        $this->get('/install/finished')
            ->assertOk()
            ->assertViewIs('installer::finish')
            ->assertViewHas('step', 5)
            ->assertViewHas('user', $user)
            ->assertViewHas('minPHPVersion', config('installer.core.minPhpVersion'))
            ->assertSeeText('Installation Successfull')
            ->assertSeeText($user->email)
            ->assertSeeText('Login');
    }

    public function test_finished_step_requires_signed_url_when_in_production(): void
    {
        config(['app.env' => 'production']);

        $this->get('/install/finished')->assertUnauthorized();
    }

    public function test_install_routes_are_not_available_when_already_installed(): void
    {
        config(['app.env' => 'production']);

        app(Installer::class)->markAsInstalled();

        $this->get('/install')->assertNotFound();
        $this->get('/install/permissions')->assertNotFound();
        $this->get('/install/setup')->assertNotFound();
        $this->post('/install/setup')->assertNotFound();
        $this->get('/install/user')->assertNotFound();
        $this->post('/install/user')->assertNotFound();
        $this->get('/install/database')->assertNotFound();
        $this->get('/install/finalize')->assertNotFound();
    }
}
