<?php

namespace App\Blog\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\Persistence\ManagerRegistry;
use App\Blog\Entity\Post;
use App\Blog\Repository\PostRepository;
use App\Blog\Form\PostFormType;

/** 
 * Class EditorPostController
 * @package App\Blog\Controller
 * @IsGranted("ROLE_EDITOR",statusCode=401, message="No access! Get out!")
 * @Route("/editor/posts")
 */
class EditorPostController extends AbstractController
{
    /**
     * @Route("/", name="editor_post_list")
     */
    public function index(PostRepository $postRepo): Response
    {
        $posts = $postRepo->findAll();

        return $this->render('@blog-admin/post/post-list.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/create", name="editor_post_create")
    */
    public function create(
        ManagerRegistry $doctrine,
        Request $request,
        ParameterBagInterface $params
    ): Response
    {
        $post = new Post();
        $tplOptions = $params->get('posts_templates');
        $form = $this->createForm(PostFormType::class, $post, [
            'change' => 'Create',
            'tplOptions' => $tplOptions
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $em = $doctrine->getManager();
            // Enregistre dans le cache de Doctrine
            $post->setAuthor($this->getUser());
            $em->persist($post);
            // INSERT ... IN post -- Enregistre en base de donnée
            $em->flush();
            // Add comment to inform user of the post creation
            $this->addFlash(
                'info',
                'Saved new post with id '.$post->getId()
            );
            return $this->redirect($this->generateUrl('editor_post_list', [
                'id' => $post->getId()
            ]));
        }
        
        return $this->render('@blog-admin/post/post-edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/update/{id}", name="editor_post_edit")
     */
    public function edit(
        int $id,
        ManagerRegistry $doctrine,
        Request $request,
        ParameterBagInterface $params
    ): Response
    {
        $post = $doctrine->getRepository(Post::class);
        $post = $post->find($id);
        $tplOptions = $params->get('posts_templates');
        $form = $this->createForm(PostFormType::class, $post, [
            'change' => 'Edit',
            'tplOptions' => $tplOptions
        ]);

        if (!$post) {
            $this->addFlash(
                'warning',
                'There is no post  with id ' . $id
            );
            return $this->redirect($this->generateUrl('editor_post_list'));
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
            return $this->redirect($this->generateUrl('member_post_show', [
                'id' => $post->getId()
            ]));
        }

        return $this->render(
            '@blog-admin/post/post-edit.html.twig',
            [
                'form' => $form->createView(),
                'post' => $post
            ]
        );
    }

    /**
     * @Route("/delete/{id}", name="editor_post_delete")
     */
    public function delete(int $id, ManagerRegistry $doctrine, Request $request): Response
    {
        $submittedToken = $request->request->get('token');

        // 'delete-post' is the same value used in the template to generate the token
        if ($this->isCsrfTokenValid('delete-post', $submittedToken)) {
            $em = $doctrine->getManager();
            $post = $doctrine->getRepository(Post::class);
            $post = $post->find($id);
            if (!$post) {
                $this->addFlash(
                    'warning',
                    'There is no post  with id ' . $id
                );
            } else {
                $comments = $doctrine->getRepository(Comment::class);
                $comments = $comments->findBy(['post' => $id]);

                foreach ($comments as $com) {
                    $em->remove($com);
                }
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
        
        return $this->redirect($this->generateUrl('editor_post_list'));
    }

}
