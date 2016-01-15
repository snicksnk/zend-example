<?php
namespace Texsts\Repository;
use Doctrine\ORM\EntityManager;
use Texsts\Entity\Text;

/**
 * Created by PhpStorm.
 * User: snicksnk
 * Date: 23.10.15м
 * Time: 12:57
 */
class TextRepository
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->setEm($em);
    }

    public function setEm(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getById($id)
    {
        return $this->em->find('Texsts\Entity\Text', (int)$id);
    }

    public function getByIds($ids = [], $saveOrder = false)
    {
        $result = $this->em->createQueryBuilder()
                           ->select('t')
                           ->from('Texsts\Entity\Text', 't')
                           ->where('t.id IN (:ids)')
                           ->setParameter('ids', $ids)
                           ->getQuery()
                           ->getResult()
        ;
        //TODO: add ORDER BY FIELD extension for Doctrine
        //https://github.com/beberlei/DoctrineExtensions/pull/33
        if ($saveOrder) {

            foreach ($result as $value) {
                $result[$value->getId()] = $value;
            }

            $new_result = [];
            foreach ($ids as $id) {
                $new_result[] = $result[$id];
            }

            $result = $new_result;
            unset($new_result);
        }

        return $result;
   }

    public function delete(Text $entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }

    public function save(Text $entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }
}