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

namespace Modules\Deals\Cards;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Modules\Core\Card\TableCard;
use Modules\Deals\Criteria\ViewAuthorizedDealsCriteria;
use Modules\Deals\Models\Deal;

class RecentlyModifiedDeals extends TableCard
{
    /**
     * Limit the number of records shown in the table
     *
     * @var int
     */
    protected $limit = 20;

    /**
     * Created in the last 30 days
     *
     * @var int
     */
    protected $days = 30;

    /**
     * Provide the table items.
     *
     * @return \Illuminate\Support\Collection
     */
    public function items(Request $request): iterable
    {
        return Deal::select(['id', 'name', 'updated_at', 'stage_id'])
            ->criteria(ViewAuthorizedDealsCriteria::class)
            ->with(['stage' => fn ($query) => $query->select(['id', 'name'])])
            ->where('updated_at', '>', Carbon::asCurrentTimezone()->subDays($this->days)->inAppTimezone())
            ->orderBy('updated_at', 'desc')
            ->limit($this->limit)
            ->get()
            ->map(fn (Deal $deal) => [
                'id' => $deal->id,
                'name' => $deal->name,
                'stage' => $deal->stage,
                'updated_at' => $deal->updated_at,
                'path' => $deal->resource()->viewRouteFor($deal),
            ]);
    }

    /**
     * Provide the table fields
     */
    public function fields(): array
    {
        return [
            ['key' => 'name', 'label' => __('deals::fields.deals.name')],
            ['key' => 'stage.name', 'label' => __('deals::fields.deals.stage.name')],
            ['key' => 'updated_at', 'label' => __('core::app.updated_at')],
        ];
    }

    /**
     * Card title
     */
    public function name(): string
    {
        return __('deals::deal.cards.recently_modified');
    }

    /**
     * jsonSerialize
     */
    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'helpText' => __('deals::deal.cards.recently_modified_info', ['total' => $this->limit, 'days' => $this->days]),
        ]);
    }
}
