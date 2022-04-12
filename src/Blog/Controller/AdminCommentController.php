<?php

namespace App\Blog\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Blog\Entity\Comment;
use App\Blog\Repository\CommentRepository;
use App\Blog\Form\AdminCommentFormType;

/**
 * @Route("/admin/comments")
 */
class AdminCommentController extends AbstractController
{
    /**
     * @Route("/", name="AdminCommentList")
     */
    public function index(CommentRepository $commentRepo): Response
    {
        $comments = $commentRepo->findAll();

        return $this->render('@blog-admin/comment/comment-list.html.twig', [
            'comments' => $comments
        ]);
    }

    /**
     * @Route("/update/{id}", name="AdminCommentEdit")
     */
    public function edit(ManagerRegistry $doctrine, Request $request, int $id): Response
    {
        $comment = $doctrine->getRepository(Comment::class);
        $comment = $comment->find($id);
        $form = $this->createForm(AdminCommentFormType::class, $comment, [
            'change' => 'Edit'
        ]);

        if (!$comment) {
            $this->addFlash(
                'warning',
                'There is no comment  with id ' . $id
            );
            return $this->redirect($this->generateUrl('AdminCommentList'));
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $comment = $form->getData();
            $em->flush();
            $this->addFlash(
                'info',
                'Comment updated'
            );
            return $this->redirect($this->generateUrl('MemberPostShow', [
                'id' => $comment->getPost()->getId()
            ]));
        }

        return $this->render(
            '@blog-admin/comment/comment-edit.html.twig',
            [
                'form' => $form->createView(),
                'comment' => $comment
            ]
        );
    }

    /**
     * @Route("/delete/{id}", name="AdminCommentDelete")
     */
    public function delete(int $id, ManagerRegistry $doctrine, Request $request): Response
    {
        $submittedToken = $request->request->get('token');
        
        if ($this->isCsrfTokenValid('delete-comment', $submittedToken)) {
            $em = $doctrine->getManager();
            $comment = $doctrine->getRepository(Comment::class);
            $comment = $comment->find($id);
            if (!$comment) {
                $this->addFlash(
                    'warning',
                    'There is no comment  with id ' . $id
                );
            } else {
                $em->remove($comment);
                $em->flush();
                $this->addFlash(
                    'success',
                    'The comment with ' . $id . ' have been deleted '
                );
            } 
        } else {
            $this->addFlash(
                'warning',
                'Your CSRF token is not valid ! '
            );
        }
        
        return $this->redirect($this->generateUrl('AdminCommentList'));
    }

}
