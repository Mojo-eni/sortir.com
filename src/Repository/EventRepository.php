<?php

namespace App\Repository;

use App\Entity\Event;
use DateInterval;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

        /**
         * @return Event[] Returns an array of Event objects
         */
        public function findByCampusId($id): array
        {
            return $this->createQueryBuilder('q')
                ->leftJoin('p.city', 'c')
                ->andWhere('q.campus = :val')
                ->setParameter('val', $id)
                ->orderBy('q.id', 'ASC')
                ->setMaxResults(10)
                ->getQuery()
                ->getArrayResult()
            ;
        }

    public function findEventsNotAttendedByUser($user) : array
    {
        $today = new DateTime();
        return $this->createQueryBuilder('e')
            ->innerJoin('e.participants', 'u')
            ->andWhere(':userId NOT MEMBER OF e.participants')
            ->andWhere(':campus = e.campus')
            ->andWhere(':deadline < e.participationDeadline')
            ->setParameter('userId', $user->getId())
            ->setParameter('campus', $user->getCampus())
            ->setParameter('deadline', $today)
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function findPastEvents() : array
    {
        $yesterday = new DateTime();
        $yesterday->sub(new DateInterval('P1D'));
        $oneMonthAgo = new DateTime();
        $oneMonthAgo->modify('-1 month');   
        return $this->createQueryBuilder('e')
            ->andWhere('e.eventStart < :yesterday')
            ->andWhere('e.eventStart > :oneMonthAgo')
            ->setParameter('oneMonthAgo', $oneMonthAgo)
            ->setParameter('yesterday', $yesterday)
            ->getQuery()
            ->getResult();
    }

//        public function findOneBySomeField($value): ?Event
//        {
//            return $this->createQueryBuilder('e')
//                ->andWhere('e.exampleField = :val')
//                ->setParameter('val', $value)
//                ->getQuery()
//                ->getOneOrNullResult()
//            ;
//        }
}
