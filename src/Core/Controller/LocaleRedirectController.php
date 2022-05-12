<?php

namespace App\Core\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class LocaleRedirectController -- Redirect to localized page
 * @package App\Core\Controller
 */
class LocaleRedirectController extends AbstractController
{

    /**
     * Redirect to localized page
     * 
     * @param Request $request
     * @Route("/redirect-to-locale", name="redirect_to_locale")
     * @return RedirectResponse
     */
    public function localeRedirect(Request $request): RedirectResponse
    {
        $locale = $request->getLocale();
        return $this->redirectToRoute('homepage', ['_locale' => $locale ]);
    }

}
