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

namespace Modules\Contacts\Enums;

use Modules\Core\Support\InteractsWithEnums;

enum LeadStatus: int
{
    use InteractsWithEnums;

    case hot = 1;
    case warm = 2;
    case cold = 3;
    case customer = 4;
    case lost = 5;
    case won = 6;

    /**
     * Get the status label.
     */
    public function label(): string
    {
        return __('contacts::lead.status.'.$this->name);
    }

    /**
     * Get the lead status badge variant.
     */
    public function badgeVariant(): string
    {
        return self::badgeVariants()[$this->name];
    }

    /**
     * Get the available badge variants.
     */
    public static function badgeVariants(): array
    {
        return [
            LeadStatus::hot->name => 'danger',
            LeadStatus::warm->name => 'warning',
            LeadStatus::cold->name => 'info',
            LeadStatus::customer->name => 'success',
            LeadStatus::lost->name => 'neutral',
            LeadStatus::won->name => 'success',
        ];
    }

    /**
     * Check if the status is a final status (Lost or Won).
     */
    public function isFinalStatus(): bool
    {
        return in_array($this, [self::lost, self::won]);
    }

    /**
     * Check if the status requires admin permissions to change.
     */
    public function requiresAdminPermission(): bool
    {
        return in_array($this, [self::lost, self::won]);
    }
}
