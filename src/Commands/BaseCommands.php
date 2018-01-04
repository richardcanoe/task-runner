<?php

namespace EC\OpenEuropa\TaskRunner\Commands;

use Consolidation\AnnotatedCommand\AnnotatedCommand;
use Consolidation\AnnotatedCommand\AnnotationData;
use Robo\Common\ConfigAwareTrait;
use Robo\Common\IO;
use Robo\Contract\ConfigAwareInterface;
use Robo\Contract\IOAwareInterface;
use Robo\Contract\BuilderAwareInterface;
use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use Robo\Exception\TaskException;
use Robo\LoadAllTasks;
use Robo\Result;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class BaseCommands.
 *
 * @package EC\OpenEuropa\TaskRunner\Commands
 */
class BaseCommands implements BuilderAwareInterface, IOAwareInterface, ConfigAwareInterface
{
    use ConfigAwareTrait;
    use LoadAllTasks;
    use IO;

    /**
     * @param InputInterface $input
     * @param AnnotationData $annotationData
     *
     * @hook init
     */
    public function init(InputInterface $input, AnnotationData $annotationData)
    {
        $this->setInput($input);
    }

    /**
     * @param  string $name
     * @return string
     *
     * @throws TaskException
     */
    protected function getBin($name)
    {
        $filename = $this->getConfig()->get('runner.bin-dir').'/'.$name;
        if (!file_exists($filename) && !$this->isSimulating()) {
            throw new TaskException($this, "Executable '{$filename}' not found.");
        }

        return $filename;
    }

    /**
     * @return bool
     */
    protected function isSimulating()
    {
        return (bool) $this->input()->getOption('simulate');
    }
}
