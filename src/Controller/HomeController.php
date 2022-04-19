<?php

namespace App\Controller;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $product=$this->getDoctrine()->getRepository(Product::class)->findAll();

        $error = $authenticationUtils->getLastAuthenticationError();
            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'product' => $product,'last_username' => $lastUsername, 'error' => $error]);


            
    
            
        
    }


    /**
     * @Route("/product/add/{productname}/{type}/{qte}/{price}/{descriptions}/{picture}/{likes}/{size}/{color}/{boutiqueid}", name="app_product_add")
     */
    public function add($productname,$type,$qte,$price,$descriptions,$picture,$likes,$size,$color,$boutiqueid): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $product = new Product();
        $product->setProductname($productname);
        $product->setType($type);
        $product->setQte($qte);
        $product->setPrice($price);
        $product->setDescriptions($descriptions);
        $product->setPicture($picture);
        $product->setLikes($likes);
        $product->setSize($size);
        $product->setColor($color);
        $product->setBoutiqueid($boutiqueid);
        $entityManager->persist($product);
        $entityManager->flush();

        return $this->render('product/add.html.twig', ["productname"=>$productname,"type"=>$type,"qte"=>$qte,"price"=>$price,"descriptions"=>$descriptions,"picture"=>$picture,"likes"=>$likes,"size"=>$size,"color"=>$color,"boutiqueid"=>$boutiqueid
            
        ]);
    }
}