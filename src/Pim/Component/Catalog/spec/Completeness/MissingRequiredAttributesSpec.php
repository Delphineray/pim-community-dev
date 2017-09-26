<?php

namespace spec\Pim\Component\Catalog\Completeness;

use Pim\Component\Catalog\Completeness\MissingRequiredAttributes;
use PhpSpec\ObjectBehavior;
use Pim\Component\Catalog\Model\AttributeInterface;
use Prophecy\Argument;

/**
 * @mixin MissingRequiredAttributes
 */
class MissingRequiredAttributesSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(MissingRequiredAttributes::class);
    }

    function it_returns_an_empty_list_of_missing_required_attributes()
    {
        $this->getAttributes()->shouldReturn([]);
    }

    function it_adds_an_attribute(AttributeInterface $attribute)
    {
        $this->add($attribute);

        $this->getAttributes()->shouldReturn([$attribute]);
    }

    function it_adds_mutltiple_attributes(AttributeInterface $attribute1, AttributeInterface $attribute2)
    {

    }
}
