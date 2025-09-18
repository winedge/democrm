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

namespace Modules\Core\Menu;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class MenuManager
{
    /**
     * Hold the main menu items.
     *
     * @var (MenuItem|callable)[]
     */
    protected array $items = [];

    /**
     * Hold the registered menu metrics.
     *
     * @var \Modules\Core\Menu\Metric[]
     */
    protected array $metrics = [];

    /**
     * Register menu item(s).
     *
     * @param  MenuItem|callable(): MenuItem[]|array<int, MenuItem>  $items
     */
    public function register(MenuItem|callable|array $items): static
    {
        foreach (Arr::wrap($items) as $item) {
            $this->item($item);
        }

        return $this;
    }

    /**
     * Register a single menu item.
     *
     * @param  MenuItem|callable(): MenuItem[]  $item
     */
    public function item(MenuItem|callable $item): static
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Register new menu metric.
     *
     * @param  Metric|array<Metric>  $metric
     */
    public function metric(Metric|array $metric): static
    {
        $this->metrics = array_merge($this->metrics, Arr::wrap($metric));

        return $this;
    }

    /**
     * Get all of the registered menu metrics.
     *
     * @return Metric[]
     */
    public function metrics()
    {
        return $this->metrics;
    }

    /**
     * Get all registered menu items.
     */
    public function get(): Collection
    {
        return (new Collection($this->items))->map(function (MenuItem|callable|array $item) {
            return is_callable($item) ? call_user_func($item) : $item;
        })->flatten(1)->filter()->map($this->checkQuickCreateProperties(...))->whenNotEmpty(function (Collection $items) {
            return $this->checkPositions($items);
        })->filter->authorizedToSee()->values();
    }

    /**
     * Clears all the registered menu items and metrics.
     */
    public function clear(): static
    {
        $this->items = [];
        $this->metrics = [];

        return $this;
    }

    /**
     * Check if order is set and sort the items.
     */
    protected function checkPositions(Collection $items): Collection
    {
        /**
         * If there is no position set, add the index + 5
         */
        $items->each(function (MenuItem $item, int $index) {
            if (! $item->position) {
                $item->position($index + 10);
            }
        });

        /**
         * Sort the items with the actual order
         */
        return $this->sort($items);
    }

    /**
     * Check quick create properties and add default props.
     */
    protected function checkQuickCreateProperties(MenuItem $item): MenuItem
    {
        if ($item->inQuickCreate) {
            if (! $item->quickCreateRoute) {
                $item->quickCreateRoute(rtrim($item->route, '/').'/'.'create');
            }

            if (! $item->quickCreateName) {
                $item->quickCreateName($item->singularName ?? $item->name);
            }
        }

        return $item;
    }

    /**
     * Sort the items.
     */
    protected function sort(Collection $items): Collection
    {
        return $items->sort(function ($a, $b) {
            if ($a->position == $b->position) {
                return 0;
            }

            return ($a->position < $b->position) ? -1 : 1;
        })->values();
    }
}
