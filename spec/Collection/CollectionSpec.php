<?php

declare(strict_types=1);

namespace spec\App\Collection;

use App\Collection\Collection;
use PhpSpec\ObjectBehavior;

/**
 * @mixin Collection
 */
class CollectionSpec extends ObjectBehavior
{
    public function let() : void
    {
        $this->beConstructedWith(['DK', 'DE', 'US']);
    }

    public function it_is_initializable() : void
    {
        $this->shouldHaveType(Collection::class);
    }

    public function it_has_item_at_index() : void
    {
        $this->getByIndex(0)->shouldReturn('DK');
        $this->getByIndex(1)->shouldReturn('DE');
        $this->getByIndex(2)->shouldReturn('US');
        $this->getByIndex(3)->shouldReturn(null);
    }

    public function it_checks_if_item_is_in_collection() : void
    {
        $this->isInCollection('DK')->shouldReturn(true);
        $this->isInCollection('DE')->shouldReturn(true);
        $this->isInCollection('US')->shouldReturn(true);
        $this->isInCollection('NOT_IN_COLLECTION')->shouldReturn(false);
        $this->isInCollection('PL')->shouldReturn(false);
    }

    public function it_checks_if_is_not_empty() : void
    {
        $this->isEmpty()->shouldReturn(false);
    }

    public function it_checks_if_is_empty() : void
    {
        $this->beConstructedWith([]);
        $this->isEmpty()->shouldReturn(true);
    }
}
