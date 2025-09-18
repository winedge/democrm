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

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Modules\Core\Common\Changelog\LogsModelChanges;

abstract class Model extends EloquentModel
{
    /**
     * Get the fillable attributes for the model.
     *
     * @return array<string>
     */
    public function getFillable(): array
    {
        return apply_filters(
            'model.'.Str::snake(class_basename(static::class)).'.fillable', $this->fillable, $this
        );
    }

    /**
     * Scope a query to eager load common relationships.
     */
    public function scopeWithCommon(Builder $query): void
    {
        //
    }

    /**
     * Scope a query to apply order to null values to be sorted as last.
     */
    public function scopeOrderByNullsLast(Builder $query, string $column, string $direction = 'asc'): void
    {
        $query->orderByRaw(
            $this->getNullsLastSql($query, $column, $direction)
        );
    }

    /**
     * Scope a query to include trashed records if the model is using the "SoftDeletes" query.
     */
    public function scopeWithTrashedIfUsingSoftDeletes(Builder $query): void
    {
        if (static::usesSoftDeletes()) {
            $query->withTrashed();
        }
    }

    /**
     * Determine if the model is currently really deleting.
     */
    public function isReallyDeleting(): bool
    {
        return ! static::usesSoftDeletes() || $this->isForceDeleting();
    }

    /**
     * Determine if the model uses the SoftDeletes trait.
     */
    public static function usesSoftDeletes(): bool
    {
        return in_array(SoftDeletes::class, class_uses_recursive(static::class));
    }

    /**
     * Determine if the model tracks changes.
     */
    public static function logsModelChanges()
    {
        return in_array(LogsModelChanges::class, class_uses_recursive(static::class));
    }

    /**
     * Set a given attribute on the model.
     *
     * Laravel uses this method when filling attributes, we will check if the date instance
     * does not use the application default timezone (UTC) and if not, we will set it to UTC.
     * For example, Zapier for dates only provides dates in this format: 2018-03-02T00:00:00+01:00
     * and for date time, it provides e.q. 2023-11-24T19:00:00+01:00
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    public function setAttribute($key, $value)
    {
        $retval = parent::setAttribute($key, $value);

        if (! empty($value)) {
            if ($this->hasCast($key, ['datetime', 'immutable_datetime'])) {
                $dateTime = $this->asDateTime($value);

                if (strtolower($dateTime->timezone->getName()) !== strtolower(config('app.timezone'))) {
                    $dateTime->setTimezone(config('app.timezone'));
                    $this->attributes[$key] = $dateTime->format($this->getDateFormat());
                }
            } elseif ($this->hasCast($key, ['date', 'immutable_date'])) {
                $this->attributes[$key] = $this->asDateTime($value)->format('Y-m-d');
            }
        }

        return $retval;
    }

    /**
     * Prefix the database table columns for the given resource
     */
    public function prefixColumns(): array
    {
        return $this->qualifyColumns($this->getConnection()
            ->getSchemaBuilder()
            ->getColumnListing($this->getTable()));
    }

    /**
     * Get NULLS LAST SQL.
     */
    protected function getNullsLastSql(Builder $query, string $column, string $direction): string
    {
        /** @var \Illuminate\Database\Connection */
        $connection = $query->getConnection();

        $sql = match ($connection->getDriverName()) {
            'mysql', 'mariadb', 'sqlsrv', 'sqlite' => 'CASE WHEN :column IS NULL THEN 1 ELSE 0 END, :column :direction',
            'pgsql' => ':column :direction NULLS LAST',
        };

        return str_replace(
            [':column', ':direction'],
            [$column, $direction],
            sprintf($sql, $column, $direction)
        );
    }
}
