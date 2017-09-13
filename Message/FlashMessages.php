<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Message;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class FlashMessages
{
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var string
     */
    private $prefix;

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(SessionInterface $session, string $prefix)
    {
        $this->prefix = $prefix;
        $this->session = $session;
    }

    public function success(string $message, string $domain = 'FSiAdminBundle', array $params = [])
    {
        $this->add('success', $message, $domain, $params);
    }

    public function error(string $message, string $domain = 'FSiAdminBundle', array $params = [])
    {
        $this->add('error', $message, $domain, $params);
    }

    public function warning(string $message, string $domain = 'FSiAdminBundle', array $params = [])
    {
        $this->add('warning', $message, $domain, $params);
    }

    public function info(string $message, string $domain = 'FSiAdminBundle', array $params = [])
    {
        $this->add('info', $message, $domain, $params);
    }

    public function all(): array
    {
        return $this->getFlashBag()->get($this->prefix);
    }

    private function add(string $type, string $message, string $domain, array $params = []): void
    {
        if ($this->getFlashBag()->has($this->prefix)) {
            $messages = $this->getFlashBag()->get($this->prefix);
        } else {
            $messages = [];
        }

        $messages[$type][] = ['text' => $message, 'domain' => $domain, 'params' => $params];

        $this->flashBag->set($this->prefix, $messages);
    }

    private function getFlashBag(): FlashBagInterface
    {
        if ($this->flashBag instanceof FlashBagInterface) {
            return $this->flashBag;
        }

        $this->flashBag = method_exists($this->session, 'getFlashBag')
            ? $this->session->getFlashBag()
            : new FlashBag();

        return $this->flashBag;
    }
}
