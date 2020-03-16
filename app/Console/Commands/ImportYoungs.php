<?php

namespace App\Console\Commands;

use App\Imports\YoungsImportable;
use Illuminate\Console\Command;

class ImportYoungs extends Command
{
    protected $signature = 'import:youngs';

    protected $description = 'Import Youngs from Excel';

    public function handle()
    {
        $this->output->title('Starting import');

        $import = (new YoungsImportable())->withOutput($this->output);
        $import->import(storage_path('imports/youngs.xlsx'), null, \Maatwebsite\Excel\Excel::XLSX);

        if ($failures = $import->getFailures()) {
            $this->output->title(count($failures) . ' Fails');
            foreach ($failures as $failure) {
                $this->output->text($failure);
            }
        }

        $this->output->success('FINISHED');
    }
}
