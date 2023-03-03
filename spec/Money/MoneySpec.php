<?php

declare(strict_types=1);

namespace spec\App\Money;

use App\Money\Money;
use PhpSpec\ObjectBehavior;

/**
 * @mixin Money
 */
class MoneySpec extends ObjectBehavior
{
    public function let() : void
    {
        $this->beConstructedThrough('create', [50.5, 'EUR']);
    }

    public function it_is_initializable() : void
    {
        $this->shouldHaveType(Money::class);
    }

    public function it_has_amount() : void
    {
        $this->getAmount()->shouldReturn(50.5);
    }

    public function it_has_currency() : void
    {
        $this->getCurrency()->shouldReturn('EUR');
    }
}
