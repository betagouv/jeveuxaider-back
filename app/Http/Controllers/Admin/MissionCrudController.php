<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\MissionRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class MissionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class MissionCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Mission');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/mission');
        $this->crud->setEntityNameStrings('mission', 'missions');
        $this->crud->allowAccess(['show']);
        $this->crud->enableExportButtons();

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // FORM
        $this->crud->addField([
            'label' => 'Name',
            'name'  => 'name',
        ]);

        $this->crud->addField([
            'label' => 'State',
            'name'  => 'state',
            'type' => 'select_from_array',
            'default' => 'Brouillon',
            'allows_null' => false,
            'options' => config('taxonomies.mission_workflow_states.terms'),
        ]);

        $this->crud->addField([
            'label' => 'Structure',
            'type'  => 'select2',
            'name'  => 'structure_id',
            'entity'=> 'structure', //
            'attribute' => 'name',
            'model' => 'App\Models\Structure',
        ]);

        $this->crud->addField([
            'label' => 'Tuteur',
            'type'  => 'select2',
            'name'  => 'tuteur_id',
            'entity'=> 'tuteur',
            'allows_null' => true,
            'attribute' => 'full_name',
            'model' => 'App\Models\Profile'
        ]);

        $this->crud->addField([
            'label' => 'Domaines',
            'name'  => 'domaines',
            'type' => 'select2_from_array',
            'allows_null' => true,
            'allows_multiple' => true,
            'options' => config('taxonomies.mission_domaines.terms'),
        ]);

        $this->crud->addField([
            'label' => 'Participation max.',
            'name'  => 'participations_max',
            'type' => 'number',
            'default' => 30
        ]);

        $this->crud->addField([
            'label' => 'Format de mission',
            'name'  => 'format',
            'type' => 'select_from_array',
            'allows_null' => true,
            'options' => config('taxonomies.mission_formats.terms'),
        ]);

        $this->crud->addField([
            'label' => 'Date de début',
            'name'  => 'start_date',
            'type' => 'datetime_picker',
            'default' => date("Y-m-d H:i:s")
        ]);

        $this->crud->addField([
            'label' => 'Date de fin',
            'name'  => 'end_date',
            'type' => 'datetime_picker',
            'default' => date("Y-m-d H:i:s", strtotime('+7 days'))
        ]);

        $this->crud->addField([
            'label' => 'Informations complémentaires concernant les dates et horaires de la mission',
            'name'  => 'dates_infos',
            'type' => 'textarea'
        ]);

        $this->crud->addField([
            'label' => 'Périodes',
            'name'  => 'periodes',
            'type' => 'select2_from_array',
            'allows_multiple' => true,
            'allows_null' => true,
            'options' => config('taxonomies.mission_periodes.terms'),
        ]);

        $this->crud->addField([
            'label' => 'Fréquence estimée de la mission',
            'name'  => 'frequence',
            'type' => 'textarea'
        ]);

        $this->crud->addField([
            'label' => 'Description',
            'name'  => 'description',
            'type' => 'textarea'
        ]);

        $this->crud->addField([
            'label' => 'Address',
            'name'  => 'address',
        ]);

        $this->crud->addField([
            'label' => 'Zip',
            'name'  => 'zip',
        ]);

        $this->crud->addField([
            'label' => 'City',
            'name'  => 'city',
        ]);

        $this->crud->addField([
            'label' => 'Department',
            'name'  => 'department',
        ]);

        $this->crud->addField([
            'label' => 'Country',
            'name'  => 'country',
        ]);

        $this->crud->addField([
            'label' => 'Latitude',
            'name'  => 'latitude',
        ]);

        $this->crud->addField([
            'label' => 'Longitude',
            'name'  => 'longitude',
        ]);

        $this->crud->addField([
            'label' => 'Actions concrètes confiées aux volontaires',
            'name'  => 'actions',
            'type' => 'textarea'
        ]);

        $this->crud->addField([
            'label' => 'En quoi la mission proposée permettra-t-elle au volontaire d\'agir en faveur de l\'intérêt général ?',
            'name'  => 'justifications',
            'type' => 'textarea'
        ]);

        $this->crud->addField([
            'label' => 'Y a-t-il des contraintes spécifiques pour cette mission ?',
            'name'  => 'contraintes',
            'type' => 'textarea'
        ]);

        $this->crud->addField([
            'label' => 'Mission ouverte aux personnes en situation de handicap',
            'name'  => 'handicap',
            'type' => 'radio',
            'allows_null' => true,
            'options' => config('taxonomies.mission_handicap.terms'),
        ]);

        $this->crud->addField([
            'label' => 'User',
            'type'  => 'select2',
            'name'  => 'user_id',
            'entity'=> 'user', //
            'attribute' => 'name',
            'model' => 'App\Models\User',
        ]);

        // LIST TABLE
        $this->crud->addColumn([
            'label' => 'ID',
            'name'  => 'id',
        ]);

        $this->crud->addColumn([
            'label' => 'Name',
            'name'  => 'name',
        ]);

        $this->crud->addColumn([
            'label' => 'Structure',
            'type'  => 'select',
            'name'  => 'structure_id',
            'entity'=> 'structure', //
            'attribute' => 'name',
            'model' => 'App\Models\Structure',
        ]);

        $this->crud->addColumn([
            'label' => 'State',
            'name'  => 'state',
        ]);

        $this->crud->addColumn([
            'label' => 'User',
            'type'  => 'select',
            'name'  => 'user_id',
            'entity'=> 'user',
            'attribute' => 'name',
            'model' => 'App\Models\User'
        ]);

        // add asterisk for fields that are required in MissionRequest
        $this->crud->setRequiredFields(MissionRequest::class, 'create');
        $this->crud->setRequiredFields(MissionRequest::class, 'edit');
    }

    public function store(MissionRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(MissionRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
