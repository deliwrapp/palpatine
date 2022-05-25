<?php

namespace App\Core\Services;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Core\Services\ConfigLoader;


class AppConfigurator
{
    /** @var ParameterBagInterface */
    private $params;

    /** @var ConfigLoader */
    private $configLoader;

    /** @var string */
    private $rootDirectory;
    
    public function __construct(
        ParameterBagInterface $params,
        ConfigLoader $configLoader
    )
    {
        $this->params = $params;
        $this->rootDirectory = $this->params->get('kernel.project_dir');
        $this->configLoader = $configLoader;
    }

    /**
     * Init Bloogy App Configuration
     *
     * @param 
     * @return
     */
    public function initApp() {
        try {
            // Create Admin User
            
            // Create .env file config for bdd and global vars
            
            // Create Locale List and add to parameters list

            // Create and update bdd table
            
            // launch dev cache clear command 

            // Create Localized Main Menu Based on defined Locale List

            // Create Localized Homepage Menu Based on defined Locale List

            // Load and Create Content based on imported config file

            // remove installer module folder

            // launch dev cache clear command 

            // launch prod cache clear command 
            
        } catch (\Exception $e) {
            printf('Unable to parse the init app string: %s', $e->getMessage());
            return null;
        }
        
    }
  
}
