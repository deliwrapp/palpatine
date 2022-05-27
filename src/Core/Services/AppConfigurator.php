<?php

namespace App\Core\Services;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use App\Core\Command\SystemCommandHandler;
use App\Core\Services\ConfigLoader;
use App\Security\Entity\User;
use App\Core\Entity\Menu;
use App\Core\Entity\MenuItem;
use App\Core\Entity\Page;
use App\Security\Repository\UserRepository;
use App\Core\Repository\MenuRepository;
use App\Core\Repository\PageRepository;
use App\Core\Factory\PageFactory;


class AppConfigurator
{
    /** @var ParameterBagInterface */
    private $params;

    /** @var UserPasswordHasherInterface */
    private $userPasswordHasher;

    /** @var SystemCommandHandler */
    private $systemCommand;

    /** @var ConfigLoader */
    private $configLoader;

    /** @var UserRepository $userRepo */
    private $userRepo;

    /** @var MenuRepository $menuRepo */
    private $menuRepo;

    /** @var PageRepository $pageRepo */
    private $pageRepo;

    /** @var PageFactory */
    private $pageFactory;

    /** @var string */
    private $rootDirectory;
    
    public function __construct(
        ParameterBagInterface $params,
        UserPasswordHasherInterface $userPasswordHasher,
        SystemCommandHandler $systemCommand,
        ConfigLoader $configLoader,
        UserRepository $userRepo,
        MenuRepository $menuRepo,
        PageRepository $pageRepo,
        PageFactory $pageFactory
    )
    {
        $this->params = $params;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->systemCommand = $systemCommand;
        $this->configLoader = $configLoader;
        $this->userRepo = $userRepo;
        $this->menuRepo = $menuRepo;
        $this->pageRepo = $pageRepo;
        $this->pageFactory = $pageFactory;
        $this->rootDirectory = $this->params->get('kernel.project_dir');
    }

    /**
     * initApp
     * 
     * Init Bloogy App Configuration
     *
     * @param bool $initContent = true
     * @param bool $initDatabase = false
     * @param bool $fullProcess = false
     * @return
     */
    public function initApp($initContent = true, $initDatabase = false, $fullProcess = false) {
        try {
            if ($initDatabase) {
                // Create and update bdd table
                $this->initDatabase();
            }
            if ($initContent) {
                // Create Localized Main Menus Based on defined Locale List
                $this->initLocalizedMenu();
                // Create Localized Homepage Menu Based on defined Locale List
                $this->initLocalizedPage();
                
                // TODO : Load and Create Content based on imported config file
            }
            if ($fullProcess) {
                // Remove installer module folder
                $this->initInstallerModuleRemove();
                // launch dev cache clear command 
                $this->systemCommand->doCommand('cache:clear', 'dev');
                // launch prod cache clear command 
                $this->systemCommand->doCommand('cache:clear', 'prod');   
            }
            // redirect to admin dashboard
            return true;
        } catch (\Exception $e) {
            printf('Unable to init app : %s', $e->getMessage());
            return false;
        }
        
    }

    /**
     * initDatabase
     * 
     * Init Bloogy App Configuration creating and updating database
     *
     * @return true|false
     */
    public function initDatabase() {
        try {
            $commandResponse = $this->systemCommand->doCommand('doctrine:database:create', 'dev', ['--if-not-exists' => true]);
            if ($commandResponse) {
                printf('BDD CREATED : %s', $commandResponse);
            }
            $commandResponse = $this->systemCommand->doCommand('doctrine:schema:update', 'dev', ['--force' => true]);
            if ($commandResponse) {
                printf('BDD UPDATED : %s', $commandResponse);
            }
            return true;

        } catch (\Exception $e) {
            printf('Unable to init Database : %s', $e->getMessage());
            return false;
        }  
    }

    /**
     * initAdminUser
     * 
     * Init Bloogy App Configuration creating an Admin User
     *
     * @param User $user
     * @param string $password
     * @return
     */
    public function initAdminUser(User $user, string $password) {
        try {
            // Create Admin User
            $user->setPassword( $this->userPasswordHasher->hashPassword(
                        $user,
                        $password
                    )
                );
            $user->setRoles(['ROLE_SUPER_ADMIN', 'ROLE_ADMIN']);
            $user->setIsVerified(true);
            $user->setIsActive(true);
            $user->setIsRestricted(false);
            $this->userRepo->add($user);
            return $user;

        } catch (\Exception $e) {
            printf('Unable to init Admin User : %s', $e->getMessage());
            return false;
        }  
    }

    /**
     * initLocalizedMenu
     * 
     * Init Bloogy App Configuration creating localized menu
     * Create Localized Main Menus Based on defined Locale List
     * @return true|false
     */
    public function initLocalizedMenu() {
        try {
            $localesList = $this->params->get('appLocalesList');
            foreach ($localesList as $locale => $value) {
                $menu = new Menu;
                $menu->setName('main-menu-'.$locale);
                $menu->setLocale($locale);
                $menu->setIsMainMenu(true);
                $menu->setIsPublished(true);
                $menuItem = new MenuItem;
                $menuItem->setName('menu.homepage');
                $menuItem->setType('internal');
                $menuItem->setPath('homepage');
                $menu->addMenuItem($menuItem);
                $this->menuRepo->add($menu, false);
            }
            $this->menuRepo->flush();
            return true;
        } catch (\Exception $e) {
            printf('Unable to init Localized Menus : %s', $e->getMessage());
            return false;
        }  
    }

    /**
     * initLocalizedPage
     * 
     * Init Bloogy App Configuration creating localized Page
     * Create Localized Pages Based on defined Locale List
     * @return true|false
     */
    public function initLocalizedPage() {
        try {
            $localesList = $this->params->get('appLocalesList');
            $defaultLocale = $this->params->get('locale');
            $firstPage = new Page;
            $firstPage->setName('homepage-'.$defaultLocale);
            $firstPage->setLocale($defaultLocale);
            $firstPage->setIsHomepage(true);
            $firstPage->setIsPublished(true);
            $firstPage = $this->pageFactory->initPage($firstPage, true);
            if ($firstPage instanceof Page) {
                foreach ($localesList as $locale => $value) {
                    if ($locale != $defaultLocale) {
                        $page = new Page;
                        $page->setName('homepage-'.$locale);
                        $page->setLocale($locale);
                        $page->setIsHomepage(true);
                        $page = $this->pageFactory->duplicatePage($firstPage, 'main-menu-'.$locale, $locale, true);
                        $page->setIsPublished(true);
                        $this->pageRepo->add($page, false);
                    }               
                }
                $this->pageRepo->flush();
                return true;
            }
            printf('Unable to init Localized Pages : Duplication faillure');
            return false;
            
            
        } catch (\Exception $e) {
            printf('Unable to init Localized Pages : %s', $e->getMessage());
            return false;
        }  
    }

    /**
     * initInstallerModuleRemove
     * 
     * Init Bloogy App Configuration removing Installer Module
     * Remove installer module folder
     * @return true|false
     */
    public function initInstallerModuleRemove() {
        
        $filesystem = new Filesystem();
        try {
            $filesystem->remove($this->rootDirectory.'/src/Installer');
        } catch (IOExceptionInterface $e) {
            echo "An error occurred while deleting Installer Module directory : ".$e->getmessage();
        }
    }
  
}
