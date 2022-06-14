<?php

declare(strict_types=1);

namespace App\Core\Traits;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait RedirectToRefererTrait
{
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function redirectToReferer(Request $request): RedirectResponse
    {
        $referer = $request->headers->get('referer');

        if (false !== $pos = strpos($referer, '?')) {
            $referer = substr($referer, 0, $pos);
        }

        return new RedirectResponse($referer, Response::HTTP_SEE_OTHER);
    }
}
