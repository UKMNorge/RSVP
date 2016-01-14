<?php

namespace UKMNorge\RSVPBundle\Repository;

/**
 * WaitingRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class WaitingRepository extends \Doctrine\ORM\EntityRepository
{
	public function getCount( $event ) {
		$query = $this->createQueryBuilder('w')
			->select('COUNT(w.id)')
		    ->where('w.event = :event')
		    ->setParameter('event', $event)
		    ->getQuery();
		
		return $query->getSingleScalarResult();
	}

	public function getMyNumber( $user, $event ) {
		$response = $this->findOneBy( array('user' => $user->getDeltaId(), 'event' => $event));
		if( null !== $response ) {
			return $response->getId();
		}
		return false;
	}	
	
	public function getNextInLine( $user, $event ) {
		$queue_id = $this->getMyNumber( $user, $event );
		if( false === $queue_id ) {
			return false;
		}
		
		$query = $this->createQueryBuilder('w')
			->select('w.user')
		    ->where('w.event = :event')
		    ->andWhere('w.id < :queue_id')
		    ->setParameter('event', $event)
		    ->setParameter('queue_id', $queue_id)
		    ->orderby('w.id', 'ASC')
		    ->setMaxResults(1)
		    ->getQuery();
		return $query->getSingleScalarResult();
	}
	
	public function getCountInFront( $user, $event ) {
		$queue_id = $this->getMyNumber( $user, $event );
		if( false === $queue_id ) {
			return $this->getCount( $event )+1;
		}

		$query = $this->createQueryBuilder('w')
			->select('COUNT(w.id)')
		    ->where('w.event = :event')
		    ->andWhere('w.id < :queue_id')
		    ->setParameter('event', $event)
		    ->setParameter('queue_id', $queue_id)
		    ->orderby('w.id', 'ASC')
		    ->getQuery();
		

		return $query->getSingleScalarResult()+1;
	}
}
