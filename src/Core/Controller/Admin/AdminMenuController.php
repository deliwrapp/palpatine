<?php

namespace App\Core\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Core\Entity\Menu;
use App\Core\Entity\MenuItem;
use App\Core\Repository\MenuRepository;
use App\Core\Repository\MenuItemRepository;
use App\Core\Form\MenuFormType;
use App\Core\Form\MenuItemFormType;
use App\Core\Services\MenuFactory;
use App\Core\Services\MenuVerificator;

/**
 * @Route("/admin/menu")
 */
class AdminMenuController extends AbstractController
{
    public function __construct(
        MenuRepository $menuRepo,
        MenuItemRepository $menuItemRepo,
        MenuFactory $menuFactory,
        MenuVerificator $menuVerif
    )
    {
        $this->menuRepo = $menuRepo;
        $this->menuItemRepo = $menuItemRepo;        
        $this->menuFactory = $menuFactory;       
        $this->menuVerif = $menuVerif;
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
            $this->menuRepo->add($menu);
            $menu->setName('menu-'.$menu->getId());
            $this->menuRepo->flush();
            $this->addFlash(
                'info',
                'Saved new Menu with id '.$menu->getId()
            );
            return $this->redirect($this->generateUrl('admin_menu_edit', [
                'id' => $menu->getId()
            ]));
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_menu_list'));
        }
    }

    /**
     * @Route("/update/{id}/{opt}", name="admin_menu_edit")
     */
    public function edit(int $id, string $opt = null, Request $request): Response
    {
        try {
            $menu = $this->menuVerificator($id);
            $form = $this->createForm(MenuFormType::class, $menu, [
                'submitBtn' => 'Edit'
            ]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $isMainMenu = $form->get('isMainMenu')->getData();
                if ($isMainMenu) {
                    $testMainMenu = $this->menuVerif->checkIfMainMenuExists();
                    if ($testMainMenu && $testMainMenu->getId() != $menu->getId()) {
                        if ($opt == 'force') {
                            $testMainMenu->setIsMainMenu(false);
                        } else {
                            $this->addFlash(
                                'warning',
                                'Main Menu is already defined'
                            );
                            return $this->redirect($this->generateUrl('admin_menu_edit', [
                                'id' => $menu->getId()
                            ]));
                        }  
                   } 
                }
                $position = $form->get('position')->getData();
                if ($position) {
                   $testMenuPos = $this->menuVerif->checkIfMenuWithPositionExists($position);
                   if ($testMenuPos && $testMenuPos->getId() != $menu->getId()) {
                        if ($opt == 'force') {
                            $testMenuPos->setPosition(null);
                        } else {
                            $this->addFlash(
                                'warning',
                                'Position already taken'
                            );
                            return $this->redirect($this->generateUrl('admin_menu_edit', [
                                'id' => $menu->getId()
                            ]));
                        }  
                   }
                } 
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

    /**
     * @Route("/add-menu-item-to/{menuId}/{type}", name="admin_menu_item_create")
     */
    public function addMenuItem(Request $request, int $menuId, string $type = "page"): Response
    {
        try {
            $menu = $this->menuVerificator($menuId);
            $menuItem = new MenuItem;
            $menuItem->setType($type);
            $menu->addMenuItem($menuItem);
            $this->menuItemRepo->add($menuItem);
            $this->addFlash(
                'info',
                'New Menu Item'
            );
            return $this->redirect($this->generateUrl('admin_menu_item_edit', [
                'menuItemId' => $menuItem->getId(),
                'menuId' => $menuId,
                'type' => $type
            ]));
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_menu_list'));
        }
    }

    /**
     * @Route("/edit-menu-item/{menuItemId}/from/{menuId}/{type}", name="admin_menu_item_edit")
     */
    public function editMenuItem(Request $request, int $menuItemId, int $menuId, string $type = "page"): Response
    {
        try {
            $this->menuVerificator($menuId);
            $menuItem = $this->menuItemRepo->find($menuItemId);
            if (!$menuItem) {
                $this->addFlash(
                    'info',
                    'Menu Item not found'
                );
                return $this->redirect($this->generateUrl('admin_menu_edit', [
                    'id' => $menuId
                ]));
            }
            $form = $this->createForm(MenuItemFormType::class, $menuItem, [
                'submitBtn' => 'Edit',
                'mode' => $type
            ]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $menuItem = $form->getData();
                $this->menuItemRepo->flush();
                $this->addFlash(
                    'info',
                    'Menu Item updated'
                );
                return $this->redirect($this->generateUrl('admin_menu_edit', [
                    'id' => $menuId
                ]));
            }
            return $this->render(
                '@core-admin/menu/menu-item-edit.html.twig',
                [
                    'form' => $form->createView(),
                    'menuItem' => $menuItem
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
     * @Route("/delete-menu-item/{menuItemId}/from/{menuId}", name="admin_menu_item_delete")
     */
    public function deleteMenuItem(Request $request, int $menuItemId, int $menuId): Response
    {
        try {
            $submittedToken = $request->request->get('token'); 
            if ($this->isCsrfTokenValid('delete-menu-item', $submittedToken)) {
                $this->menuVerificator($menuId);
                $menuItem = $this->menuItemRepo->find($menuItemId);
                if (!$menuItem) {
                    $this->addFlash(
                        'info',
                        'Menu Item not found'
                    );
                    return $this->redirect($this->generateUrl('admin_menu_edit', [
                        'id' => $menuId
                    ]));
                }
                $this->menuItemRepo->remove($menuItem);
                $this->addFlash(
                    'info',
                    'Menu Item deleted'
                );
            } else {
                $this->addFlash(
                    'warning',
                    'Your CSRF token is not valid ! '
                );
            }
            return $this->redirect($this->generateUrl('admin_menu_edit', [
                'id' => $menuId
            ]));
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_menu_list'));
        }
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
