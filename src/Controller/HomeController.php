<?php

namespace App\Controller;
use App\Entity\Product;
use App\Entity\categorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Repository\ProductRepository;
use App\Repository\CategorieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Form\SearchAnnonceType;


class HomeController extends AbstractController
{
    private $logger;
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
    public function index(AuthenticationUtils $authenticationUtils,SessionInterface $session, ProductRepository $productRepository, Request $request): Response
    {
        $products=$this->getDoctrine()->getRepository(Product::class)->findAll();

        $form = $this->createForm(SearchAnnonceType::class);

         $search = $form->handleRequest($request);
      
         if($form->isSubmitted() && $form->isValid()){
             // on recherche les products correspondant aux mots clés
              $products = $productRepository->search($search->get('mots')->getData());
             
         }

        $product=$this->getDoctrine()->getRepository(Product::class)->findAll();

        $error = $authenticationUtils->getLastAuthenticationError();
            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();
            $panier = $session->get('panier', []);
    
            $panierwithData = [];
        
            foreach($panier as $id => $quantity){
                $panierwithData[] = [
                     'product' => $productRepository->find($id),
                     'quantity' => $quantity
                ];
            }
        
            $total = 0;
        
              foreach($panierwithData as $item){
                 $totalItem = $item['product']->getPrice() * $item['quantity'];
                 $total += $totalItem;
              }



        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'items' => $panierwithData,
            'total' => $total,
            'products' => $products,
            'product' => $product,'last_username' => $lastUsername, 'error' => $error,
            'form' => $form->createView()
        
        
        
        ]);


            
    
            
        
    }

      /**
     * @Route("/shop/{id}", name="app_list_prod_categ")
     */
    public function listProdParCateg($id): Response
    {
         $categorie= $this->doctrine->getRepository(Categorie::class)->find($id);
       
        $Products = $this->repos->findBy(['categorie'=> $categorie]);
       // dd($Products);
        return $this->render('home/listparCateg.html.twig', [
            'controller_name' => 'HomeController',
            'Products' => $Products,'categorie' =>$categorie]);
    }

      /**
     * @Route("/shop2", name="app_list_prod_categ2")
     */
    public function listProdParCateg2(AuthenticationUtils $authenticationUtils,SessionInterface $session, ProductRepository $productRepository, Request $request)
    {
        $products=$this->getDoctrine()->getRepository(Product::class)->findAll();

              $form = $this->createForm(SearchAnnonceType::class);

              $search = $form->handleRequest($request);
           
              if($form->isSubmitted() && $form->isValid()){
                  // on recherche les products correspondant aux mots clés
                   $products = $productRepository->search($search->get('mots')->getData());
                  
              }

              $error = $authenticationUtils->getLastAuthenticationError();
              // last username entered by the user
              $lastUsername = $authenticationUtils->getLastUsername();
              $panier = $session->get('panier', []);
      
              $panierwithData = [];
          
              foreach($panier as $id => $quantity){
                  $panierwithData[] = [
                       'product' => $productRepository->find($id),
                       'quantity' => $quantity
                  ];
              }
          
              $total = 0;
          
                foreach($panierwithData as $item){
                   $totalItem = $item['product']->getPrice() * $item['quantity'];
                   $total += $totalItem;
                }
  
       
        return $this->render('home/listparCateg2.html.twig', [
            'controller_name' => 'HomeController',
            'products' => $products,
            'items' => $panierwithData,
            'total' => $total,
            'form' => $form->createView() 
            
        
        ]);
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

    /**
     * @Route("/product/{id}", name="app_produit_detail",requirements={"id"="\d+"})
     */
    public function detail($id): Response
    {
        
        $Products = $this->repos->find($id);
        $categorie = $Products->getCategorie();
        if (!$Products) {
             throw $this->createNotFoundException('Ce produit est inexistant');
                      
         }
             
    
       return $this->render('home/detail.html.twig', ["Products" => $Products]); 
    }  

 /**
     * @Route("/panier/add/{id}", name="cart_add")
     */
    
    public function add2($id, SessionInterface $session) 
    {
    
     $panier=$session->get('panier', []);
    
     if(!empty($panier[$id])){
        $panier[$id]++;
     }else{
        $panier[$id] = 1;
     }
    
     $session->set('panier', $panier);
    
    return $this->redirectToRoute("app_home");
    
    }

    /**
     * @Route("/panier2/add/{id}", name="cart_add2")
     */
    
    public function add3($id, SessionInterface $session) 
    {
    
     $panier=$session->get('panier', []);
    
     if(!empty($panier[$id])){
        $panier[$id]++;
     }else{
        $panier[$id] = 1;
     }
    
     $session->set('panier', $panier);
    
    return $this->redirectToRoute("app_list_prod_categ2");
    
    }
    
    
    /**
         * @Route("/panier/remove/{id}", name="cart_remove")
         */
    public function remove($id, SessionInterface $session)
    {
    
     $panier = $session->get('panier', []);
    
     if (!empty($panier[$id])){
         unset($panier[$id]);
     }
    
     $session->set('panier', $panier);
    
     return $this->redirectToRoute("app_home");
    }

     /**
         * @Route("/panier/remove2/{id}", name="cart_remove2")
         */
        public function remove2($id, SessionInterface $session)
        {
        
         $panier = $session->get('panier', []);
        
         if (!empty($panier[$id])){
             unset($panier[$id]);
         }
        
         $session->set('panier', $panier);
        
         return $this->redirectToRoute("app_list_prod_categ2");
        }
    
    
}