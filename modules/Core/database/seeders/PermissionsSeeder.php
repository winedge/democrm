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

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Facades\Permissions;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permissions::createMissing();
    }
}
