<?php

namespace App\Post\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Post\Entity\Post;
use App\Comment\Entity\Comment;
use App\Post\Repository\PostRepository;
use App\Comment\Form\CommentFormType;

/**
 * @Route("/posts")
 */
class MemberPostController extends AbstractController
{
    /**
     * @Route("/", name="MemberPostsList")
     */
    public function index(PostRepository $postRepo): Response
    {
        $posts = $postRepo->findAll();

        return $this->render('@post/member/post-list.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/show/{id}/{commentId}", name="MemberPostShow")
     */
    public function show(int $id, int $commentId = null, ManagerRegistry $doctrine): Response
    {
        $post = $doctrine->getRepository(Post::class);
        $post = $post->find($id);

        if (!$post) {
            $this->addFlash(
                'warning',
                'There is no post  with id ' . $id
            );
            return $this->redirect($this->generateUrl('AdminPostList'));
        }

        if ($commentId) {
            $comment = $doctrine->getRepository(Comment::class);
            $comment = $comment->find($commentId);
            $submitBtn = 'Edit';

            if (!$comment) {
                $this->addFlash(
                    'warning',
                    'There is no comment  with id ' . $id
                );
                return $this->redirect($this->generateUrl('MemberPostShow', [
                    'id' => $id
                ]));
            }
        } else { 
            $comment = new Comment;
            $submitBtn = 'Create';
        }
        
        // COMMENT FORM BUILDER and CONFIGURE THE ACTION ROUTE
        $form = $this->createForm(
            CommentFormType::class,
            $comment,
            [
                'submitBtn' => $submitBtn,
                'action' => $this->generateUrl('MemberCommentUpdate', [
                    'id' => $post->getId(),
                    'commentId' => $commentId
                ]),
                'method' => 'POST',
            ]
        );

        $comments = $doctrine->getRepository(Comment::class);
        $comments = $comments->findBy(['post' => $id]);
        
        return $this->render('@post/member/post-show.html.twig', [
            'post' => $post,
            'comments' => $comments,
            'form' => $form->createView(),
            'editComment' => $commentId
        ]);
    }

}
