<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request;

use FSi\Bundle\AdminBundle\Admin\Context\Request\AbstractHandler;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\ListEvent;
use FSi\Bundle\AdminBundle\Event\ListEvents;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DataGridSetDataHandler extends AbstractHandler
{
    public function handleRequest(AdminEvent $event, Request $request): ?Response
    {
        $event = $this->validateEvent($event);

        $this->eventDispatcher->dispatch(ListEvents::LIST_DATAGRID_DATA_PRE_BIND, $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        $event->getDataGrid()->setData($event->getDataSource()->getResult());
        $this->eventDispatcher->dispatch(ListEvents::LIST_DATAGRID_DATA_POST_BIND, $event);

        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        return null;
    }

    private function validateEvent(AdminEvent $event): ListEvent
    {
        if (false === $event instanceof ListEvent) {
            throw new RequestHandlerException(sprintf('%s requires ListEvent', get_class($this)));
        }

        return $event;
    }
}
