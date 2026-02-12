<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use App\Form\CategoryType;
use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\CommentsRepository;
use App\Entity\Comments; 
use Doctrine\ORM\EntityManagerInterface;


#[IsGranted('ROLE_ADMIN')]
final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin_dashboard')]
public function index(CommentsRepository $commentsRepository): Response
{
    $categoryForm = $this->createForm(CategoryType::class, new Category(), [
        'action' => $this->generateUrl('app_category_new'),
        'method' => 'POST',
    ]);

    $postForm = $this->createForm(PostType::class, new Post(), [
        'action' => $this->generateUrl('app_admin_post_new'),
        'method' => 'POST',
    ]);

    $pendingComments = $commentsRepository->findBy(
        ['status' => 'pending'],
        ['id' => 'DESC']
    );

    return $this->render('admin/index.html.twig', [
        'categoryForm' => $categoryForm->createView(),
        'postForm' => $postForm->createView(),
        'pendingComments' => $pendingComments,
    ]);
}
     #[Route('/{id}/approve', name: 'app_admin_comments_approve', methods: ['POST'])]
    public function approve(Comments $comment, EntityManagerInterface $em): Response
    {
        $comment->setStatus('approved');
        $em->flush();

        return $this->redirectToRoute('app_admin_dashboard');
    }

    #[Route('/{id}/reject', name: 'app_admin_comments_reject', methods: ['POST'])]
    public function reject(Comments $comment, EntityManagerInterface $em): Response
    {
        $comment->setStatus('rejected');
        $em->flush();

        return $this->redirectToRoute('app_admin_dashboard');
    }
}