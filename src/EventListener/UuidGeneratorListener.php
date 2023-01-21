<?php

namespace App\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Uid\Uuid;

class UuidGeneratorListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        
        if (method_exists($entity, 'setUuid')) {
            $uuid = strval(Uuid::v6());
            $entity->setUuid($uuid);
        }
    }
}