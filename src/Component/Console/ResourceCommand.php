<?php

namespace Queue\Component\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;

class ResourceCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('queue:resource:create')
            ->setAliases('q:r:c')
            ->setDescription('Processes the resources and either create it directly on ResourceManager connection')
            ->setDefinition(array(
                new InputArgument(
                    'file',
                    InputArgument::REQUIRED | InputArgument::IS_ARRAY,
                    'File path(s) of resources to be processes.'
                )
            ))
            ->setHelp(<<<EOT
Create resources directly to Queue Server.
EOT
            );
    }
}
