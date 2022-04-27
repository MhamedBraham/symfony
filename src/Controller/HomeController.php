<?php

namespace App\Controller;
use App\Entity\Product;
use App\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Repository\ProductRepository;
use App\Repository\CategorieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{

    private $repos;
    private $doctrine;
    public function __construct(ProductRepository $repos,ManagerRegistry $doctrine)
    {
       $this->repos = $repos;
       $this->doctrine = $doctrine;
    }
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
     * @Route("/prod/{id}", name="app_list_prod_categ")
     */
    public function listProdParCateg($id): Response
    {
         $categorie= $this->doctrine->getRepository(Categorie::class)->find($id);
       
        $Products = $this->repos->findBy(['categorie'=> $categorie]);
       // dd($Products);
        return $this->render('home/listparCateg.html.twig', ["Products" => $Products,"categorie" =>$categorie]);
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