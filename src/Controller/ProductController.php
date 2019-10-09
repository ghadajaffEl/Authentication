<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\DataTableProduct;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/", name="addProduct")
     * @param EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function productList(EntityManagerInterface $manager)
    {

        $products = $manager->getRepository(Product::class)->findAll();
        if(count($products)<1000){
            for($i=0;$i<1000;$i++){
                $product =new Product();
                $product->setName($this->getRandomString(8));
                $product->setPrice(random_int(1, 900));
                $manager->persist($product);

            }
            $manager->flush();
        }
        return $this->render('product/productList.html.twig', [
        ]);
    }

    /**
     * @Route("/productListDataTable",name="productListDataTable")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param DataTableProduct $dataTableProduct
     * @return JsonResponse
     */
    public function productListDataTable(Request $request,EntityManagerInterface $manager, DataTableProduct $dataTableProduct)
    {
        if ($request->isMethod('POST')) {
            $draw = intval($request->request->get('draw'));
            $start = $request->request->get('start');
            $length = $request->request->get('length');
            $search = $request->request->get('search');
            $orders = $request->request->get('order');
            $columns = $request->request->get('columns');

        }
        foreach ($orders as $key => $order) {
            $orders[$key]['name'] = $columns[$order['column']]['name'];
        }

        $params = [$start, $length, $orders, $search];
        $results = $manager->getRepository(Product::class)->getProduct($params);
        $objects = $results["results"];
        $totalObjectsCount = count($manager->getRepository(Product::class)->findAll());
        $filteredObjectsCount = $results["countResult"];
        $finalOutput = [];
        $finalOutput['draw'] = $draw;
        $finalOutput['recordsTotal'] = $totalObjectsCount;
        $finalOutput['recordsFiltered'] = $filteredObjectsCount;
        $finalOutput['data'] = [];
        foreach ($objects as $key => $product) {
            $fullColumn = array();
            foreach ($columns as $key => $column) {
                $responseTemp = "-";
                $responseTemp = $dataTableProduct->productListDataTable($column, $product, $responseTemp);
                $fullColumn[] = $responseTemp;
            }
            $finalOutput['data'][] = $fullColumn;
        }
        $returnResponse = new JsonResponse();
        $response = json_encode($finalOutput);
        $returnResponse->setJson($response);


        return $returnResponse;


    }


    function getRandomString($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = '';

        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        return $string;
    }

}
