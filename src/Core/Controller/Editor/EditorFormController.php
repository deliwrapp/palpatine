<?php

namespace App\Core\Controller\Editor;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Core\Entity\FormModel;
use App\Core\Repository\FormModelRepository;
use App\Core\Form\FormModelFormType;
use App\Core\Factory\FormModelFactory;
use App\Core\Verificator\FormModelVerificator;

/**
 * Class EditorFormController
 * @package App\Core\Controller\Editor
 * @IsGranted("ROLE_EDITOR",statusCode=401, message="No access! Get out!")
 * @Route("/editor/form")
 */
class EditorFormController extends AbstractController
{
    /** @var FormModelRepository */
    private $formRepo;

    /** @var FormModelFactory */
    private $formFactory;

    /** @var FormModelVerificator */
    private $formVerif;

    public function __construct(
        FormModelRepository $formRepo,
        FormModelFactory $formFactory,
        FormModelVerificator $formVerif
    )
    {
        $this->menuRepo = $formRepo;      
        $this->menuFactory = $formFactory;       
        $this->menuVerif = $formVerif;
    }

    /**
     * FormModel List Index
     * 
     * @Route("/", name="editor_form_list")
     * @return RedirectResponse
     */
    public function index(): Response
    {
        try {
            $formModels = $this->formRepo->findAll();
            return $this->render('@core-admin/menu/editor/form-list.html.twig', [
                'formModels' => $formModels
            ]);
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_dashboard_redirect'));
        } 
    }

    /**
     * FormModel Create
     * 
     * @param Request $request
     * @Route("/create", name="editor_form_create") 
     * @return RedirectResponse
    */
    public function create(Request $request): RedirectResponse
    {
        try {
            $formModel = new FormModel();
            $this->formRepo->add($formModel);
            $formModel->setName('form-'.$formModel->getId());
            $this->formRepo->flush();
            $this->addFlash(
                'info',
                'Saved new Menu with id '.$formModel->getId()
            );
            return $this->redirect($this->generateUrl('editor_form_edit', [
                'id' => $formModel->getId()
            ]));
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('editor_form_list'));
        }
    }

    /**
     * FormModel Edit
     * 
     * @param int $id
     * @param Request $request
     * @Route("/update/{id}", name="editor_form_edit") 
     * @return Response 
     * @return RedirectResponse
     */
    public function edit(int $id, Request $request): Response
    {
        try {
            $formModel = $this->formVerificator($id);
            $form = $this->createForm(FormModelFormType::class, $formModel, [
                'submitBtn' => 'Edit'
            ]);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                
                $formModel = $form->getData();
                $this->formRepo->flush();
                $this->addFlash(
                    'info',
                    'FormModel updated'
                );
                return $this->redirect($this->generateUrl('editor_form_edit', [
                    'id' => $formModel->getId()
                ]));
            } 
            return $this->render(
                '@core-admin/form/editor/form-edit.html.twig',
                [
                    'form' => $form->createView(),
                    'formModel' => $formModel
                ]
            );
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('editor_form_list'));
        }
    }

    /**
     * FormModel Duplicate
     * 
     * @param int $id
     * @param Request $request
     * @Route("/duplicate/{id}", name="editor_form_duplicate")
     * @return RedirectResponse
     */
    public function duplicate(int $id, Request $request): RedirectResponse
    {
        try {
            $formModel = $this->formVerificator($id);
            $newFormModel = new FormModel;
            $newFormModel = $formModel->duplicate($newFormModel);
            $this->formRepo->add($newFormModel);
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
        }
        return $this->redirect($this->generateUrl('editor_form_list'));
    }

    /**
     * FormModel Show
     * 
     * @param int $id
     * @Route("/show/{id}", name="editor_form_show")
     * @return Response
     */
    public function show(int $id): Response
    {
        try {
            $formModel = $this->formVerificator($id);
            return $this->render('@core-admin/menu/editor/form-show.html.twig', [
                'formModel' => $formModel
            ]);
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('editor_form_list'));
        }
    }

    /**
     * FormModel Delete
     * 
     * @param int $id
     * @param Request $request
     * @Route("/delete/{id}", name="editor_form_delete")
     * @return RedirectResponse
     */
    public function delete(int $id, Request $request): RedirectResponse
    {
        try {
            $submittedToken = $request->request->get('token'); 
            if ($this->isCsrfTokenValid('delete-form', $submittedToken)) {
                $formModel = $this->formVerificator($id);
                $this->mformRepo->remove($formModel);
                $this->addFlash(
                    'success',
                    'The FormModel with ' . $id . ' have been deleted '
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
        return $this->redirect($this->generateUrl('editor_form_list'));
    }

    /**
     * FormModel Add Field
     * 
     * @param Request $request
     * @param int $formId
     * @param string $type = "page"
     * @Route("/add-field-to/{formId}/{type}", name="editor_form_field_create")
     * @return RedirectResponse
     */
    public function addField(Request $request, int $formId, string $type = "text"): RedirectResponse
    {
        try {
            $formModel = $this->formVerificator($formId);
            
            $this->addFlash(
                'info',
                'New Menu Item'
            );
            return $this->redirect($this->generateUrl('editor_form_edit', [
                'id' => $formId
            ]));
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('editor_form_list'));
        }
    }

    /**
     * FormModel Edit field
     * 
     * @param Request $request
     * @param int $menuItemId
     * @param int $formId
     * @param string $type = "page"
     * @Route("/edit-field/{formId}", name="editor_form_field_edit")
     * @return Response
     * @return RedirectResponse
     */
    public function editMenuItem(Request $request, int $formId): Response
    {
        try {
            $formModel = $this->formVerificator($formId);
            
            return $this->render(
                '@core-admin/menu/editor/form-field-edit.html.twig',
                [
                    
                    'formModel' => $formModel
                ]
            );
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('editor_form_list'));
        }
        
    }

    /**
     * FormModel Delete field
     * 
     * @param Request $request
     * @param int $formId
     * @Route("/delete-field/{formId}", name="editor_menu_item_delete")
     * @return RedirectResponse
     */
    public function deleteMenuItem(Request $request, int $formId): RedirectResponse
    {
        try {
            $submittedToken = $request->request->get('token'); 
            if ($this->isCsrfTokenValid('delete-field-form', $submittedToken)) {
                $this->formVerificator($formId);
                
                $this->addFlash(
                    'info',
                    'Menu Item deleted'
                );
            } else {
                $this->addFlash(
                    'warning',
                    'Your CSRF token is not valid ! '
                );
            }
            return $this->redirect($this->generateUrl('editor_mform_edit', [
                'id' => $formId
            ]));
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('editor_form_list'));
        }
    }
    
    /**
     * Test if FormModel exists and return it, or redirect to FormModel list index with an error message
     * 
     * @param int $formId
     * @return FormIModel $formModel
     * @return RedirectResponse
     */
    public function formVerificator(int $formId)
    {
        $formModel = $this->formRepo->find($formId);
        if (!$formModel) {
            $this->addFlash(
                'warning',
                'There is no FormModel  with id ' . $formId
            );
            return $this->redirect($this->generateUrl('editor_form_list'));
        }
        return $formModel;
    }
}
