<?php
/**
 * Created by PhpStorm.
 * User: aram
 * Date: 8/1/14
 * Time: 5:39 PM
 */

namespace Yit\NotificationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;



class RemoveCommand extends ContainerAwareCommand
{

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('yitNote:remove:old')
            ->setDescription('Remove old notifications')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // get Entity manager
        $em = $this->getContainer()->get("doctrine")->getManager();

        $output->writeln("<info>Start ... </info>");

        $em->getRepository('YitNotificationBundle:NotificationStatus')->removeAllOlder(62);
        $em->getRepository('YitNotificationBundle:NotificationStatus')->removeAllUnStatus();

        $output->writeln("<info>Success</info>");
    }
}