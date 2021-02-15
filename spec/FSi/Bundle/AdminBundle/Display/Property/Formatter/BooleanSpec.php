<?php

namespace spec\FSi\Bundle\AdminBundle\Display\Property\Formatter;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BooleanSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith('yes', 'no');
    }

    public function it_ignore_empty_values(): void
    {
        $this->format(0)->shouldReturn(0);
        $this->format(null)->shouldReturn(null);
        $this->format([])->shouldReturn([]);
    }

    public function it_decorate_value(): void
    {
        $this->format(true)->shouldReturn('yes');
        $this->format(false)->shouldReturn('no');
    }
}
