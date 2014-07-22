<?php

namespace espend\Inspector\FrontendBundle\Util;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use espend\Inspector\CoreBundle\Entity\InspectorClass;
use espend\Inspector\CoreBundle\Entity\InspectorMethod;
use espend\Inspector\FrontendBundle\Twig\TwigPathExtension;
use Symfony\Component\Routing\RouterInterface;

class SitemapGenerator {

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    public function __construct(EntityManager $em, TwigPathExtension $router) {
        $this->em = $em;
        $this->router = $router;
    }

    public function getContent() {
        ini_set('memory_limit', '1G');

        $content = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
 xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
 xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';

        /** @var EntityRepository $er */
        $er = $this->em->getRepository('espendInspectorCoreBundle:InspectorClass');

        $qb = $er->createQueryBuilder('classes');
        $iterableResult = $qb->getQuery()->iterate();
        while (($row = $iterableResult->next()) !== false) {

            /** @var InspectorClass $class */
            $class = $row[0];
            $content .= sprintf('<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>weekly</changefreq><priority>1</priority></url>', $this->router->getViewPath($class->getClass(), true), $class->getLastFoundAt()->format('Y-m-d'));
        }


        $qb = $this->em->getRepository('espendInspectorCoreBundle:InspectorMethod')->createQueryBuilder('method');
        $qb->join('method.class', 'class');
        $qb->addSelect('class');
        $qb->groupBy('method.class');
        $qb->addGroupBy('method.method');

        $iterableResult = $qb->getQuery()->iterate();
        while (($row = $iterableResult->next()) !== false) {

            /** @var InspectorMethod $method */
            $method = $row[0];
            $content .= sprintf('<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>', $this->router->getViewPath($method->getClass()->getClass() . ':' . $method->getMethod(), true), $method->getLastFoundAt()->format('Y-m-d'));
        }

        $content .= '</urlset>';

        return $content;
    }


} 