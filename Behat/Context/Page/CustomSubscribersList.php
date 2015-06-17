<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Behat\Context\Page;

use SensioLabs\Behat\PageObjectExtension\PageObject\Exception\UnexpectedPageException;

class CustomSubscribersList extends Page
{
    protected $path = '/admin/list/custom_subscriber';

    protected function verifyPage()
    {
        if (!$this->has('css', 'h1#page-header:contains("Custom subscribers list")')) {
            throw new UnexpectedPageException(sprintf("%s page is missing \"Custom subscribers list\" header", $this->path));
        }
    }
}
