<?php

namespace App\Core\Command;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\KernelInterface;
use App\Core\Services\ConfigLoader;


class SystemCommandHandler
{
    /** @var ParameterBagInterface */
    private $params;

    /** @var ConfigLoader */
    private $configLoader;

    /** @var string */
    private $rootDirectory;

    /** @var KernelInterface $kernel */
    private $kernel;
    
    public function __construct(
        ParameterBagInterface $params,
        ConfigLoader $configLoader,
        KernelInterface $kernel
    )
    {
        $this->params = $params;
        $this->rootDirectory = $this->params->get('kernel.project_dir');
        $this->configLoader = $configLoader;
        $this->kernel = $kernel;
    }

    /**
     * do_command -> execute command
     * 
     * @param string $command
     * @param string $env = null {options = dev/prod}
     */
    public function doCommand(
        string $command,
        string $env = null
    ) {
        try {
            if (null == $env) {
                $env = $this->kernel->getEnvironment();
            } 
            
            $application = new Application($this->kernel);
            $application->setAutoExit(false);
            
            $input = new ArrayInput(array(
                'command' => $command,
                '--env' => $env
            ));
            
            $output = new BufferedOutput();
            $application->run($input, $output);
            
            $content = $output->fetch();
            
            return $content;
            
        } catch (\Exception $e) {
            printf('Unable to execute command : %s', $e->getMessage());
            return null;
        }
        
    }
  
}
