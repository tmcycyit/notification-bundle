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
     * @param bool $jsonEncode
     * @return string
     */
    public function noteGetRoles($user, $jsonEncode = true)
    {
        // get em
        $em = $this->container->get('doctrine')->getManager();

        // get roles
        $roles = $em->getRepository("YitNotificationBundle:FastPreparedNote")->findRolesByUser($user);

        $result = array();

        // get user repository
        $userRepository = $this->container->getParameter('yit_notification.user_group');

        foreach($roles as $role){
            $builder = $em->createQueryBuilder();

            $builder
                ->select('g.name as name')
                ->from($userRepository, 'g')
                ->where('g.roles like :role')
                ->setParameter('role', '%' . $role . '%')
                ->setMaxResults(1)
            ;
            $name = $builder->getQuery()->getOneOrNullResult();

            $result[$role] = $name['name'];
        }

        return $jsonEncode ?  json_encode($result) : $result;

    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'yit_note_get_roles_extension';
    }
}
