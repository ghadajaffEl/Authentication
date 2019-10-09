<?php
/**
 * Created by PhpStorm.
 * User: jghada
 * Date: 07/10/2019
 * Time: 09:25
 */

namespace App\Service;


class DataTableProduct
{

    public function productListDataTable($column, $product, $responseTemp)
    {

        switch ($column['name']) {
            case 'id':
                {
                    $responseTemp = $product->getId();
                    break;
                }
            case 'name':
                {
                    $product = $product->getName();
                    $responseTemp = ucfirst($product);
                    break;
                }

            case 'price':
                {

                    $responseTemp = $product->getPrice();

                    break;
                }


            default :
                break;

        }
        return $responseTemp;

    }
}