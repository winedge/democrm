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

namespace Modules\Billable\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Billable\Models\Billable;
use Modules\Billable\Models\BillableProduct;
use Modules\Billable\Models\Product;

class BillableProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = BillableProduct::class;

    /**
     * Next product display order
     */
    protected static $displayOrder = 1;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'billable_id' => Billable::factory()->withBillableable(),
            'name' => $name = $this->faker->catchPhrase(),
            'description' => $this->faker->text(),
            'unit_price' => $this->faker->randomFloat(3, 300, 4500),
            'qty' => 1,
            'tax_label' => 'TAX',
            'tax_rate' => 0,
            'display_order' => static::$displayOrder,
            'product_id' => Product::factory()->state(function ($attributes) use ($name) {
                return ['name' => $name];
            }),
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return self
     */
    public function configure()
    {
        return $this->afterCreating(function (BillableProduct $product) {
            static::$displayOrder = $product->display_order + 1;
        });
    }

    /**
     * Add that the product billable with be tax exclusive.
     */
    public function withTaxExclusiveBillable()
    {
        return $this->for(Billable::factory()->withBillableable()->taxExclusive());
    }

    /**
     * Add that the product billable with be tax inclusive.
     */
    public function withTaxInclusiveBillable()
    {
        return $this->for(Billable::factory()->withBillableable()->taxInclusive());
    }

    /**
     * Add that the product billable with be without tax.
     */
    public function withBillableWithoutTax()
    {
        return $this->for(Billable::factory()->withBillableable()->noTax());
    }
}
