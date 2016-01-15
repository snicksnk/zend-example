<?php
namespace Texsts;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Texsts\Repository\Text' => function ($em) {
                    $em = $em->get('Doctrine\ORM\EntityManager');
                    $svc = new \Texsts\Repository\TextRepository($em);
                    return $svc;
                },
                'Texsts\Service\Text' => function ($sm) {

                    $rep = $sm->get('Texsts\Repository\Text');
                    $svc = new \Texsts\Service\Text();
                    $svc->setRepository($rep);

                    return $svc;
                },
                'Texsts\Form\Text' => function($em) {
                    return new \Texsts\Form\Text();
                }
            ),
        );
    }
}
