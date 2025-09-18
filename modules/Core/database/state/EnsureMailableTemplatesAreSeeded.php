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

namespace Modules\Core\Database\State;

use Modules\Core\Facades\MailableTemplates;

class EnsureMailableTemplatesAreSeeded
{
    public function __invoke(): void
    {
        MailableTemplates::seed();
    }
}
