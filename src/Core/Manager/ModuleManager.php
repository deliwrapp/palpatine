<?php
// src/Core/Manager/ModuleManager.php
namespace App\Core\Manager;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Core\Services\ConfigLoader;
use App\Core\Services\ModuleUploader;
use App\Core\Command\SystemCommandHandler;
use App\Core\Command\ProcessCommandLineHandler;
use App\Core\Entity\Template;
use App\Core\Entity\Block;
use App\Core\Repository\TemplateRepository;
use App\Core\Repository\BlockRepository;
 
class ModuleManager
{
    /** @var string */
    private $projectDirectory;

    /** @var string */
    private $privateUploadPath;

    /** @var string */
    private $absolutePrivatePath;

    /** @var ParameterBagInterface */
    private $params;
 
    /** @var ConfigLoader */
    private $configLoader;

    /** @var ModuleUploader */
    private $moduleUploader;

    /** @var TemplateRepository */
    private $tplRepository;

    /** @var BlockRepository */
    private $blockRepository;

    /** @var SystemCommandHandler */
    private $systemCommandHandler;

    /** @var ProcessCommandLineHandler */
    private $processCommandLineHandler;

    public function __construct(
        ParameterBagInterface $params,
        ConfigLoader $configLoader,
        ModuleUploader $moduleUploader,
        TemplateRepository $tplRepository,
        BlockRepository $blockRepository,
        SystemCommandHandler $systemCommandHandler,
        ProcessCommandLineHandler $processCommandLineHandler
    )
    {
        $this->projectDirectory = $params->get('kernel.project_dir');
        $this->privateUploadPath = $params->get('private_uploads_directory');
        $this->absolutePrivatePath = $params->get('absolute_private_directory');
        $this->configLoader = $configLoader;
        $this->moduleUploader = $moduleUploader;
        $this->tplRepository = $tplRepository;
        $this->blockRepository = $blockRepository;
        $this->systemCommandHandler = $systemCommandHandler;
        $this->processCommandLineHandler = $processCommandLineHandler;
    }

