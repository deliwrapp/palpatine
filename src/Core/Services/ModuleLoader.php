<?php

// src/Core/Services/FileLoader.php
namespace App\Core\Services;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Core\Services\ConfigLoader;
use App\Core\Model\ModulesInfo;

class ModuleLoader
{
    /** @var string */
    private $rootDirectory;

    /** @var ParameterBagInterface */
    private $params;

    /** @var ConfigLoader */
    private $configLoader;
 
    public function __construct(
        ParameterBagInterface $params,
        ConfigLoader $configLoader
    )
    {
        $this->rootDirectory = $params->get('kernel.project_dir');
        $this->configLoader = $configLoader;
    }
 
    /**
     * Upload a new File
     *
     * @return ModulesInfo $modulesInfo
     * @return Exception $e
     */
    public function getModulesInfo()
    {
        try {
            $modulesInfo = new ModulesInfo;
            foreach(glob($this->rootDirectory . '/src/*') as $folder) {
                if (is_dir($folder)) {
                    $srcModule = $this->configLoader->getConfigFileConvertedInObject($folder.'/module.yaml');
                    if (null != $srcModule) {
                        if (isset($srcModule['type']) && $srcModule['type'] == 'core') {
                            $modulesInfo->addCoreModule($srcModule);
                        } else {
                            $modulesInfo->addExtensionModule($srcModule);
                        }
                    }
                }   
            }
            foreach(glob($this->rootDirectory . '/templates/*') as $folder) {
                if (is_dir($folder)) {
                    if ($folder == $this->rootDirectory . '/templates/blocks') {
                        foreach(glob($this->rootDirectory . '/templates/blocks/*') as $folder) {
                            if (is_dir($folder)) {
                                $blockModule = $this->configLoader->getConfigFileConvertedInObject($folder.'/module.yaml');
                                if (null != $blockModule) {
                                    $modulesInfo->addBlockModule($blockModule);
                                }
                            }   
                        }
                    } else {
                        $themeModule = $this->configLoader->getConfigFileConvertedInObject($folder.'/module.yaml');
                        if (null != $themeModule) {
                            $modulesInfo->addThemeModule($themeModule);
                        }
                    }
                }   
            }
            return $modulesInfo;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
