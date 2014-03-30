<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Annotation;

use Doctrine\Common\Annotations\AnnotationReader;
use FSi\Bundle\AdminBundle\Admin\Manager;
use FSi\Bundle\AdminBundle\Factory\ElementFactory;
use FSi\Bundle\AdminBundle\Finder\AdminClassFinder;

class ManagerBuilder
{
    const ANNOTATION_CLASS = 'FSi\\Bundle\\AdminBundle\\Annotation\\Element';

    /**
     * @var AdminClassFinder
     */
    private $finder;

    /**
     * @var \FSi\Bundle\AdminBundle\Factory\ElementFactory
     */
    private $elementFactory;

    /**
     * @param AdminClassFinder $finder
     * @param ElementFactory $elementFactory
     */
    public function __construct(AdminClassFinder $finder, ElementFactory $elementFactory)
    {
        $this->finder = $finder;
        $this->elementFactory = $elementFactory;
    }

    public function build(Manager $manager)
    {
        $reader = new AnnotationReader();
        foreach ($this->finder->findClasses() as $class) {
            $annotation = $reader->getClassAnnotation(new \ReflectionClass($class), self::ANNOTATION_CLASS);
            if (isset($annotation) && $annotation instanceof Element) {
                $element = $this->elementFactory->create($class);
                $manager->addElement($element, $annotation->group);
            }
        }
    }
}
