<?php

namespace App\Exports;

use App\Models\Territoire;
use Generator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromGenerator;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DoublonsTerritoiresExport implements FromGenerator, WithHeadings
{
    use Exportable;

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function generator(): Generator
    {

        $searchValue = $this->request->input('search') ?? null;

        $accent = 'âãäåāăąÁÂÃÄÅĀĂĄèééêëēĕėęěĒĔĖĘĚìíîïìĩīĭÌÍÎÏÌĨĪĬóôõöōŏőÒÓÔÕÖŌŎŐùúûüũūŭůÙÚÛÜŨŪŬŮ';
        $sansAccent = 'aaaaaaaaaaaaaaaeeeeeeeeeeeeeeeiiiiiiiiiiiiiiiiooooooooooooooouuuuuuuuuuuuuuuu';
        $selectRawTranslate = "translate(LOWER(REPLACE(REPLACE(name, ' ', ''), '-', '')),'$accent', '$sansAccent') as trimname";
        $whereRawTranslate = "translate(LOWER(REPLACE(REPLACE(name, ' ', ''), '-', '')),'$accent', '$sansAccent') = translate(LOWER(REPLACE(REPLACE(?, ' ', ''), '-', '')),'$accent', '$sansAccent')";

        $doublons = Territoire::selectRaw("$selectRawTranslate, COUNT(*)")
            ->where('state', 'validated')
            ->where('type', 'city')
            ->when($searchValue, function ($query) use ($searchValue) {
                $query->where('name', 'ilike', '%' . $searchValue . '%');
            })
            ->groupByRaw('trimname')
            ->havingRaw('count(*) > 1')
            ->orderBy('trimname');


        foreach ($doublons->get() as $doublon) {
            $territoires = Territoire::whereRaw($whereRawTranslate, [$doublon['trimname']])
                ->where('state', 'validated')
                ->where('type', 'city')
                ->get();

            foreach ($territoires as $territoire) {
                yield [
                    $doublon['trimname'],
                    $territoire->id,
                    $territoire->name,
                    $territoire->is_published,
                    $territoire->structure_id,
                    $territoire->created_at,
                    $territoire->full_url,
                ];
            }
        }
    }

    public function headings(): array
    {
        return [
            'trimname',
            'id',
            'name',
            'is_published',
            'structure_id',
            'created_at',
            'full_url',
        ];
    }
}
