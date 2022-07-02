<?php

namespace App\Core\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Core\Manager\ModuleManager;
use App\Core\Form\ModuleUploadFormType;

/**
 * Class AdminAppConfigurationController
 * @package App\Core\Controller\Admin
 * @IsGranted("ROLE_ADMIN",statusCode=401, message="No access! Get out!")
 * @Route("/admin/module")
 */
class AdminModuleController extends AbstractController
{
    /** @var ModuleManager $moduleManager */
    private $moduleManager;

    public function __construct(
        ModuleManager $moduleManager
    )
    {
        $this->moduleManager = $moduleManager;
    }

    /**     
     * Install Module Handler
     * 
     * @param Request $request
     * @Route("/install", name="admin_module_install")   
     * @return RedirectResponse
    */
    public function install(Request $request): RedirectResponse
    {
        try {
            $uploadModuleform = $this->createForm(ModuleUploadFormType::class, [], [
                'action' => $this->generateUrl('admin_module_install'),
                'method' => 'POST',
            ]);
            $uploadModuleform->handleRequest($request);
            
            if ($uploadModuleform->isSubmitted() && $uploadModuleform->isValid()) 
            {
                $moduleToUpload = $uploadModuleform->get('upload_module')->getData();
                if ($moduleToUpload) 
                {
                    // upload the file and save it
                    $module = $this->moduleManager->install($moduleToUpload);
                    if ($module instanceof FileException || $module instanceof \Exception) {
                        $this->addFlash(
                            'danger',
                            'error.module.module_upload_failed'
                        );
                        $this->addFlash(
                            'danger',
                            $module->getMessage()
                        );
                    } else {
                        $this->addFlash(
                            'success',
                            'module.module_install_success'
                        );
                    }
                }   
            }
            else {
                $this->addFlash(
                    'danger',
                    'error.module.upload_module_failed'
                );
            }
            return $this->redirect($this->generateUrl('admin_app_configuration_dashboard'));
    
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_app_configuration_dashboard'));
        }
    }

}
