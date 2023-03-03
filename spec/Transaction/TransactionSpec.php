<?php

declare(strict_types=1);

namespace spec\App\Transaction;

use App\Transaction\Transaction;
use PhpSpec\ObjectBehavior;

/**
 * @mixin Transaction
 */
class TransactionSpec extends ObjectBehavior
{
    public function let() : void
    {
        $this->beConstructedThrough('create', ['4745030', 2000.00, 'GBP']);
    }

    public function it_is_initializable() : void
    {
        $this->shouldHaveType(Transaction::class);
    }

    public function it_has_bin() : void
    {
        $this->getBin()->shouldReturn('4745030');
    }

    public function it_has_amount() : void
    {
        $this->getAmount()->shouldReturn(2000.00);
    }

    public function it_has_currency() : void
    {
        $this->getCurrency()->shouldReturn('GBP');
    }
}
