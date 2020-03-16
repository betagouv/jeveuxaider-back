<?php

namespace App\Imports;

use App\Models\Young;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Validation\Rule;
use Spatie\Geocoder\Facades\Geocoder;

class YoungsPartialImportable implements ToCollection, WithHeadingRow, WithProgressBar
{
    use Importable;

    protected $failures;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $validator = Validator::make($row->toArray(), [
                'action' => 'in:SUPPRIMER,REMPLACER,CRÉER',
            ]);

            if (!$validator->fails()) {
                switch ($row['action']) {
                    case 'SUPPRIMER':
                        $this->deleteYoung($row);
                    break;
                    case 'REMPLACER':
                        $this->updateYoung($row);
                    break;
                    case 'CRÉER':
                        $this->createYoung($row);
                    break;
                }
            } else {
                foreach ($validator->errors()->messages() as $message) {
                    $this->failures[] = $row['email'] . ': ' . $message[0];
                }
            }
        }
    }

    public function getFailures()
    {
        return $this->failures;
    }

    private function createYoung($row)
    {
        $validator = Validator::make($row->toArray(), [
            'email' => 'required|email|unique:youngs',
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:2',
            'city' => 'required'
        ]);

        if (!$validator->fails()) {
            $geocode = Geocoder::getCoordinatesForAddress($row['city']);

            Young::create([
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'email' => $row['email'],
                'phone' => $row['phone'],
                'department' => $row['department'],
                'address' => $row['address'],
                'zip' => $row['zip'],
                'city' => $row['city'],
                'regular_city' => $row['city'],
                'regular_latitude' => $geocode['lat'],
                'regular_longitude' => $geocode['lng']
            ]);
        } else {
            foreach ($validator->errors()->messages() as $message) {
                $this->failures[] = 'INSERT ' . $row['email'] . ' : ' . $message[0];
            }
        }
    }

    private function updateYoung($row)
    {
        $young = Young::find($row['id']);
        if ($young) {
            $values = $row->toArray();

            if (!empty($values['city'])) {
                $values['regular_city'] = $values['city'];
                $geocode = Geocoder::getCoordinatesForAddress($row['city']);
                if ($geocode['lat'] && $geocode['lng']) {
                    $values['regular_latitude'] = $geocode['lat'];
                    $values['regular_longitude'] = $geocode['lng'];
                }
            }

            $validator = Validator::make($row->toArray(), [
                'email' => [
                    'sometimes',
                    'email',
                    Rule::unique('youngs')->ignore($young->id),
                ],
                'first_name' => 'sometimes|min:3',
                'last_name' => 'sometimes|min:2',
            ]);

            if (!$validator->fails()) {
                $young->update(array_filter($values));
            } else {
                foreach ($validator->errors()->messages() as $message) {
                    $this->failures[] = 'UPDATE ' . $row['id'] . ' : ' . $message[0];
                }
            }
        } else {
            $this->failures[] = 'UPDATE ' . $row['id'] . ' : this id does not exists.';
        }
    }

    private function deleteYoung($row)
    {
        $young = Young::find($row['id']);
        if ($young) {
            $young->delete();
        } else {
            $this->failures[] = 'DELETE ' . $row['id'] . ' : this id does not exists.';
        }
    }
}
