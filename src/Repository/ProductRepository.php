<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function getProduct($params)
    {
        $query = $this->createQueryBuilder('product');
            $query
                ->where('product.id LIKE :val OR product.name LIKE :val OR product.price  LIKE :val')
                ->setParameter('val', '%' . trim($params[3]['value']) . '%');


        $countResult = count($query->getQuery()->getResult());
        $query->setFirstResult($params[0])->setMaxResults($params[1]);
        // Order
        foreach ($params[2] as $order) {
            if ($order['name'] != '') {
                $orderColumn = null;

                switch ($order['name']) {
                    case 'id':{
                        $orderColumn='product.id';
                        break;
                    }
                    case 'name':
                        {
                            $orderColumn = 'product.name';
                            break;
                        }


                    case 'price':
                        {
                            $orderColumn = 'product.price';
                            break;
                        }

                    default:
                        break;
                }

                if ($orderColumn !== null) {
                    $query->orderBy($orderColumn, $order['dir']);
                }
            }
        }

        $results = $query->getQuery()->getResult();

        return array(
            "results" => $results,
            "countResult" => $countResult
        );

    }
}
