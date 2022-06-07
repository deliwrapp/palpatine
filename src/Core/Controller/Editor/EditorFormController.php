<?php

namespace App\Core\Controller\Editor;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Core\Entity\FormModel;
use App\Core\Entity\FormModelField;
use App\Core\Repository\FormModelRepository;
use App\Core\Repository\FormModelFieldRepository;
use App\Core\Form\FormModelFormType;
use App\Core\Form\FormModelFieldFormType;
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

    /** @var FormModelFieldRepository */
    private $formFieldRepo;

    /** @var FormModelFactory */
    private $formFactory;

    /** @var FormModelVerificator */
    private $formVerif;

    public function __construct(
        FormModelRepository $formRepo,
        FormModelFieldRepository $formFieldRepo,
        FormModelFactory $formFactory,
        FormModelVerificator $formVerif
    )
    {
        $this->formRepo = $formRepo; 
        $this->formFieldRepo = $formFieldRepo;       
        $this->formFactory = $formFactory;       
        $this->formVerif = $formVerif;
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
            return $this->render('@core-admin/form/editor/form-list.html.twig', [
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
            $formModel = $this->formFactory->createFormModel();
            $this->formRepo->add($formModel);
            $formModel->setName('form-'.$formModel->getId());
            $this->formRepo->flush();
            $this->addFlash(
                'info',
                'Saved new Form with id '.$formModel->getId()
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
            $formModelField = $this->formFactory->createFormField();
            $form = $this->createForm(FormModelFormType::class, $formModel, [
                'submitBtn' => 'Edit'
            ]);
            $formField = $this->createForm(FormModelFieldFormType::class, $formModelField);
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
                    'formModel' => $formModel,
                    'formField' => $formField->createView()
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
            return $this->render('@core-admin/form/editor/form-show.html.twig', [
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
                $this->formRepo->remove($formModel);
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
    public function addFormField(Request $request, int $formId, string $type = "text"): RedirectResponse
    {
        try {
            $formModel = $this->formVerificator($formId);
            $formModel = $this->formFactory->addField($formModel, $type);
            $this->addFlash(
                'info',
                'New Form Field'
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
     * @param int $formId
     * @param int $formFieldId
     * @param string $type = "page"
     * @Route("/{formId}/edit-field/{formFieldId}", name="editor_form_field_edit")
     * @return RedirectResponse
     */
    public function editFormField(Request $request, int $formId, int $formFieldId): RedirectResponse
    {
        try {
            
            $formModel = $this->formVerificator($formId);
            $formField = $this->formFieldVerificator($formFieldId);
            $this->formFieldLinkVerificator($formModel, $formField);
            $formField = $this->createForm(FormModelFieldFormType::class, $formField);
            $formField->handleRequest($request);
            if ($formField->isSubmitted() && $formField->isValid()) {
                $formField = $formField->getData();
                $this->formFieldRepo->flush();
                $this->addFlash(
                    'info',
                    'Form Field updated'
                );
            }
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
     * FormModel Delete field
     * 
     * @param Request $request
     * @param int $formId
     * @Route("/{formId}/delete-field/{formFieldId}", name="editor_form_field_delete")
     * @return RedirectResponse
     */
    public function deleteFormField(Request $request, int $formId, int $formFieldId): RedirectResponse
    {
        try {
            $submittedToken = $request->request->get('token'); 
            if ($this->isCsrfTokenValid('delete-field-form', $submittedToken)) {
                $formModel = $this->formVerificator($formId);
                $formField = $this->formFieldVerificator($formFieldId);
                $this->formFieldLinkVerificator($formModel, $formField);
                $this->formFactory->removeField($formModel, $formField);
                $this->addFlash(
                    'info',
                    'Form Field deleted'
                );
            } else {
                $this->addFlash(
                    'warning',
                    'Your CSRF token is not valid ! '
                );
            }
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
     * Form Field change position
     * 
     * @param int $formId
     * @param int $formFIeldId
     * @param int $position
     * @Route("{formId}/field/{formFieldId}/move-to-position/{position}", name="editor_form_field_position")
     * @return RedirectResponse
     */
    public function moveFieldTo(int $formId, int $formFieldId, int $position): RedirectResponse
    {
        try {
            $formModel = $this->formVerificator($formId);
            $formField = $this->formFieldVerificator($formFieldId);
            $this->formFieldLinkVerificator($formModel, $formField);
            
            if ($position == 0 || $position > count($formModel->getFields())) {
                $this->addFlash(
                    'warning',
                    'You can not move Field out !'
                );
                return $this->redirect($this->generateUrl('editor_form_edit', [
                    'id' => $formId
                ]));
            }

            $formFieldToSwitch = $this->formFieldRepo->findOneBy([
                'formModel' => $formModel->getId(),
                'position' => $position
            ]);
            $actualPosition = $formField->getPosition();
            $formFieldToSwitch->setPosition($actualPosition);
            $formField->setPosition($position);
            
            $this->formFieldRepo->flush();
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
        }
        return $this->redirect($this->generateUrl('editor_form_edit', [
            'id' => $formModel->getId()
        ]));
    }

    /**
     * Page Reorder Field on the Form
     * 
     * @param int $pageId
     * @Route("/{formId}/fields/re-order", name="editor_form_fields_reorder")
     * @return RedirectResponse
     */
    public function reOrderFieldsOnForm(int $formId): RedirectResponse
    {
        try {
            $formModel =$this->formVerificator($formId);
            $formModel = $this->formFactory->reOrderFormFields($formModel);
            $this->addFlash(
                'success',
                'The Form Model Fields have been reordered'
            );
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
        }
        return $this->redirect($this->generateUrl('editor_form_edit', [
            'id' => $formId
        ]));
    }

    /**
     * Test if FormModel exists and return it, or redirect to FormModel list index with an error message
     * 
     * @param int $formId
     * @return FormModel $formModel
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
    /**
     * Test if FormModelField exists and return it, or redirect to FormModel list index with an error message
     * 
     * @param int $formFieldId
     * @return FormModelField $formField
     * @return RedirectResponse
     */
    public function formFieldVerificator(int $formFieldId)
    {
        $formField = $this->formFieldRepo->find($formFieldId);
        if (!$formField) {
            $this->addFlash(
                'warning',
                'There is no Form Field  with id ' . $formFieldId
            );
            return $this->redirect($this->generateUrl('editor_form_list'));
        }
        return $formField;
    }

    /**
     * Test if FormMode and FormField are linked, return true or redirect to form edit page with an error message
     * 
     * @param PageBlock $pageBlock
     * @param Page $pageId
     * @return bool
     * @return RedirectResponse
     */
    public function formFieldLinkVerificator(FormModel $formModel, FormModelField $formModelField)
    {
        if ($formModel !== $formModelField->getFormModel()) {
            $this->addFlash(
                'warning',
                'The Form and the Field are not linked '
            );
            return $this->redirect($this->generateUrl('editor_form_edit', [
                'id' => $formModel->getId()
            ]));
        }
        return true;
    }
}
