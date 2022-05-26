<?php

namespace App\Core\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use Symfony\Component\HttpKernel\KernelInterface;
use App\Core\Command\SystemCommandHandler;

/**
 * Class AdminCommandSystemController
 * @package App\Core\Controller\Admin
 * @IsGranted("ROLE_ADMIN",statusCode=401, message="No access! Get out!")
 * @Route("/admin/system")
 */
class AdminCommandSystemController extends AbstractController
{

    /** @var SystemCommandHandler */
    private $systemCommand;


    public function __construct(
        SystemCommandHandler $systemCommand
    )
    {
        $this->systemCommand = $systemCommand;
    }

    /**
     * Admin System Dashboard 
     * 
     * @Route("/", name="admin_command_system_dashboard")
     * @return Response
     */
    public function index(): Response
    {

        return $this->render('@core-admin/system/admin/dashboard.html.twig', []);
    }

    /**
     * command_cache_clear ->clear cache
     * 
     * @param string $env = null (options = dev/prod)
     * @Route("/command/cache/clear/{env}",
     * name="admin_system_command_cache_clear",
     * defaults={"env": null}
     * )
     */
    public function commandCacheClear(string $env = null)
    {
        try {
            $commandResponse = $this->systemCommand->doCommand('cache:clear', $env);
            $this->addFlash(
                'success',
                'Cache Clear Command Executed'
            );
            return new Response($commandResponse);
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_command_system_dashboard'));
        } 
    }

    /**
     * command_cache_warmup -> cache warmup
     * 
     * @param string $env = null (options = dev/prod)
     * @Route("/command/cache/warmup/{env}",
     * name="admin_system_command_cache_warmup",
     * defaults={"env": null}
     * )
     */
    public function commandCacheWarmup(string $env = null)
    {
        try {
            $commandResponse = $this->systemCommand->doCommand('cache:warmup', $env);
            $this->addFlash(
                'success',
                'Cache Warmup Command Executed'
            );
            return new Response($commandResponse);
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_command_system_dashboard'));
        }
    }
}

