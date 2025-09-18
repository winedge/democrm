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

namespace Modules\Core\Settings\Utilities;

use Illuminate\Support\Arr as BaseArr;
use UnexpectedValueException;

class Arr extends BaseArr
{
    /**
     * Set an array item to a given value using "dot" notation.
     *
     * If no key is given to the method, the entire array will be replaced.
     *
     * @param  array  &$array
     * @param  string  $key
     * @param  mixed  $value
     */
    public static function set(&$array, $key, $value): array
    {
        $segments = explode('.', $key);
        $key = array_pop($segments);

        // iterate through all of $segments except the last one
        foreach ($segments as $segment) {
            if (! array_key_exists($segment, $array)) {
                $array[$segment] = [];
            } elseif (! is_array($array[$segment])) {
                throw new UnexpectedValueException('Non-array segment encountered');
            }

            $array = &$array[$segment];
        }

        $array[$key] = $value;

        return $array;
    }
}
