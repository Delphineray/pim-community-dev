<?php

declare(strict_types=1);

namespace Pim\Component\Catalog\Completeness;

use Pim\Component\Catalog\Model\AttributeInterface;

/**
 * Value object that holds the list of missing required attributes for a channel and scope used by the
 * CompletenessCalculator to generate the completeness for a product.
 *
 * @author    Samir Boulil <samir.boulil@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MissingRequiredAttributes
{
    /** @var array */
    protected $attributes = [];

    /**
     * Returns a list of attributes that are missing.
     *
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Adds an attribute as a remissing required attributes.
     *
     * @param AttributeInterface $attribute
     */
    public function add(AttributeInterface $attribute): void
    {
        $this->attributes[] = $attribute;
    }
}
