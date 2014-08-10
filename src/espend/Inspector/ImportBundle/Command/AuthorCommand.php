<?php

namespace espend\Inspector\ImportBundle\Command;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use espend\Inspector\CoreBundle\Entity\InspectorAuthor;
use espend\Inspector\CoreBundle\Entity\InspectorAuthorClass;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class AuthorCommand extends ContainerAwareCommand {

    /**
     * @var EntityManager
     */
    private $em;

    private $authors = array();

    protected function configure() {
        $this->setName('inspector:author');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $this->em = $this->getContainer()->get('doctrine')->getManager();
        $this->em->getConfiguration()->setSQLLogger(null);

        $inspectorClassRepository = $this->em->getRepository('espendInspectorCoreBundle:InspectorClass');
        $qb = $inspectorClassRepository->createQueryBuilder('class');

        $qb->select(array(
            'class.id',
            'class.doc_comment',
        ));

        $iterableResult = $qb->getQuery()->iterate(null, AbstractQuery::HYDRATE_SCALAR);

        $i = 0;
        foreach ($iterableResult AS $row) {

            if(preg_match_all("#\@author\s*(.*)\s*<\s*(.*@.*)\s*>#i", $row[0]['doc_comment'], $result, PREG_SET_ORDER)) {

                foreach($result as $res) {

                    $name = trim($res[1]);
                    $mail = trim($res[2]);

                    if(strlen($name) > 0 && strlen($mail) > 0) {

                        $author = $this->getAuthor($name, $mail);

                        $author_class = $this->em->getRepository('espendInspectorCoreBundle:InspectorAuthorClass')->findOneBy(array(
                            'class' => $row[0]['id'],
                            'author' => $author->getId(),
                        ));

                        if($author_class == null) {
                            $author_class = new InspectorAuthorClass();
                            $author_class->setClass($this->em->getReference('espendInspectorCoreBundle:InspectorClass', $row[0]['id']));
                            $author_class->setAuthor($author);
                            $this->em->persist($author_class);
                        }

                        $author_class->setLastFoundAt(new \DateTime());

                    }

                }

            }

            if (($i++ % 50) == 0) {
                $output->writeln('flush');
                $this->em->flush();
            }

        }

        $this->em->flush();
    }

    private function getAuthor($name, $mail) {

        if(isset($this->authors[$name . $mail])) {
            return $this->authors[$name . $mail];
        }

        $qb = $this->em->getRepository('espendInspectorCoreBundle:InspectorAuthor')->createQueryBuilder('author');

        $qb->andWhere('author.name = :name AND author.mail = :mail');
        $qb->setParameter('name', $name);
        $qb->setParameter('mail', $mail);

        /** @var InspectorAuthor $author */
        $author = $qb->getQuery()->getOneOrNullResult();
        if($author != null) {
            return $this->authors[$name.$mail] = $author;
        }

        $author = new InspectorAuthor();
        $author->setName($name);
        $author->setMail($mail);
        $author->setLastFoundAt(new \DateTime());

        $this->em->persist($author);
        $this->em->flush($author);

        return $this->authors[$name . $mail] = $author;
    }

}

