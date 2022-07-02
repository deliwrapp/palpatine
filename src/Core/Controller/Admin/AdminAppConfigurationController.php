<?php

namespace App\Core\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Core\Form\ModuleUploadFormType;
use App\Core\Services\ModuleLoader;

/**
 * Class AdminAppConfigurationController
 * @package App\Core\Controller\Admin
 * @IsGranted("ROLE_ADMIN",statusCode=401, message="No access! Get out!")
 * @Route("/admin/app")
 */
class AdminAppConfigurationController extends AbstractController
{
    /** @var ModuleLoader */
    private $moduleLoader;
 
    public function __construct(
        ModuleLoader $moduleLoader
    )
    {
        $this->moduleLoader = $moduleLoader;
    }

    /**
     * Admin App configuration Dashboard
     * 
     * @Route("/", name="admin_app_configuration_dashboard") 
     * @return Response
     */
    public function index(): Response
    {
        $uploadModuleform = $this->createForm(ModuleUploadFormType::class, [], [
            'action' => $this->generateUrl('admin_module_install'),
            'method' => 'POST',
        ]);
        $modulesInfo = $this->moduleLoader->getModulesInfo();
        return $this->render('@core-admin/app/admin/dashboard.html.twig',  [
            'uploadModuleform' => $uploadModuleform->createView(),
            'modulesInfo' => $modulesInfo
        ]);
    }

}
