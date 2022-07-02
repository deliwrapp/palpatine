<?php

namespace App\Core\Services;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;


class ConfigLoader
{
    /** @var ParameterBagInterface */
    private $params;

    /** @var string */
    private $rootDirectory;
    
    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
        $this->rootDirectory = $this->params->get('kernel.project_dir');
    }

    /**
     * Get yaml file converted in php object
     *
     * @var string $path
     * @return Object|null $convertedYaml
     * @return ParseException $e
     */
    public function getConfigFileConvertedInObject(string $path) {
        try {
            $convertedYaml = Yaml::parseFile($path);
            return $convertedYaml;
        } catch (ParseException $e) {
            printf('Unable to parse the YAML string: %s', $e->getMessage());
            throw $e;
            /* return null; */
        }
        
    }

    /**
     * getConfigFile() Get yaml file converted in php object depending of an Index
     *
     * @var string $fileToConvert
     * @return Object|null $convertedYaml
     */
    public function getConfigFile(string $fileToConvert) {
        switch ($fileToConvert) {
            case 'admin_config':
                $convertedYaml = $this->getConfigFileConvertedInObject($this->rootDirectory.'/config/admin_config.yaml');
                return $convertedYaml;
                break;
            case 'parameters':
                $convertedYaml = $this->getConfigFileConvertedInObject($this->rootDirectory.'/config/parameters.yaml');
                return $convertedYaml;
                break;
            case 'routes':
                $convertedYaml = $this->getConfigFileConvertedInObject($this->rootDirectory.'/config/routes.yaml');
                return $convertedYaml;
                break;
            case 'users':
                $convertedYaml = $this->getConfigFileConvertedInObject($this->rootDirectory.'/datamodel/users.yaml');
                return $convertedYaml;
                break;
            case 'templates':
                $convertedYaml = $this->getConfigFileConvertedInObject($this->rootDirectory.'/datamodel/templates.yaml');
                return $convertedYaml;
                break;
            case 'files':
                $convertedYaml = $this->getConfigFileConvertedInObject($this->rootDirectory.'/datamodel/files.yaml');
                return $convertedYaml;
                break;
            case 'forms':
                $convertedYaml = $this->getConfigFileConvertedInObject($this->rootDirectory.'/datamodel/forms.yaml');
                return $convertedYaml;
                break;
            case 'blocks':
                $convertedYaml = $this->getConfigFileConvertedInObject($this->rootDirectory.'/datamodel/blocks.yaml');
                return $convertedYaml;
                break;
            case 'pages':
                $convertedYaml = $this->getConfigFileConvertedInObject($this->rootDirectory.'/datamodel/pages.yaml');
                return $convertedYaml;
                break;
            case 'menus':
                $convertedYaml = $this->getConfigFileConvertedInObject($this->rootDirectory.'/datamodel/menus.yaml');
                return $convertedYaml;
                break;
            default:
                return null;
                break;
        }        
    }

    /**
     * configFileUpdate() - Update config {$data} yaml file in ./datamodel/{$command}.yaml
     * 
     * @param string $command
     * @param array|null $data
     */
    public function configFileUpdate(
        string $command,
        $data
    )
    {
        $yaml = Yaml::dump($data);
        switch ($command) {
            case 'admin_config':
                try {
                    file_put_contents($this->rootDirectory.'/config/admin_config.yaml', $yaml);
                    return true;
                }
                catch (ParseException $e)
                {
                    printf('Unable to parse the YAML string: %s', $e->getMessage());
                    return false;
                }
                break;
            case 'parameters':
                try {
                    file_put_contents($this->rootDirectory.'/config/parameters.yaml', $yaml);
                    return true;
                }
                catch (ParseException $e)
                {
                    printf('Unable to parse the YAML string: %s', $e->getMessage());
                    return false;
                }
                break;
            case 'routes':
                try {
                    file_put_contents($this->rootDirectory.'/config/routes.yaml', $yaml);
                    return true;
                }
                catch (ParseException $e)
                {
                    printf('Unable to parse the YAML string: %s', $e->getMessage());
                    return false;
                }
                break;
            case 'users':
                try {
                    file_put_contents($this->rootDirectory.'/datamodel/users.yaml', $yaml);
                    return true;
                }
                catch (ParseException $e)
                {
                    printf('Unable to parse the YAML string: %s', $e->getMessage());
                    return false;
                }
                break;
            case 'templates':
                try {
                    file_put_contents($this->rootDirectory.'/datamodel/templates.yaml', $yaml);
                    return true;
                }
                catch (ParseException $e)
                {
                    printf('Unable to parse the YAML string: %s', $e->getMessage());
                    return false;
                }
                break;
            case 'files':
                try {
                    file_put_contents($this->rootDirectory.'/datamodel/files.yaml', $yaml);
                    return true;
                }
                catch (ParseException $e)
                {
                    printf('Unable to parse the YAML string: %s', $e->getMessage());
                    return false;
                }
                break;
            case 'forms':
                try {
                    file_put_contents($this->rootDirectory.'/datamodel/forms.yaml', $yaml);
                    return true;
                }
                catch (ParseException $e)
                {
                    printf('Unable to parse the YAML string: %s', $e->getMessage());
                    return false;
                }
                break;
            case 'blocks':
                try {
                    file_put_contents($this->rootDirectory.'/datamodel/blocks.yaml', $yaml);
                    return true;
                }
                catch (ParseException $e)
                {
                    printf('Unable to parse the YAML string: %s', $e->getMessage());
                    return false;
                }
                break;
            case 'pages':
                try {
                    file_put_contents($this->rootDirectory.'/datamodel/pages.yaml', $yaml);
                    return true;
                }
                catch (ParseException $e)
                {
                    printf('Unable to parse the YAML string: %s', $e->getMessage());
                    return false;
                }
                break;
            case 'menus':
                try {
                    file_put_contents($this->rootDirectory.'/datamodel/menus.yaml', $yaml);
                    return true;
                }
                catch (ParseException $e)
                {
                    printf('Unable to parse the YAML string: %s', $e->getMessage());
                    return false;
                }
                break;
            default:
                printf('Unable to find command');
                return false;
                break;
        }
    }

      /**
     * getConfigFile() Get yaml file converted in php object depending of an Index
     *
     * @var string $fileToConvert
     * @var string $filename
     * @return Object|null $convertedYaml
     */
    public function getDatamodelFile(string $fileToConvert, string $filename) {
        switch ($fileToConvert) {
            case 'users':
                $convertedYaml = $this->getConfigFileConvertedInObject($this->rootDirectory.'/datamodel/users/'.$filename.'.yaml');
                return $convertedYaml;
                break;
            case 'templates':
                $convertedYaml = $this->getConfigFileConvertedInObject($this->rootDirectory.'/datamodel/templates/'.$filename.'.yaml');
                return $convertedYaml;
                break;
            case 'files':
                $convertedYaml = $this->getConfigFileConvertedInObject($this->rootDirectory.'/datamodel/files/'.$filename.'.yaml');
                return $convertedYaml;
                break;
            case 'forms':
                $convertedYaml = $this->getConfigFileConvertedInObject($this->rootDirectory.'/datamodel/forms/'.$filename.'.yaml');
                return $convertedYaml;
                break;
            case 'blocks':
                $convertedYaml = $this->getConfigFileConvertedInObject($this->rootDirectory.'/datamodel/blocks/'.$filename.'.yaml');
                return $convertedYaml;
                break;
            case 'pages':
                $convertedYaml = $this->getConfigFileConvertedInObject($this->rootDirectory.'/datamodel/pages/'.$filename.'.yaml');
                return $convertedYaml;
                break;
            case 'menus':
                $convertedYaml = $this->getConfigFileConvertedInObject($this->rootDirectory.'/datamodel/menus/'.$filename.'.yaml');
                return $convertedYaml;
                break;
            default:
                return null;
                break;
        }        
    }

    /**
     * dataModelUpdate() - Update data {$data} model yaml file in ./datamodel/{$command}/{$filename}
     * 
     * @param string $command
     * @param array|null $data
     * @param string $filename
     */
    public function dataModelUpdate(
        string $command,
        $data,
        string $filename
    )
    {
        $yaml = Yaml::dump($data);
        switch ($command) {
            case 'users':
                try {
                    file_put_contents($this->rootDirectory.'/datamodel/users/'.$filename.'.yaml', $yaml);
                    return true;
                }
                catch (ParseException $e)
                {
                    printf('Unable to parse the YAML string: %s', $e->getMessage());
                    return false;
                }
                break;
            case 'templates':
                try {
                    file_put_contents($this->rootDirectory.'/datamodel/templates/'.$filename.'.yaml', $yaml);
                    return true;
                }
                catch (ParseException $e)
                {
                    printf('Unable to parse the YAML string: %s', $e->getMessage());
                    return false;
                }
                break;
            case 'files':
                try {
                    file_put_contents($this->rootDirectory.'/datamodel/files/'.$filename.'.yaml', $yaml);
                    return true;
                }
                catch (ParseException $e)
                {
                    printf('Unable to parse the YAML string: %s', $e->getMessage());
                    return false;
                }
                break;
            case 'forms':
                try {
                    file_put_contents($this->rootDirectory.'/datamodel/forms/'.$filename.'.yaml', $yaml);
                    return true;
                }
                catch (ParseException $e)
                {
                    printf('Unable to parse the YAML string: %s', $e->getMessage());
                    return false;
                }
                break;
            case 'blocks':
                try {
                    file_put_contents($this->rootDirectory.'/datamodel/blocks/'.$filename.'.yaml', $yaml);
                    return true;
                }
                catch (ParseException $e)
                {
                    printf('Unable to parse the YAML string: %s', $e->getMessage());
                    return false;
                }
                break;
            case 'pages':
                try {
                    file_put_contents($this->rootDirectory.'/datamodel/pages/'.$filename.'.yaml', $yaml);
                    return true;
                }
                catch (ParseException $e)
                {
                    printf('Unable to parse the YAML string: %s', $e->getMessage());
                    return false;
                }
                break;
            case 'menus':
                try {
                    file_put_contents($this->rootDirectory.'/datamodel/menus/'.$filename.'.yaml', $yaml);
                    return true;
                }
                catch (ParseException $e)
                {
                    printf('Unable to parse the YAML string: %s', $e->getMessage());
                    return false;
                }
                break;
            default:
                printf('Unable to find command');
                return false;
                break;
        }
    }
  
}
