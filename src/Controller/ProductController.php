<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/create", name="product_create")
     * 
     * UPLOAD :
     * - Ajouter un champ de type file (enctype) X
     * - Prévoir un dossier pour uploader les fichiers X
     * - Stocker le chemin de l'image dans la BDD (?)
     * - Faire l'upload en PHP X et la vérification (?)
     */
    public function create(Request $request, EntityManagerInterface $entityManager)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère l'image pour l'upload
            $image = $form->get('image')->getData();

            // On vérifie qu'une image est uploadée...
            if ($image !== null) {
                // On déplace l'image uploadée vers un dossier de notre projet
                // On génére un nom de fichier aléatoire
                $fileName = uniqid().'.'.$image->guessExtension();
                $image->move(
                    $this->getParameter('kernel.project_dir').'/public/uploads',
                    $fileName
                );

                // @todo: Ajouter une propriété image dans la class Product
                $product->setImage($fileName);
            }

            // J'associe l'utilisateur connecté au produit
            $product->setUser($this->getUser());

            $entityManager->persist($product); // On persiste l'objet
            $entityManager->flush(); // On exécute la requête (INSERT...)
        }

        return $this->render('product/create.html.twig', [
            'form' => $form->createView(),
            'edit' => false,
        ]);
    }

    /**
     * @Route("/product", name="product_index")
     */
    public function index(ProductRepository $repository)
    {
        // $this->getDoctrine()->getRepository(Product::class)->findAll();
        $products = $repository->findAllProducts();

        dump($products);

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/product/{id}", name="product_show")
     */
    public function show(Product $product, $id, ProductRepository $repository)
    {
        //$product = $repository->find($id);

        //if (!$product) {
        //    throw $this->createNotFoundException();
        //}

        dump($product->getCategory()->getName());

        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/product/edit/{id}", name="product_edit")
     * @Security("user === product.getUser()")
     */
    public function edit(Product $product, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On peut récupérer les infos directement dans la request
            $product->setName($request->request->get('product')['name']);

            $entityManager->flush(); // On exécute la requête (UPDATE...)
        }

        return $this->render('product/create.html.twig', [
            'form' => $form->createView(),
            'edit' => true,
        ]);
    }

    /**
     * @Route("/product/delete/{id}", name="product_delete")
     * @Security("user === product.getUser()")
     */
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager)
    {
        $token = $request->request->get('csrf_token');

        // Ici, on se protège d'une faille CSRF
        if ($this->isCsrfTokenValid('delete-'.$product->getId(), $token)) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_index');
    }
}
