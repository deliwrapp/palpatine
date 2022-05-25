<?php

namespace App\Core\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

/**
 * Class ErrorHandlerController
 * @package App\Core\Controller
 * @Route("/error")
 */
class ErrorHandlerController extends AbstractController
{    
    /**
     * Page Error Handler Controller
     * 
     * @param string $error_code
     * @Route("/{error_code}",
     * name="page_error_handler",
     * defaults={"error_code":404}
     * )
     * @return Response
     * @return RedirectResponse
     */
    public function showErrorPage(int $error_code = 404): Response
    {
        try {
            switch ($error_code) {
                case 401:
                    $status_text = "core.error.forbiden";
                    break;
                case 403:
                    $status_text = "core.error.not_authorized";
                    break;
                case 404:
                    $status_text = "core.error.not_found";
                    break;
                case 501:
                    $status_text = "core.error.server_failled";
                    break;
                case 503:
                    $status_text = "core.error.not_access";
                    break;
                default:
                    $status_text = "core.error.error";
                    break;
            }
            return $this->render('@base-theme/error-page.html.twig', [
                'error_code' => $error_code,
                'status_text' => $status_text
            ]);
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('homepage'));
        }
    }

    /**
     * Page Error Exception Handler Controller
     * 
     * @param FlattenException $exception
     * @param DebugLoggerInterface $logger = null
     * @return Response
     */
    public function handleError(FlattenException $exception, DebugLoggerInterface $logger = null)
    {
        try {
            return $this->render('@base-theme/error-page.html.twig', [
                "error_code" => $exception->getStatusCode(),
                "status_text" =>$exception->getStatusText()
            ]);
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('homepage'));
        }
    }
}
