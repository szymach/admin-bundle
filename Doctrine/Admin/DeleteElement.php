<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Doctrine\Admin;

abstract class DeleteElement extends BatchElement
{
    /**
     * @inheritdoc
     */
    public function apply($object)
    {
        $this->getObjectManager()->remove($object);
        $this->getObjectManager()->flush();
    }
}