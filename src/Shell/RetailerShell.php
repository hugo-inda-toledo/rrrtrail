<?php 
namespace App\Shell;

use Cake\Console\Shell;

class RetailerShell extends Shell
{
    public function main()
    {
        $this->out('Hello world.');
    }

    public function heyThere($name = 'Anonymous')
    {
        $this->out('Hey there ' . $name);
    }
}