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

namespace Modules\Deals\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Lang;
use Modules\Core\Concerns\HasDisplayOrder;
use Modules\Core\Contracts\Resources\Resourceable as ResourceableContract;
use Modules\Core\Models\CacheModel;
use Modules\Core\Resource\Resourceable;
use Modules\Deals\Database\Factories\StageFactory;
use Modules\Users\Models\User;

class Stage extends CacheModel implements ResourceableContract
{
    use HasDisplayOrder,
        HasFactory,
        Resourceable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'win_probability', 'display_order', 'pipeline_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'win_probability' => 'int',
        'display_order' => 'int',
        'pipeline_id' => 'int',
    ];

    /**
     * Perform any actions required after the model boots.
     */
    protected static function booted(): void
    {
        static::deleting(function (Stage $model) {
            if ($model->deals()->withTrashed()->count() > 0) {
                abort(Response::HTTP_CONFLICT, __('deals::deal.stage.delete_usage_warning'));
            }
        });
    }

    /**
     * A stage belongs to pipeline
     */
    public function pipeline(): BelongsTo
    {
        return $this->belongsTo(\Modules\Deals\Models\Pipeline::class);
    }

    /**
     * A stage has many deals
     */
    public function deals(): HasMany
    {
        return $this->hasMany(\Modules\Deals\Models\Deal::class);
    }

    /**
     * Get all stages for that can be used on option fields.
     */
    public static function allStagesForOptions(User $user): Collection
    {
        return static::with('pipeline')
            ->whereHas('pipeline', fn (Builder $query) => $query->visible($user))
            ->get()
            ->map(fn (Stage $stage) => [
                'id' => $stage->getKey(),
                'name' => "{$stage->name} ({$stage->pipeline->name})",
            ]);
    }

    /**
     * Scope a query to only include only deals of the given pipeline.
     */
    public function scopeOfPipeline(Builder $query, Pipeline|int $pipeline): void
    {
        $query->where('pipeline_id', is_int($pipeline) ? $pipeline : $pipeline->getKey());
    }

    /**
     * Name attribute accessor
     *
     * Supports translation from language file
     */
    protected function name(): Attribute
    {
        return Attribute::get(function (?string $value, array $attributes) {
            if (! array_key_exists('id', $attributes)) {
                return $value;
            }

            $customKey = 'custom.stage.'.$attributes['id'];

            if (Lang::has($customKey)) {
                return __($customKey);
            } elseif (Lang::has($value)) {
                return __($value);
            }

            return $value;
        });
    }

    /**
     * Get the deals summary (by stage).
     */
    public static function summary(Builder $query, ?int $pipelineId = null, ?int $stageId = null)
    {
        return static::select(['id', 'win_probability'])
            ->when(is_int($pipelineId), fn (Builder $query) => $query->ofPipeline($pipelineId))
            ->when(is_int($stageId), fn (Builder $query) => $query->where('id', $stageId))
            ->get()
            ->mapWithKeys(function (Stage $stage) use ($query) {
                return [$stage->id => [
                    'count' => (int) $query->clone()->where('stage_id', $stage->id)->reorder()->count(),
                    'value' => (float) $sum = $query->clone()->where('stage_id', $stage->id)->reorder()->sum('amount'),
                    // Not applicable when the user is filtering won or lost deals
                    'weighted_value' => $stage->win_probability * $sum / 100,
                ]];
            });
    }

    /**
     * Find stage by given ID.
     *
     * Caches results because of import to prevent thousands of queries.
     */
    public static function findFromObjectCache(int|string $id): Stage
    {
        return Cache::driver('array')->rememberForever(
            'deal-save-stage-'.$id, fn () => static::find($id)
        );
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): StageFactory
    {
        return StageFactory::new();
    }
}
