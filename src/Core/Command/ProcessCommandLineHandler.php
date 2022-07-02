<?php

namespace App\Core\Command;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;


class ProcessCommandLineHandler
{

    /**
     * do_command -> execute command
     * 
     * @param array $commands
     * @throw ProcessFailedException
     * @throw Exception
     */
    public function doCommand(
        array $commands
    ) {
        try {
            $process = new Process($commands);
            $process->run();
            // executes after the command finishes
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
            echo $process->getOutput();
            return $process->getOutput();
        } catch (\Exception $e) {
            printf('Unable to execute command : %s', $e->getMessage());
            throw $e;
        }      
    }
  
}
