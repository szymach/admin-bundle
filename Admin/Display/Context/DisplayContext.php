<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\Display\Context;

use FSi\Bundle\AdminBundle\Admin\Context\ContextAbstract;
use FSi\Bundle\AdminBundle\Admin\Display\Element as DisplayElement;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Display\Display;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\DisplayEvent;
use FSi\Bundle\AdminBundle\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use function get_class;

class DisplayContext extends ContextAbstract
{
    /**
     * @var DisplayElement
     */
    protected $element;

    /**
     * @var Display
     */
    private $display;

    public function hasTemplateName(): bool
    {
        return true === $this->element->hasOption('template') || true === parent::hasTemplateName();
    }

    public function getTemplateName(): ?string
    {
        return true === $this->element->hasOption('template')
            ? $this->element->getOption('template')
            : parent::getTemplateName()
        ;
    }

    public function getData(): array
    {
        return [
            'display' => $this->display->getData(),
            'element' => $this->element,
        ];
    }

    public function setElement(Element $element): void
    {
        if (false === $element instanceof DisplayElement) {
            throw InvalidArgumentException::create(self::class, DisplayElement::class, get_class($element));
        }
        $this->element = $element;
    }

    protected function createEvent(Request $request): AdminEvent
    {
        $object = $this->getObject($request);
        $this->display = $this->element->createDisplay($object);

        return new DisplayEvent($this->element, $request, $this->display, $object);
    }

    protected function getSupportedRoute(): string
    {
        return 'fsi_admin_display';
    }

    protected function supportsElement(Element $element): bool
    {
        return $element instanceof DisplayElement;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    private function getObject(Request $request)
    {
        $id = $request->get('id');

        $object = $this->element->getDataIndexer()->getData($id);
        if (null === $object) {
            throw new NotFoundHttpException(sprintf('Can\'t find object with id %s', $id));
        }

        return $object;
    }
}
