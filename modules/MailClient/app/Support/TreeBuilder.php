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

namespace Modules\MailClient\Support;

class TreeBuilder
{
    /**
     * The parent id key name in the array
     *
     * @var string
     */
    protected $parentIdKeyName = 'parent_id';

    /**
     * The children key name when createing the child array
     *
     * @var string
     */
    protected $childrenKeyName = 'children';

    /**
     * The main id key name
     *
     * @var string
     */
    protected $mainIdKeyName = 'id';

    /**
     * Build the tree
     *
     * @param  int  $parentId
     * @return array
     */
    public function build(array $elements, $parentId = 0)
    {
        $branch = [];
        foreach ($elements as $element) {
            if ($element[$this->parentIdKeyName] == $parentId) {
                $children = $this->build($elements, $element[$this->mainIdKeyName]);

                if ($children) {
                    $element[$this->childrenKeyName] = $children;
                }

                $branch[] = $element;
            }
        }

        return $branch;
    }

    /**
     * Set parent id key name
     */
    public function setParentIdKeyName(string $name)
    {
        $this->parentIdKeyName = $name;

        return $this;
    }

    /**
     * Set children key name
     */
    public function setChildrenKeyName(string $name)
    {
        $this->childrenKeyName = $name;

        return $this;
    }

    /**
     * Set main id key name
     */
    public function setMainIdKeyName(string $name)
    {
        $this->mainIdKeyName = $name;

        return $this;
    }
}
