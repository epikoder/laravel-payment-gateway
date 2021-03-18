<?php
namespace Epikoder\LaravelPaymentGateway\Traits;

use ReflectionObject;

trait Objectify
{
    public  function createFromJson($json)
    {
        $sourceReflection = new ReflectionObject($json);
        $destinationReflection = new ReflectionObject($this);
        $sourceProperties = $sourceReflection->getProperties();
        foreach ($sourceProperties as $sourceProperty) {
            $sourceProperty->setAccessible(true);
            $name = $sourceProperty->getName();
            $value = $sourceProperty->getValue($json);
            if ($destinationReflection->hasProperty($name)) {
                $propDest = $destinationReflection->getProperty($name);
                $propDest->setAccessible(true);
                $propDest->setValue($this, $value);
            } else {
                $this->$name = $value;
            }
        }
    }
}
