<?php

namespace spec\FSi\Bundle\AdminBundle\Menu\Builder;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use FSi\Bundle\AdminBundle\Menu\Item\Item;

class MenuBuilderSpec extends ObjectBehavior
{
    public function let(EventDispatcherInterface $dispatcher): void
    {
        $this->beConstructedWith($dispatcher, 'fsi_admin.menu.tools');
    }

    public function it_should_emit_proper_event(EventDispatcherInterface $dispatcher): void
    {
        $dispatcher->dispatch(
            'fsi_admin.menu.tools',
            Argument::allOf(
                Argument::type('FSi\Bundle\AdminBundle\Event\MenuEvent')
            )
        )->shouldBeCalled();

        $this->buildMenu()->shouldReturnAnInstanceOf(Item::class);
    }
}
