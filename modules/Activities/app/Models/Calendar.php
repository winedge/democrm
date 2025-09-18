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

namespace Modules\Activities\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Activities\Calendar\CalendarSyncManager;
use Modules\Activities\Database\Factories\CalendarFactory;
use Modules\Core\Common\OAuth\EmptyRefreshTokenException;
use Modules\Core\Common\Synchronization\Synchronizable;
use Modules\Core\Models\Model;
use Modules\Core\Models\OAuthAccount;
use Modules\Users\Models\User;

class Calendar extends Model
{
    use HasFactory, Synchronizable;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'activity_types' => 'array',
        'data' => 'array',
        'user_id' => 'int',
        'activity_type_id' => 'int',
        'access_token_id' => 'int',
    ];

    /**
     * An email account can belongs to a user (personal)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\Modules\Users\Models\User::class);
    }

    /**
     * A model has OAuth connection
     */
    public function oAuthAccount(): HasOne
    {
        return $this->hasOne(OAuthAccount::class, 'id', 'access_token_id');
    }

    /**
     * Get the calendar synchronizer class
     *
     * @return \Modules\Core\Contracts\Synchronization\Synchronizable
     */
    public function synchronizer()
    {
        return CalendarSyncManager::createClient($this);
    }

    /**
     * Check whether the actual calendar is synchronized via webhook
     */
    public function isSynchronizingViaWebhook(): bool
    {
        return $this->synchronization->isSynchronizingViaWebhook();
    }

    /**
     * Synchronize the calendar events
     */
    public function synchronize(): void
    {
        try {
            $this->synchronizer()->synchronize($this->synchronization);
        } catch (EmptyRefreshTokenException) {
            $this->synchronization->stopSync(
                'The sync for this calendar is disabled because the '.$this->email.' account has empty refresh token, try to remove the app from your '.explode('@', $this->email)[1].' account connected apps section and re-connect again.'
            );
        }
    }

    /**
     * Get the date the events should be synced from
     *
     * @return \Illuminate\Support\Carbon
     */
    public function startSyncFrom()
    {
        return $this->synchronization->start_sync_from;
    }

    /**
     * Get the connectionType attribute
     */
    protected function connectionType(): Attribute
    {
        return Attribute::get(
            fn () => match ($this->oAuthAccount->type) {
                'microsoft' => 'outlook',
                default => $this->oAuthAccount->type,
            }
        );
    }

    /**
     * Find active calendar for the given user.
     */
    public static function findActiveFor(User $user): ?Calendar
    {
        return static::whereHas('user', function ($query) use ($user) {
            return $query->where('user_id', $user->getKey());
        })->whereHas('synchronization', function ($query) {
            return $query->notDisabled();
        })->orderBy('created_at')->first();
    }

    /**
     * Disable the sync for the calendar.
     */
    public function disableSync(): static
    {
        $this->synchronization->disableSync();

        return $this;
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): CalendarFactory
    {
        return CalendarFactory::new();
    }
}
