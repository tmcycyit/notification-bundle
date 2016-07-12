<?php
/**
 * Created by PhpStorm.
 * User: andranik
 * Date: 8/7/14
 * Time: 11:41 PM
 */

namespace Tmcycyit\NotificationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class FileFieldType extends AbstractType
{
    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'fileField';
    }
}