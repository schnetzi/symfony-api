<?php

namespace App\ApiPlatform;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\TipTicket;
use Doctrine\ORM\QueryBuilder;

class TipTicketIsPaidExtension implements QueryCollectionExtensionInterface
{
    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null
    ) {
        if ($resourceClass !== TipTicket::class) {
            return;
        }

        $rootAlias = $queryBuilder->getRootAlias()[0];
        $queryBuilder->andWhere(sprintf('%s.isPaid = :isPaid', $rootAlias))
        ->setParameter('isPaid', true);
    }

}
