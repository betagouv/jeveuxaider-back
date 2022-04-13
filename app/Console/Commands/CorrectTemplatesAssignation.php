<?php

namespace App\Console\Commands;

use App\Models\Mission;
use Illuminate\Console\Command;

class CorrectTemplatesAssignation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mission:correct-templates-assignation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Correct Templates Assignation';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 113
        // Je m’engage en tant que secrétaire bénévole au sein d’un bureau de vote lors des élections dans ma commune
        $ids = [
            10654,
            10876,
            14900,
            10721,
            10810,
            10648,
            10701,
            11105,
            11368,
            10428,
            10611,
            11282,
            11115,
            11344,
            11209,
            11200,
            10720,
            10631,
            10632,
            10419,
            10649,
            11332,
            10763,
            11102,
            11233,
            10875,
            11163,
            10793,
            11148,
            11164,
            10198,
            10551,
            11063,
            11064,
            11317,
            11345,
            11361
          ];
        $query = Mission::whereIn('id', $ids)
            ->whereNull('template_id')
            ->whereNull('name');

        $this->info($query->count() . ' missions will be updated to template_id 195');
        if ($this->confirm('Do you wish to continue?')) {
            $query->update(['template_id' => 195]);
        }

        // 119
        // Je m’engage en tant que scrutateur bénévole au sein d’un bureau de vote lors des élections dans ma commune
        $ids = [
            17457,
            11223,
            11306,
            11146,
            10562,
            10816,
            10819,
            10621,
            11147,
            17461,
            17459,
            11253,
            11262,
            10487,
            10897,
            10628,
            10783,
            10711,
            10697,
            13239,
            10707,
            17454,
            11235,
            10629,
            10630,
            11246,
            10841,
            10424,
            11116,
            11169,
            11088,
            11083,
            11241,
            11101,
            10488,
            10512,
            10965,
            10635,
            10665,
            10664,
            10765,
            10789,
            11335,
            10698,
            11208,
            11174,
            10492,
            10692,
            11075,
            11255,
            11363,
            11225,
            10753,
            11043,
            11038,
            10791,
            10817,
            10809,
            10731,
            10728,
            10718,
            10719,
            10708,
            11095,
            11066,
            11069,
            11342,
            10700,
            11106,
            11252,
            10813,
            11303,
            11071,
            10833,
            11271,
            10878,
            10712,
            10690,
            11283,
            10650,
            10605,
            11107,
            11273,
            11036,
            11257,
            11041,
            11352,
            10688,
            11137,
            11250,
            10804,
            11145,
            10736,
            11238,
            10481,
            10889,
            11195,
            10410,
            11199,
            10993,
            10559,
            10683,
            10554,
            10603,
            10874,
            11059,
            11061,
            11067,
            11070,
            11128,
            11129,
            11175,
            11324,
            11326,
            11348
        ];
        $query = Mission::whereIn('id', $ids)
            ->whereNull('template_id')
            ->whereNull('name');

        $this->info($query->count() . ' missions will be updated to template_id 196');
        if ($this->confirm('Do you wish to continue?')) {
            $query->update(['template_id' => 196]);
        }
    }
}
