<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Entity\categorie;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Repository\ProductRepository;
use App\Repository\CategorieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Form\AddproductType;



class ProductaddController extends AbstractController
{
    
      /**
     * @Route("/admin3", name="productlist", methods={"GET"})
     */
    public function show(ProductRepository $productRepository): Response
    {
      

        return $this->render('admin/admin3.html.twig', [
            'product' => $productRepository->findAll(),
        ]);
    }
    
    
    
    /**
     * @Route("/productadd", name="productadd", methods={"GET", "POST"})
     */
    public function newproduct(Request $request, ProductRepository $productRepository): Response
    {
        $product = new Product();
        $form = $this->createForm(AddproductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $productRepository->add($product);
            return $this->redirectToRoute('productlist', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('productadd/index.html.twig', [
            'product' => $product,
            "form" => $form,
        ]);
    }


    /**
     * 
     * 
     * @Route("/edit/{id}", name="product_edit")
     */
    public function edit(Request $request,Product $product)
    {
        
        $form = $this->createForm(AddproductType::class, $product);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            
            //$em->persist($productadd);
            $em->flush();
            return $this->redirectToRoute('productlist');

        }

        
        

        
        return $this->render('productadd/edtindex.html.twig', [
            "form" => $form->createView(),
         
            array('form'=>$form),
          
        ]);
        
    }
}
