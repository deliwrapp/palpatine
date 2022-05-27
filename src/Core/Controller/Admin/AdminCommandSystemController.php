<?php

namespace App\Core\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * commandCacheClear ->clear cache
     * 
     * @param string $env = null (options = dev/prod)
     * @Route("/command/cache/clear/{env}",
     * name="admin_system_command_cache_clear",
     * defaults={"env": null}
     * )
     * 
     * @return RedirectResponse
     */
    public function commandCacheClear(string $env = null) :RedirectResponse
    {
        try {
            $commandResponse = $this->systemCommand->doCommand('cache:clear', $env);
            $this->addFlash(
                'success',
                $commandResponse
            );
            return $this->redirect($this->generateUrl('admin_command_system_dashboard'));
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_command_system_dashboard'));
        } 
    }

    /**
     * commandCacheWarmup -> cache warmup
     * 
     * @param string $env = null (options = dev/prod)
     * @Route("/command/cache/warmup/{env}",
     * name="admin_system_command_cache_warmup",
     * defaults={"env": null}
     * )
     * 
     * @return RedirectResponse
     */
    public function commandCacheWarmup(string $env = null)
    {
        try {
            $commandResponse = $this->systemCommand->doCommand('cache:warmup', $env);
            $this->addFlash(
                'success',
                $commandResponse
            );
            return $this->redirect($this->generateUrl('admin_command_system_dashboard'));
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_command_system_dashboard'));
        }
    }

    /**
     * commandDatabaseUpdateDump -> cache warmup
     * 
     * @param string $opt = dump (options = dump/force)
     * @Route("/command/database/update/{opt}",
     * name="admin_system_command_database_update",
     * defaults={"opt": "dump"}
     * )
     * 
     * @return RedirectResponse
     */
    public function commandDatabaseUpdate(string $opt = "dump")
    {
        try {
            if ($opt == 'dump') {
                $commandResponse = $this->systemCommand->doCommand('doctrine:schema:update', 'dev', ['--dump-sql' => true]);
            }
            elseif ($opt == 'force') {
                $commandResponse = $this->systemCommand->doCommand('doctrine:schema:update', 'dev', ['--force' => true]);
            } else {
                $this->addFlash(
                    'warning',
                    'unknown command'
                );
                return $this->redirect($this->generateUrl('admin_command_system_dashboard'));
            }
            $this->addFlash(
                'success',
                $commandResponse
            );
            return $this->redirect($this->generateUrl('admin_command_system_dashboard'));
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_command_system_dashboard'));
        }
    }
}

