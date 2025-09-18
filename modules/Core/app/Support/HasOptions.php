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

namespace Modules\Core\Support;

use Illuminate\Support\Collection;
use Modules\Core\Resource\Resource;

trait HasOptions
{
    /**
     * From where the value key should be taken
     */
    public string $valueKey = 'value';

    /**
     * From where the label key should be taken
     */
    public string $labelKey = 'label';

    /**
     * Provided options
     */
    public mixed $options = [];

    /**
     * Add field options
     */
    public function options(array|callable|Collection|Resource $options): static
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Resolve the element options
     */
    public function resolveOptions(): array
    {
        if (is_callable($this->options)) {
            $options = call_user_func($this->options);
        } elseif ($this->options instanceof Resource) {
            $options = with($this->options, fn (Resource $resource) => $resource->applyDefaultOrder(
                $resource->newQuery(), request()
            )->get());
        } else {
            $options = $this->options;
        }

        return $this->formatOptions($options);
    }

    /**
     * Resolve all of the available options for the field (non filtered).
     */
    public function resolveAllOptions(): array
    {
        return $this->resolveOptions();
    }

    /**
     * Set custom key for value.
     */
    public function valueKey(string $key): static
    {
        $this->valueKey = $key;

        return $this;
    }

    /**
     * Set custom label key.
     */
    public function labelKey(string $key): static
    {
        $this->labelKey = $key;

        return $this;
    }

    /**
     * Format the given options for the front-end.
     *
     * @param  array|\Illuminate\Support\Collection  $options
     */
    protected function formatOptions($options): array
    {
        return collect($options)->map(function ($label, $value) {
            return isset($label[$this->valueKey]) ? $label : [$this->labelKey => $label, $this->valueKey => $value];
        })->values()->all();
    }
}
