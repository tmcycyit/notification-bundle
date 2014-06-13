<?php

namespace Yit\NotificationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class YitNotificationBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
