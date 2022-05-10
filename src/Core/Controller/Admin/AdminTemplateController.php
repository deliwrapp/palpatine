<?php

namespace App\Core\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use App\Core\Entity\Template;
use App\Core\Repository\TemplateRepository;
use App\Core\Form\TemplateFormType;

/**
 * @Route("/admin/template")
 */
class AdminTemplateController extends AbstractController
{
    public function __construct(
        TemplateRepository $templateRepo,
        Environment $twig
    )
    {
        $this->templateRepo = $templateRepo;
        $this->tplLoader = $twig->getLoader();
    }

    /**
     * @Route("/", name="admin_template_list")
     */
    public function index(): Response
    {
        try {
            $templates = $this->templateRepo->findAll();
            return $this->render('@core-admin/template/template-list.html.twig', [
                'templates' => $templates
            ]);
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('AdminDashboard'));
        } 
    }

    /**
     * @Route("/create", name="admin_template_create")
    */
    public function create(Request $request): Response
    {
        try {
            $template = new Template();
            $form = $this->createForm(TemplateFormType::class, $template, [
                'submitBtn' => 'Create'
            ]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $tplPath = $form->get('templatePath')->getData();
                if ($this->tplLoader->exists($tplPath)) {
                    $template = $form->getData();
                    $this->templateRepo->add($template);
                    $this->addFlash(
                        'info',
                        'Saved new Template with id '.$template->getId()
                    );
                } else {
                    $this->addFlash(
                        'danger',
                        'The defined path for the template does not exists'
                    );
                }
                return $this->redirect($this->generateUrl('admin_template_edit', [
                    'id' => $template->getId()
                ]));
            }
            
            return $this->render('@core-admin/template/template-edit.html.twig', [
                'form' => $form->createView()
            ]);
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_template_list'));
        }
    }

    /**
     * @Route("/update/{id}", name="admin_template_edit")
     */
    public function edit(int $id, Request $request): Response
    {
        try {
            $template = $this->templateRepo->find($id);
            if (!$template) {
                $this->addFlash(
                    'warning',
                    'There is no template  with id ' . $id
                );
                return $this->redirect($this->generateUrl('admin_template_list'));
            }
            $form = $this->createForm(TemplateFormType::class, $template, [
                'submitBtn' => 'Edit'
            ]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $tplPath = $form->get('templatePath')->getData();
                if ($this->tplLoader->exists($tplPath)) {
                    $template = $form->getData();
                    $this->templateRepo->flush();
                    $this->addFlash(
                        'info',
                        'Template updated'
                    );
                } else {
                    $this->addFlash(
                        'danger',
                        'The defined path for the template does not exists'
                    );
                }
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
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_template_list'));
        }
    }

    /**
     * @Route("/show/{id}", name="admin_template_show")
     */
    public function show(int $id): Response
    {
        try {
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
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_template_list'));
        }
    }

    /**
     * @Route("/delete/{id}", name="admin_template_delete")
     */
    public function delete(int $id, Request $request): Response
    {
        try {
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
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
        }
        return $this->redirect($this->generateUrl('admin_template_list'));
    }

}
