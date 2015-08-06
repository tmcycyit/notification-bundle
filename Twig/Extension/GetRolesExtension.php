<?php
/**
 * Created by PhpStorm.
 * User: aram
 * Date: 5/29/15
 * Time: 3:08 PM
 */

namespace Yit\NotificationBundle\Twig\Extension;
use Symfony\Component\DependencyInjection\Container;


class GetRolesExtension extends \Twig_Extension
{
    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
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
            new \Twig_SimpleFilter('noteGetRoles', array($this, 'noteGetRoles')),
        );
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('noteGetRoles', array($this, 'noteGetRoles'))
        );
    }

    /**
     * @param $user
     * @return mixed
     */
    public function noteGetRoles($user)
    {
        // get em
        $em = $this->container->get('doctrine')->getManager();

        // get roles
        $roles = $em->getRepository("YitNotificationBundle:FastPreparedNote")->findRolesByUser($user);

        return $roles;

    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'yit_note_must_sent_extension';
    }
}
