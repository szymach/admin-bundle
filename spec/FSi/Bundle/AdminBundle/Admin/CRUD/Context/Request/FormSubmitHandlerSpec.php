<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request;

use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Event\FormEvents;
use FSi\Bundle\AdminBundle\Event\ListEvent;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use PhpSpec\ObjectBehavior;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;

class FormSubmitHandlerSpec extends ObjectBehavior
{
    public function let(EventDispatcherInterface $eventDispatcher, FormEvent $event): void
    {
        $event->hasResponse()->willReturn(false);
        $this->beConstructedWith($eventDispatcher);
    }

    public function it_is_context_request_handler(): void
    {
        $this->shouldHaveType(HandlerInterface::class);
    }

    public function it_throw_exception_for_non_form_event(ListEvent $listEvent, Request $request): void
    {
        $this->shouldThrow(
            new RequestHandlerException(
                "FSi\\Bundle\\AdminBundle\\Admin\\CRUD\\Context\\Request\\FormSubmitHandler requires FormEvent"
            )
        )->during('handleRequest', [$listEvent, $request]);
    }

    public function it_do_nothing_on_non_POST_request(FormEvent $event, Request $request): void
    {
        $request->isMethod(Request::METHOD_POST)->willReturn(false);

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    public function it_submit_form_on_POST_request(
        FormEvent $event,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        FormInterface $form
    ): void {
        $request->isMethod(Request::METHOD_POST)->willReturn(true);
        $eventDispatcher->dispatch(FormEvents::FORM_REQUEST_PRE_SUBMIT, $event)
            ->shouldBeCalled();

        $event->getForm()->willReturn($form);
        $form->handleRequest($request)->shouldBeCalled();

        $eventDispatcher->dispatch(FormEvents::FORM_REQUEST_POST_SUBMIT, $event)
            ->shouldBeCalled();

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    public function it_return_response_from_request_pre_submit_event(
        FormEvent $event,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        Response $response
    ): void {
        $request->isMethod(Request::METHOD_POST)->willReturn(true);
        $eventDispatcher->dispatch(FormEvents::FORM_REQUEST_PRE_SUBMIT, $event)
            ->will(
                function () use ($event, $response) {
                    $event->hasResponse()->willReturn(true);
                    $event->getResponse()->willReturn($response);
                }
            );

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf(Response::class);
    }

    public function it_return_response_from_request_post_submit_event(
        FormEvent $event,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        FormInterface $form,
        Response $response
    ): void {
        $request->isMethod(Request::METHOD_POST)->willReturn(true);
        $eventDispatcher->dispatch(FormEvents::FORM_REQUEST_PRE_SUBMIT, $event)
            ->shouldBeCalled();

        $event->getForm()->willReturn($form);
        $form->handleRequest($request)->shouldBeCalled();

        $eventDispatcher->dispatch(FormEvents::FORM_REQUEST_POST_SUBMIT, $event)
            ->will(
                function () use ($event, $response) {
                    $event->hasResponse()->willReturn(true);
                    $event->getResponse()->willReturn($response);
                }
            );

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf(Response::class);
    }
}
