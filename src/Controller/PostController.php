<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\Comments;
use App\Form\CommentsType;

final class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post_index')]
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findBy([], ['publishedAt' => 'DESC']),
        ]);
    }

    #[Route('/post/{id}', name: 'app_post_show')]
    public function show(Post $post,Request $request,EntityManagerInterface $entityManager): 
    Response {
    $comment = new Comments();
    $form = $this->createForm(CommentsType::class, $comment);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid() && $this->getUser()) {

        $comment->setPost($post);              
        $comment->setUsers($this->getUser());  
        $comment->setStatus('pending');

        $entityManager->persist($comment);
        $entityManager->flush();

        return $this->redirectToRoute('app_post_show', [
            'id' => $post->getId()
        ]);
    }

    return $this->render('post/show.html.twig', [
        'post' => $post,
        'commentForm' => $form->createView(),
    ]);
}

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/post/new', name: 'app_post_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new Post();

        if (method_exists($post, 'setAuthor') && $this->getUser()) {
            $post->setAuthor($this->getUser());
        }
        if (method_exists($post, 'setPublishedAt')) {
            $post->setPublishedAt(new \DateTimeImmutable());
        }

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_post_index');
        }

        return $this->render('post/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
