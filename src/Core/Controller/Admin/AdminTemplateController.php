<?php

namespace App\Core\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use App\Core\Entity\Template;
use App\Core\Repository\TemplateRepository;
use App\Core\Form\TemplateFormType;

/**
 * Class AdminTemplateController
 * @package App\Core\Controller\Admin
 * @Route("/admin/template")
 */
class AdminTemplateController extends AbstractController
{
    /** @var TemplateRepository */
    private $templateRepo;

    /** @var MenuFactory */
    private $twig;

    public function __construct(
        TemplateRepository $templateRepo,
        Environment $twig
    )
    {
        $this->templateRepo = $templateRepo;
        $this->tplLoader = $twig->getLoader();
    }

    /**
     * Template List Index
     * @Route("/", name="admin_template_list")
     * @return Response
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
     * Create Template
     * 
     * @param Request $request
     * @Route("/create", name="admin_template_create")
     * @return Response
     * @return RedirectResponse
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
     * Edit Template
     * 
     * @param int $id
     * @param Request $request
     * @Route("/update/{id}", name="admin_template_edit")
     * @return Response
     * @return RedirectResponse
     */
    public function edit(int $id, Request $request): Response
    {
        try {
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
     * Show Template
     * 
     * @param int $id
     * @Route("/show/{id}", name="admin_template_show")
     * @return Response
     * @return RedirectResponse
     */
    public function show(int $id): Response
    {
        try {
            $template = $this->templateVerificator($id);
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
     
     */
    /**
     * Delete Template
     * 
     * @param int $id
     * @param Request $request
     * @Route("/delete/{id}", name="admin_template_delete")
     * @return RedirectResponse
     */
    public function delete(int $id, Request $request): Response
    {
        try {
            $submittedToken = $request->request->get('token'); 
            if ($this->isCsrfTokenValid('delete-template', $submittedToken)) {
                $template = $this->templateVerificator($id);
                $this->templateRepo->remove($template);
                $this->addFlash(
                    'success',
                    'The Template with ' . $id . ' have been deleted '
                ); 
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

    
    /**
     * Test if template exists and return it, or redirect to template list index with an error message
     * 
     * @param int $templateId
     * @return Template
     * @return RedirectResponse
     */
    public function templateVerificator(int $templateId)
    {
        $template = $this->templateRepo->find($templateId);
        if (!$template) {
            $this->addFlash(
                'warning',
                'There is no Template  with id ' . $templateId
            );
            return $this->redirect($this->generateUrl('admin_template_list'));
        }
        return $template;
    }

}
