<?php

namespace App\Console\Commands;

use App\Imports\YoungsPartialImportable;
use Illuminate\Console\Command;

class ImportPartialYoungs extends Command
{
    protected $signature = 'import:youngs:partial
                            {filename : The filename to import with his extension Ex. 2020-02-11.xlsx}';

    protected $description = 'Import partial youngs from Excel';

    public function handle()
    {
        $this->output->title('Starting import');

        $filename = $this->argument('filename');
        $this->output->text($filename);

        $import = (new YoungsPartialImportable())->withOutput($this->output);
        $import->import(storage_path('imports/partial/' . $filename), null, \Maatwebsite\Excel\Excel::XLSX);

        if ($failures = $import->getFailures()) {
            $this->output->title(count($failures) . ' Fails');
            foreach ($failures as $failure) {
                $this->output->text($failure);
            }
        }

        $this->output->success('FINISHED');
    }
}
