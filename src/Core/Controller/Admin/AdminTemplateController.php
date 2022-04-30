<?php

namespace App\Core\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Core\Entity\Template;
use App\Core\Repository\TemplateRepository;
use App\Core\Form\TemplateFormType;

/**
 * @Route("/admin/template")
 */
class AdminTemplateController extends AbstractController
{
    public function __construct(
        TemplateRepository $templateRepo
    )
    {
        $this->templateRepo = $templateRepo;
    }

    /**
     * @Route("/", name="admin_template_list")
     */
    public function index(): Response
    {
        $templates = $this->templateRepo->findAll();

        return $this->render('@core-admin/template/template-list.html.twig', [
            'templates' => $templates
        ]);
    }

    /**
     * @Route("/create", name="admin_template_create")
    */
    public function create(Request $request): Response
    {
        $template = new Template();
        $form = $this->createForm(TemplateFormType::class, $template, [
            'submitBtn' => 'Create'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $template = $form->getData();
            $this->templateRepo->add($template);
            $this->addFlash(
                'info',
                'Saved new Template with id '.$template->getId()
            );
            return $this->redirect($this->generateUrl('admin_template_edit', [
                'id' => $template->getId()
            ]));
        }
        
        return $this->render('@core-admin/template/template-edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/update/{id}", name="admin_template_edit")
     */
    public function edit(int $id, Request $request): Response
    {
        $template = $this->templateRepo->find($id);
        $form = $this->createForm(TemplateFormType::class, $template, [
            'submitBtn' => 'Edit'
        ]);

        if (!$template) {
            $this->addFlash(
                'warning',
                'There is no template  with id ' . $id
            );
            return $this->redirect($this->generateUrl('admin_template_list'));
        }
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $template = $form->getData();
            $this->templateRepo->flush();
            $this->addFlash(
                'info',
                'Template updated'
            );
            return $this->redirect($this->generateUrl('admin_template_edit', [
                'id' => $template->getId()
            ]));
        }

        return $this->render(
            '@core-admin/template/template-edit.html.twig',
            [
                'form' => $form->createView(),
                'template' => $template
            ]
        );
    }

    /**
     * @Route("/show/{id}", name="admin_template_show")
     */
    public function show(int $id): Response
    {
        $template = $this->templateRepo->find($id);

        if (!$template) {
            $this->addFlash(
                'warning',
                'There is no template  with id ' . $id
            );
            return $this->redirect($this->generateUrl('admin_template_list'));
        }
       
        return $this->render('@core-admin/template/template-show.html.twig', [
            'template' => $template
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_template_delete")
     */
    public function delete(int $id, Request $request): Response
    {
        $submittedToken = $request->request->get('token');
        
        if ($this->isCsrfTokenValid('delete-template', $submittedToken)) {
            $template = $this->templateRepo->find($id);
            if (!$template) {
                $this->addFlash(
                    'warning',
                    'There is no template  with id ' . $id
                );
            } else {                
                $this->templateRepo->remove($template);
                $this->addFlash(
                    'success',
                    'The Template with ' . $id . ' have been deleted '
                );
            } 
        } else {
            $this->addFlash(
                'warning',
                'Your CSRF token is not valid ! '
            );
        }        
        return $this->redirect($this->generateUrl('admin_template_list'));
    }

}
