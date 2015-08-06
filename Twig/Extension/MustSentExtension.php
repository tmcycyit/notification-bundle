<?php
/**
 * Created by PhpStorm.
 * User: aram
 * Date: 5/29/15
 * Time: 3:08 PM
 */

namespace Yit\NotificationBundle\Twig\Extension;
use Symfony\Component\DependencyInjection\Container;


class MustSentExtension extends \Twig_Extension
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('mustSentNote', array($this, 'mustSentNote')),
        );
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('mustSentNote', array($this, 'mustSentNote'))
        );
    }

    /**
     * @return mixed
     */
    public function mustSentNote()
    {
        $mustSent = $this->container->get('yitNote')->mustSent();

        return $mustSent;

    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'yit_note_must_sent_extension';
    }
}
