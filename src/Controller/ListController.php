<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Post;
use Symfony\Component\HttpFoundation\Request;

class ListController extends AbstractController
{
    #[Route('/lista', name: 'lista')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $posts = $entityManager->getRepository(Post::class)->findAll();
    
        return $this->render('lista/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete_post')]
    public function delete(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $post = $entityManager->getRepository(Post::class)->find($id);

        if ($post) {
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('lista');
    }
}
