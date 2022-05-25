<?php

namespace App\Core\Controller\Editor;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Core\Entity\Menu;
use App\Core\Entity\MenuItem;
use App\Core\Repository\MenuRepository;
use App\Core\Repository\MenuItemRepository;
use App\Core\Form\MenuFormType;
use App\Core\Form\MenuItemFormType;
use App\Core\Factory\MenuFactory;
use App\Core\Verificator\MenuVerificator;

/**
 * Class EditorMenuController
 * @package App\Core\Controller\Editor
 * @IsGranted("ROLE_EDITOR",statusCode=401, message="No access! Get out!")
 * @Route("/editor/menu")
 */
class EditorMenuController extends AbstractController
{
    /** @var MenuRepository */
    private $menuRepo;

    /** @var MenuItemRepository */
    private $menuItemRepo;

    /** @var MenuFactory */
    private $menuFactory;

    /** @var MenuVerificator */
    private $menuVerif;

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
     * Menu List Index
     * 
     * @Route("/", name="editor_menu_list")
     * @return RedirectResponse
     */
    public function index(): Response
    {
        try {
            $menus = $this->menuRepo->findAll();
            return $this->render('@core-admin/menu/editor/menu-list.html.twig', [
                'menus' => $menus
            ]);
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_dashboard_redirect'));
        } 
    }

    /**
     * Menu Create
     * 
     * @param Request $request
     * @Route("/create", name="editor_menu_create") 
     * @return RedirectResponse
    */
    public function create(Request $request): RedirectResponse
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
            return $this->redirect($this->generateUrl('editor_menu_edit', [
                'id' => $menu->getId()
            ]));
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('editor_menu_list'));
        }
    }

    /**
     * Menu Edit
     * 
     * @param int $id
     * @param string $opt
     * @param Request $request
     * @Route("/update/{id}/{opt}", name="editor_menu_edit") 
     * @return Response 
     * @return RedirectResponse
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
                $locale = $form->get('locale')->getData();
                if ($isMainMenu) {
                    $testMainMenu = $this->menuVerif->checkIfMainMenuWithLocaleExists($locale);
                    if ($testMainMenu && $testMainMenu->getId() != $menu->getId()) {
                        if ($opt == 'force') {
                            $testMainMenu->setIsMainMenu(false);
                        } else {
                            $this->addFlash(
                                'warning',
                                'Main Menu with '.$locale.' locale is already defined'
                            );
                            return $this->redirect($this->generateUrl('editor_menu_edit', [
                                'id' => $menu->getId()
                            ]));
                        }  
                   } 
                }
                $position = $form->get('position')->getData();
                if ($position) {
                   $testMenuPos = $this->menuVerif->checkIfMenuWithPositionAndLocaleExists($position, $locale);
                   if ($testMenuPos && $testMenuPos->getId() != $menu->getId()) {
                        if ($opt == 'force') {
                            $testMenuPos->setPosition(null);
                        } else {
                            $this->addFlash(
                                'warning',
                                'Position with '.$locale.' locale is already taken'
                            );
                            return $this->redirect($this->generateUrl('editor_menu_edit', [
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
                return $this->redirect($this->generateUrl('editor_menu_edit', [
                    'id' => $menu->getId()
                ]));
            } 
            return $this->render(
                '@core-admin/menu/editor/menu-edit.html.twig',
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
            return $this->redirect($this->generateUrl('editor_menu_list'));
        }
    }

    /**
     * Menu Duplicate
     * 
     * @param int $id
     * @param Request $request
     * @Route("/duplicate/{id}", name="editor_menu_duplicate")
     * @return RedirectResponse
     */
    public function duplicate(int $id, Request $request): RedirectResponse
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
        return $this->redirect($this->generateUrl('editor_menu_list'));
    }

    /**
     * Menu Show
     * 
     * @param int $id
     * @Route("/show/{id}", name="editor_menu_show")
     * @return Response
     */
    public function show(int $id): Response
    {
        try {
            $menu = $this->menuVerificator($id);
            return $this->render('@core-admin/menu/editor/menu-show.html.twig', [
                'menu' => $menu
            ]);
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('editor_menu_list'));
        }
    }

    /**
     * Menu Delete
     * 
     * @param int $id
     * @param Request $request
     * @Route("/delete/{id}", name="editor_menu_delete")
     * @return RedirectResponse
     */
    public function delete(int $id, Request $request): RedirectResponse
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
        return $this->redirect($this->generateUrl('editor_menu_list'));
    }

    /**
     * Menu Add MenuItem
     * 
     * @param Request $request
     * @param int $menuId
     * @param string $type = "page"
     * @Route("/add-menu-item-to/{menuId}/{type}", name="editor_menu_item_create")
     * @return RedirectResponse
     */
    public function addMenuItem(Request $request, int $menuId, string $type = "page"): RedirectResponse
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
            return $this->redirect($this->generateUrl('editor_menu_item_edit', [
                'menuItemId' => $menuItem->getId(),
                'menuId' => $menuId,
                'type' => $type
            ]));
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('editor_menu_list'));
        }
    }

    /**
     * Menu Edit MenuItem
     * 
     * @param Request $request
     * @param int $menuItemId
     * @param int $menuId
     * @param string $type = "page"
     * @Route("/edit-menu-item/{menuItemId}/from/{menuId}/{type}", name="editor_menu_item_edit")
     * @return Response
     * @return RedirectResponse
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
                return $this->redirect($this->generateUrl('editor_menu_edit', [
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
                return $this->redirect($this->generateUrl('editor_menu_edit', [
                    'id' => $menuId
                ]));
            }
            return $this->render(
                '@core-admin/menu/editor/menu-item-edit.html.twig',
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
            return $this->redirect($this->generateUrl('editor_menu_list'));
        }
        
    }

    /**
     * Menu Delete MenuItem
     * 
     * @param Request $request
     * @param int $menuItemId
     * @param int $menuId
     * @Route("/delete-menu-item/{menuItemId}/from/{menuId}", name="editor_menu_item_delete")
     * @return RedirectResponse
     */
    public function deleteMenuItem(Request $request, int $menuItemId, int $menuId): RedirectResponse
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
                    return $this->redirect($this->generateUrl('editor_menu_edit', [
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
            return $this->redirect($this->generateUrl('editor_menu_edit', [
                'id' => $menuId
            ]));
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('editor_menu_list'));
        }
    }
    
    /**
     * Test if menu exists and return it, or redirect to menu list index with an error message
     * 
     * @param int $menuId
     * @return Menu $menu
     * @return RedirectResponse
     */
    public function menuVerificator(int $menuId)
    {
        $menu = $this->menuRepo->find($menuId);
        if (!$menu) {
            $this->addFlash(
                'warning',
                'There is no Menu  with id ' . $menuId
            );
            return $this->redirect($this->generateUrl('editor_menu_list'));
        }
        return $menu;
    }
}
