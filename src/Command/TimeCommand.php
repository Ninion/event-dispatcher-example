<?php
/*
 * This file is part of the SDPHP event-dispatcher-test Package.
 * For the full copyright and license information, 
 * please view the LICENSE file that was distributed 
 * with this source code.
 */

namespace SDPHP\EventDispatcherTest\Command;

use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\GenericEvent;


/**
 * TimeCommand - Description. 
 *
 * @author Juan Manuel Torres <kinojman@gmail.com>
 */
class TimeCommand extends Command
{
    protected $dispatcher;

    public function __construct(EventDispatcher $dispatcher, $name = null)
    {
        parent::__construct($name);
        $this->dispatcher = $dispatcher;
    }

    protected function configure()
    {
        $this
            ->setName('time:show')
            ->setDescription('Main command to get the current time.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $event = new GenericEvent();
        $this->dispatcher->dispatch('time.before_time', $event);                // EVENT BEFORE TIME OBJECT IS CREATED

        while (true) {
            $dateTime = new \DateTime();
            $dateTime->setTimezone(new \DateTimeZone('America/Los_Angeles'));

            $this->dispatcher->dispatch('time.after_time', $event);             // EVENT AFTER TIME OBJECT IS CREATED
            
            $format = 'g:i:s a';
            $this->dispatcher->dispatch('time.before_display', $event);         // EVENT BEFORE DISPLAYING THE TIME

            $output->writeln($dateTime->format($format));

            $sleep = 1;
            $this->dispatcher->dispatch('time.after_display', $event);          // EVENT AFTER TIME HAS BEEN DISPLAYED. 
            
            sleep($sleep);
        }
    }
}
