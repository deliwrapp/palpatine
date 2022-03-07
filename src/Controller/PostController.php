<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Post;
use App\Repository\PostRepository;
use App\Form\PostFormType;

class PostController extends AbstractController
{
    /**
     * @Route("/posts", name="postsList")
     */
    public function index(PostRepository $postRepo): Response
    {
        $posts = $postRepo->findAll();

        return $this->render('post/post-list.html.twig', [
            'controller_name' => 'PostController',
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/post/show/{id}", name="postShow")
     */
    public function show(int $id, PostRepository $postRepository): Response
    {
        $post = $postRepository
            ->find($id);

        return $this->render('post/post-show.html.twig', [
            'post' => $post
        ]);
    }

    /**
     * @Route("/post/create", name="postCreate")
    */
    public function create(ManagerRegistry $doctrine, Request $request): Response
    {
        $post = new Post();

        $form = $this->createForm(PostFormType::class, $post, [
            'change' => 'Create'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $em = $doctrine->getManager();
            // Enregistre dans le cache de Doctrine
            $em->persist($post);
            // INSERT ... IN post -- Enregistre en base de donnée
            $em->flush();
            // Add comment to inform user of the post creation
            $this->addFlash(
                'info',
                'Saved new post with id '.$post->getId()
            );
            return $this->redirect($this->generateUrl('postsList', [
                'id' => $post->getId()
            ]));
        }
        
        return $this->render('post/post-edit.html.twig', [
            'controller_name' => 'PostController',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/post/edit/{id}", name="postEdit")
     */
    public function edit(ManagerRegistry $doctrine, Request $request, int $id): Response
    {
        $post = $doctrine->getRepository(Post::class);
        $post = $post->find($id);
        $form = $this->createForm(PostFormType::class, $post, [
            'change' => 'Edit'
        ]);


        if (!$post) {
            $this->addFlash(
                'warning',
                'There is no post  with id ' . $id
            );
            return $this->redirect($this->generateUrl('postList'));
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $post = $form->getData();
            $em->flush();
            $this->addFlash(
                'info',
                'Post updated'
            );
            return $this->redirect($this->generateUrl('postShow', [
                'id' => $post->getId()
            ]));
        }

        return $this->render(
            'post/post-edit.html.twig',
            [
                'form' => $form->createView(),
                'post' => $post
            ]
        );
    }

    /**
     * @Route("/post/delete/{id}", name="postDelete")
     */
    public function delete(int $id, ManagerRegistry $doctrine, Request $request): Response
    {
        $submittedToken = $request->request->get('token');

        // 'delete-post' is the same value used in the template to generate the token
        if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
            $em = $doctrine->getManager();
            $post = $doctrine->getRepository(Post::class);
            $post = $post->find($id);
            if (!$post) {
                $this->addFlash(
                    'warning',
                    'There is no post  with id ' . $id
                );
            } else {
                $em->remove($post);
                $em->flush();
                $this->addFlash(
                    'success',
                    'The post with ' . $id . ' have been deleted '
                );
            } 
        } else {
            $this->addFlash(
                'warning',
                'Your CSRF token is not valid ! '
            );
        }
        
        return $this->redirect($this->generateUrl('postsList'));
    }

}
