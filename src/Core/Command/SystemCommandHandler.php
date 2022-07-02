<?php

namespace App\Core\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\KernelInterface;


class SystemCommandHandler
{
    /** @var KernelInterface $kernel */
    private $kernel;
    
    public function __construct(
        KernelInterface $kernel
    )
    {
        $this->kernel = $kernel;
    }

    /**
     * do_command -> execute command
     * 
     * @param string $command
     * @param string $env = null {options = dev/prod}
     * @param array $options = null
     */
    public function doCommand(
        string $command,
        string $env = null,
        $options = null
    ) {
        try {
            if (null == $env) {
                $env = $this->kernel->getEnvironment();
            } 
            $application = new Application($this->kernel);
            $application->setAutoExit(false);
            $commandToExecute =  ['command' => $command, '--env' => $env];
            if ($options) {
                $commandToExecute = array_merge($commandToExecute, $options);
            }
            $input = new ArrayInput($commandToExecute);
            $output = new BufferedOutput();
            $application->run($input, $output);
            $content = $output->fetch();
            return $content;   
        } catch (\Exception $e) {
            printf('Unable to execute command : %s', $e->getMessage());
            return false;
        }      
    }
  
}
