<?php
$app->bind('Concrete\Core\Session\SessionFactoryInterface', 'Application\Redis\Session\SessionFactory');