    /**
     * install a module
     *
     * @param UploadedFile $zipFile - the zipFile
     * @return bool
     * @throw \Exception $e
     */
    public function install(UploadedFile $zipFile)
    {
        try {
            $uploadedZipPath = $this->moduleUploader->upload($zipFile);
            $uploadedZipPath = $this->unzip($uploadedZipPath);
            if ($uploadedZipPath && file_exists($uploadedZipPath.'/install.yaml')) {
                $instalConfigFile = $this->getConfigFile($uploadedZipPath);
                if (isset($instalConfigFile['dir'])) {
                    $originalFolder = $uploadedZipPath.'/'.$instalConfigFile['dir'];
                    $destinationFolder = $this->getModuleDestinationPath($instalConfigFile).'/'.$instalConfigFile['dir'];
                    
                    if (isset($instalConfigFile['beforeCommands'])) {
                        $this->executeConsoleCommand($instalConfigFile['beforeCommands']);
                    }
                    if (isset($instalConfigFile['beforeScripts'])) {
                        $this->executeScripts($instalConfigFile['beforeScripts']);
                    }
                    if ($this->moveFolderModuleToDestination($originalFolder, $destinationFolder)) {
                        $this->synchonizeWithDatabase($instalConfigFile);
                    }
                    if (isset($instalConfigFile['commands'])) {
                        $this->executeConsoleCommand($instalConfigFile['commands']);
                    }
                    if (isset($instalConfigFile['scripts'])) {
                        $this->executeScripts($instalConfigFile['scripts']);
                    }
                    if (isset($instalConfigFile['afterCommands'])) {
                        $this->executeConsoleCommand($instalConfigFile['afterCommands']);
                    }
                    if (isset($instalConfigFile['afterScripts'])) {
                        $this->executeScripts($instalConfigFile['afterScripts']);
                    }
                    $this->deleteModuleTransfertFolder($uploadedZipPath);
                } else {
                    $this->deleteModuleTransfertFolder($uploadedZipPath);
                    throw new \Exception("error.module.directory_folder_not_exists", 1);
                }
            } else {
                $this->deleteModuleTransfertFolder($uploadedZipPath);
                throw new \Exception("error.module.install_file_not_exists", 1);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Unzip the zip File and analize it
     *
     * @param object $uploadedZipPath - the path of the zipFile
     * @return string $folderDestination - the folder destination for extrated zip file
     * @throw \Exception $e
     */
    public function unzip(string $uploadedZipPath)
    {
        try {
            $zip = new \ZipArchive;
            $folderDestination = $this->absolutePrivatePath.'/'.$this->privateUploadPath.'/module-to-install';
            if ($zip->open($uploadedZipPath) === TRUE) {
                $zip->extractTo($folderDestination);
                $zip->close();
                unlink($uploadedZipPath);
                return $folderDestination;
            } else {
                unlink($uploadedZipPath);
                return false;
            } 
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get The Config File in Folder
     *
     * @param object $uploadedZipPath - the base folder path
     * @return Object|null $convertedYaml
     * @throw \Exception $e
     */
    public function getConfigFile(string $dir)
    {
        try {
            return $this->configLoader->getConfigFileConvertedInObject($dir.'/install.yaml');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Delete Module Transfert Folder and file inside
     *
     * @param string $dir - the folder dirrectory to remove
     * @return void
     * @return \Exception $e
     */
    public function deleteModuleTransfertFolder(string $dir) {
        try {
            foreach(glob($dir . '/*') as $file) {
                if (is_dir($file)) {
                    $this->deleteModuleTransfertFolder($file);
                }
                else {
                    unlink($file);
                }    
            }
            rmdir($dir);
        } catch (\Exception $th) {
            throw $e;
        }
    }

    /**
     * Get Module Path
     *
     * @param array $config - The module config file
     * @return string $moduleFolderPath
     * @throw \Exception
     */
    public function getModuleDestinationPath(array $config)
    {
        try {
            $moduleFolderPath = null;
            if (isset($config['type'])) {
                switch ($config['type']) {
                    case 'block':
                        $moduleFolderPath = $this->projectDirectory.'/templates/blocks/';
                        break;
                    case 'theme':
                        $moduleFolderPath = $this->projectDirectory.'/templates/';
                        break;
                    case 'extension':
                        $moduleFolderPath = $this->projectDirectory.'/src/';
                        break;
                    default:
                        throw new \Exception('error.module.module_type_no_exists');
                        break;
                }
                return $moduleFolderPath;
            } else {
                throw new \Exception('error.module.type_no_defined');
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * move Folder Module To the apropriate path destination
     *
     * @param string $originalFolder - the original folder directory
     * @param string $destinationFolder - the destination folder directory
     * @return bool
     * @throw \Exception $e
     */
    public function moveFolderModuleToDestination(string $originalFolder, string $destinationFolder)
    {
        try {
            $filesystem = new Filesystem();
            $filesystem->rename($originalFolder, $destinationFolder);
            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Synchonize With Database
     *
     * @param array $config - The module config file
     * @return bool - true
     * @throw \Exception
     */
    public function synchonizeWithDatabase(array $config)
    {
        try {
            if (isset($config['type'])) {
                switch ($config['type']) {
                    case 'block':
                        $tpl = null;
                        if (isset($config['template'])) {
                            $tpl = $this->addConfigEntityToDatabase('template', $config['template']);
                        }
                        if (isset($config['block'])) {
                            $block = $this->addConfigEntityToDatabase('block', $config['block']);
                            $block->setBlockTemplate($tpl);
                            $this->blockRepository->add($block);
                        }
                        break;
                    case 'theme':
                        // TODO
                        break;
                    case 'extension':
                        // TODO
                        break;
                    default:
                        throw new \Exception('error.module.module_type_no_exists');
                        break;
                }
                return true;
            } else {
                throw new \Exception('error.module.type_no_defined');
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Create data based on existing Entity and add it to database (Template | Block | Form)
     *
     * @param array $type - the type of the entity to create
     * @param array $data - the data for the new entity
     * @return bool
     * @return \Exception $e
     */
    public function addConfigEntityToDatabase(string $type, array $data)
    {
        try {
            switch ($type) {
                case 'template':
                    $tpl = new Template;
                    $tpl->hydrate($tpl, $data);
                    $this->tplRepository->add($tpl);
                    return $tpl;
                    break;
                case 'block':
                    $block = new Block;
                    $block->hydrate($block, $data);
                    $this->blockRepository->add($block);
                    return $block;
                    break;
                case 'form':
                    # TODO : Form creation process
                    break;
                default:
                    # code...
                    break;
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
    
    /**
     * Execute the console command system pipeline - call SystemCommandHandler Job Handler
     *
     * @param array $scripts - the list of the command to execute
     * @return bool
     * @return \Exception $e
     */
    public function executeConsoleCommand(array $commands)
    {
        try {
            foreach ($commands as $cmd) {
                $executeCmd = false;
                if (isset($cmd['cmd']) && isset($cmd['env'])) {
                    $executeCmd = $this->systemCommandHandler->doCommand($cmd['cmd'], $cmd['env']);
                } elseif (isset($cmd['cmd']) && isset($cmd['env']) && isset($cmd['opts'])) {
                    $executeCmd = $this->systemCommandHandler->doCommand($cmd['cmd'], $cmd['env'], $cmd['opts']);  
                } else {
                    throw new \Exception("error.module.command_argument_not_defined", 1);
                }
                if (!$executeCmd) {
                    throw new \Exception("error.module.command_process_error", 1);
                } else {
                    printf($executeCmd);
                }
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Execute the script pipeline
     *
     * @param array $scripts - the list of the scripts to execute
     * @return bool
     * @return \Exception $e
     */
    public function executeScripts(array $scripts)
    {
        try {
            foreach ($scripts as $script) {
                // call Command Line Process Manager
                $this->processCommandLineHandler->doCommand($script);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Remove Module
     *
     * @param array $config
     * @return bool
     * @return IOExceptionInterface $e
     */
    public function removeModule(array $config)
    {
        try {
            
        } catch (IOExceptionInterface $e) {
            throw $e;
        }
    }
}
