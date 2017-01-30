<?php

$classLoader = require __DIR__ . '/../vendor/autoload.php';

Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($classLoader, 'loadClass'));

unset($classLoader);
