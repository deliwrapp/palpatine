<?php

namespace App\Core\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Core\Entity\Menu;
use App\Core\Repository\MenuRepository;
use App\Core\Form\MenuFormType;

/**
 * @Route("/admin/menu")
 */
class AdminMenuController extends AbstractController
{
    public function __construct(
        MenuRepository $menuRepo
    )
    {
        $this->menuRepo = $menuRepo;
    }

    /**
     * @Route("/", name="admin_menu_list")
     */
    public function index(): Response
    {
        try {
            $menus = $this->menuRepo->findAll();
            return $this->render('@core-admin/menu/menu-list.html.twig', [
                'menus' => $menus
            ]);
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('AdminDashboard'));
        } 
    }

    /**
     * @Route("/create", name="admin_menu_create")
    */
    public function create(Request $request): Response
    {
        try {
            $menu = new Menu();
            $form = $this->createForm(MenuFormType::class, $menu, [
                'submitBtn' => 'Create'
            ]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $menu = $form->getData();
                $this->menuRepo->add($menu);
                $this->addFlash(
                    'info',
                    'Saved new Menu with id '.$menu->getId()
                );
                return $this->redirect($this->generateUrl('admin_menu_edit', [
                    'id' => $menu->getId()
                ]));
            }
            
            return $this->render('@core-admin/menu/menu-edit.html.twig', [
                'form' => $form->createView()
            ]);
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_menu_list'));
        }
    }

    /**
     * @Route("/update/{id}", name="admin_menu_edit")
     */
    public function edit(int $id, Request $request): Response
    {
        try {
            $menu = $this->menuVerificator($id);
            $form = $this->createForm(MenuFormType::class, $menu, [
                'submitBtn' => 'Edit'
            ]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $position = $form->get('position')->getData();
                $menu = $form->getData();
                $this->menuRepo->flush();
                $this->addFlash(
                    'info',
                    'Menu updated'
                );
                return $this->redirect($this->generateUrl('admin_menu_edit', [
                    'id' => $menu->getId()
                ]));
            }
            return $this->render(
                '@core-admin/menu/menu-edit.html.twig',
                [
                    'form' => $form->createView(),
                    'menu' => $menu
                ]
            );
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_menu_list'));
        }
    }

        /**
     * @Route("/duplicate/{id}", name="admin_menu_duplicate")
     */
    public function duplicate(int $id, Request $request): Response
    {
        try {
            $menu = $this->menuVerificator($id);
            $newMenu = new Menu;
            $newMenu = $menu->duplicate($newMenu);
            $this->menuRepo->add($newMenu);
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
        }
        return $this->redirect($this->generateUrl('admin_menu_list'));
    }

    /**
     * @Route("/show/{id}", name="admin_menu_show")
     */
    public function show(int $id): Response
    {
        try {
            $menu = $this->menuVerificator($id);
            return $this->render('@core-admin/menu/menu-show.html.twig', [
                'menu' => $menu
            ]);
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_menu_list'));
        }
    }

    /**
     * @Route("/delete/{id}", name="admin_menu_delete")
     */
    public function delete(int $id, Request $request): Response
    {
        try {
            $submittedToken = $request->request->get('token'); 
            if ($this->isCsrfTokenValid('delete-menu', $submittedToken)) {
                $menu = $this->menuVerificator($id);
                $this->menuRepo->remove($menu);
                $this->addFlash(
                    'success',
                    'The Menu with ' . $id . ' have been deleted '
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
        return $this->redirect($this->generateUrl('admin_menu_list'));
    }

    public function menuVerificator(int $menuId)
    {
        $menu = $this->menuRepo->find($menuId);
        if (!$menu) {
            $this->addFlash(
                'warning',
                'There is no Menu  with id ' . $menuId
            );
            return $this->redirect($this->generateUrl('admin_menu_list'));
        }
        return $menu;
    }

}
