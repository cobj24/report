<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller för att hantera produkter.
 *
 * Innehåller CRUD-operationer (skapa, läsa, uppdatera, ta bort) för Product-entiteten,
 * samt vyer för att lista och visa produkter.
 */
final class ProductController extends AbstractController
{
    /**
     * Visar en enkel startsida för produktdelen.
     *
     * @return Response Renderar 'product/index.html.twig' vyn.
     */
    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    /**
     * Skapar en ny produkt med slumpmässigt namn och värde och sparar i databasen.
     *
     * @param ManagerRegistry $doctrine doctrine entity manager registry
     *
     * @return Response bekräftelse på sparad produkt med dess ID
     */
    #[Route('/product/create', name: 'product_create')]
    public function createProduct(
        ManagerRegistry $doctrine,
    ): Response {
        $entityManager = $doctrine->getManager();

        $product = new Product();
        $product->setName('Keyboard_num_'.rand(1, 9));
        $product->setValue(rand(100, 999));

        $entityManager->persist($product);
        $entityManager->flush();

        return new Response('Saved new product with id '.$product->getId());
    }

    /**
     * Hämtar och returnerar alla produkter i JSON-format.
     *
     * @param ProductRepository $productRepository repository för produktentiteten
     *
     * @return Response JSON med alla produkter
     */
    #[Route('/product/show', name: 'product_show_all')]
    public function showAllProduct(
        ProductRepository $productRepository,
    ): Response {
        $products = $productRepository->findAll();

        return $this->json($products);
    }

    /**
     * Hämtar och returnerar en produkt baserat på ID i JSON-format.
     * Returnerar 404 om produkten inte hittas.
     *
     * @param ProductRepository $productRepository repository för produktentiteten
     * @param int               $id                produktens ID
     *
     * @return Response JSON med produkten eller felmeddelande
     */
    #[Route('/product/show/{id}', name: 'product_by_id')]
    public function showProductById(
        ProductRepository $productRepository,
        int $id,
    ): Response {
        $product = $productRepository->find($id);

        if (!$product) {
            return $this->json(['error' => 'Product not found'], 404);
        }

        return $this->json($product);
    }

    /**
     * Tar bort en produkt baserat på ID.
     * Returnerar 404 om produkten inte finns.
     *
     * @param ManagerRegistry $doctrine doctrine entity manager registry
     * @param int             $id       produktens ID
     *
     * @return Response redirectar till vyn som visar alla produkter
     */
    #[Route('/product/delete/{id}', name: 'product_delete_by_id')]
    public function deleteProductById(
        ManagerRegistry $doctrine,
        int $id,
    ): Response {
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException('No product found for id '.$id);
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute('product_show_all');
    }

    /**
     * Uppdaterar värdet på en produkt baserat på ID.
     * Returnerar 404 om produkten inte finns.
     *
     * @param ManagerRegistry $doctrine doctrine entity manager registry
     * @param int             $id       produktens ID
     * @param int             $value    det nya värdet för produkten
     *
     * @return Response redirectar till vyn som visar alla produkter
     */
    #[Route('/product/update/{id}/{value}', name: 'product_update')]
    public function updateProduct(
        ManagerRegistry $doctrine,
        int $id,
        int $value,
    ): Response {
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException('No product found for id '.$id);
        }

        $product->setValue($value);
        $entityManager->flush();

        return $this->redirectToRoute('product_show_all');
    }

    /**
     * Renderar en Twig-vy som visar alla produkter.
     *
     * @param ProductRepository $productRepository repository för produktentiteten
     *
     * @return Response Renderar 'product/view.html.twig' med produkterna.
     */
    #[Route('/product/view', name: 'product_view_all')]
    public function viewAllProduct(
        ProductRepository $productRepository,
    ): Response {
        $products = $productRepository->findAll();

        $data = [
            'products' => $products,
        ];

        return $this->render('product/view.html.twig', $data);
    }

    /**
     * Renderar en Twig-vy med produkter som har ett värde större eller lika med angivet minimumvärde.
     *
     * @param ProductRepository $productRepository repository för produktentiteten
     * @param int               $value             minimumvärdet som produkternas värde ska uppfylla
     *
     * @return Response Renderar 'product/view.html.twig' med filtrerade produkter.
     */
    #[Route('/product/view/{value}', name: 'product_view_minimum_value')]
    public function viewProductWithMinimumValue(
        ProductRepository $productRepository,
        int $value,
    ): Response {
        $products = $productRepository->findByMinimumValue($value);

        $data = [
            'products' => $products,
        ];

        return $this->render('product/view.html.twig', $data);
    }
}
