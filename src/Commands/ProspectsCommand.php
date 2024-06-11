<?php

namespace Homeful\Prospects\Commands;

use Illuminate\Console\Command;

class ProspectsCommand extends Command
{
    public $signature = 'prospects';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
