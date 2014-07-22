<?php

namespace espend\Inspector\FrontendBundle\Twig;

use Symfony\Component\Routing\RouterInterface;

class TwigPathExtension extends \Twig_Extension {

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    public function __construct(RouterInterface $router) {
        $this->router = $router;
    }

    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('view_path', array($this, 'getViewPath'))
        );
    }

    public function getViewPath($parameter, $referenceType = false) {
        return $this->router->generate('espend_inspector_frontend_view', array(
           'view' => str_replace('\\', '/', $parameter),
        ), $referenceType);
    }

    public function getName() {
        return 'twig_url_path_extension';
    }


}