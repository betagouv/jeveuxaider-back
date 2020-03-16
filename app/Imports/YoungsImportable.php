<?php

namespace App\Imports;

use App\Models\Young;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\Importable;

class YoungsImportable implements ToCollection, WithHeadingRow, WithProgressBar
{
    use Importable;

    protected $failures;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $validator = Validator::make($row->toArray(), [
                'first_name' => '',
            ]);

            if (!$validator->fails()) {
                $full_name = explode(' ', $row['full_name']);
                $young = Young::create([
                    'first_name' => $row['firstname'] ? $row['firstname'] : (isset($full_name[1]) ? $full_name[1] : ''),
                    'last_name' => $row['firstname'] ? $row['full_name'] : (isset($full_name[0]) ? $full_name[0] : ''),
                    'email' => $row['email'],
                    'phone' => $row['phone'],
                    'department' => $this->convertDepartment($row['department']),
                    'address' => $row['address'],
                    'zip' => $row['zip'],
                    'city' => $row['city'],
                    'regular_city' => $row['regular_city'],
                    'engaged' => $row['engaged'],
                    'engaged_structure' => $row['engaged_structure'],
                    'interet_defense' => $row['interet_defense'],
                    'interet_defense_type' => $row['interet_defense_type'],
                    'interet_defense_domaine' => $row['interet_defense_domaine'],
                    'interet_defense_motivation' => $row['interet_defense_motivation'],
                    'interet_securite' => $row['interet_securite'],
                    'interet_securite_domaine' => $row['interet_securite_domaine'],
                    'interet_solidarite' => $row['interet_solidarite'],
                    'interet_sante' => $row['interet_sante'],
                    'interet_education' => $row['interet_education'],
                    'interet_culture' => $row['interet_culture'],
                    'interet_sport' => $row['interet_sport'],
                    'interet_environnement' => $row['interet_environnement'],
                    'interet_citoyennete' => $row['interet_citoyennete'],
                    'mission_format' => $this->convertMissionFormat($row['mission_format']),
                    'mission_autonome_projet' => $row['mission_autonome_projet'],
                    'mission_autonome_structure' => $row['mission_autonome_structure'],
                    'contraintes' => $row['contraintes'],
                    'situation' => $row['situation'],
                    'genre' => $row['genre'],
                ]);
            } else {
                foreach ($validator->errors()->messages() as $message) {
                    $this->failures[] = $row['firstname'] . ': ' . $message[0];
                }
            }
        }
    }

    public function getFailures()
    {
        return $this->failures;
    }

    private function convertMissionFormat($format)
    {
        switch ($format) {
            case "De façon continue : mission pendant 12 jours":
                return "Continue";
            break;
            case "De façon perlée : 84 heures, réparties tout au long de l'année":
                return "Perlée";
            break;
            case "De façon autonome, en montant vous-même, avec d'autres personnes, un projet en faveur de l'intérêt général":
                return "Autonome";
            break;
        }
    }

    private function convertDepartment($department)
    {
        $options = config('taxonomies.departments.terms');

        // Correction ortographe
        switch ($department) {
            case "Haute-Saone":
                $department = 'Haute-Saône';
                break;
            case "Haute-Pyrénées":
                $department = 'Hautes-Pyrénées';
                break;
            case "Val d'oise":
                $department = "Val-d'Oise";
                break;
        }

        $num = array_search($department, $options);

        return $num ? $num : null;
    }
}
