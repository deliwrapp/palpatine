<?php

namespace App\Core\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class LocaleRedirectController extends AbstractController
{

    /**
     * @Route("/redirect-to-locale", name="redirect_to_locale")
     */
    public function localeRedirect(Request $request): Response
    {
        $locale = $request->getLocale();
        return $this->redirectToRoute('homepage', ['_locale' => $locale ]);
    }

}
