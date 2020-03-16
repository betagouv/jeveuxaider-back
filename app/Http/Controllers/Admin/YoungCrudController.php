<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\YoungRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class ProfileCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class YoungCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Young');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/young');
        $this->crud->setEntityNameStrings('young', 'youngs');
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
            'label' => 'Firstname',
            'name'  => 'first_name',
        ]);

        $this->crud->addField([
            'label' => 'Lastname',
            'name'  => 'last_name',
        ]);

        $this->crud->addField([
            'label' => 'Email',
            'name'  => 'email',
        ]);

        $this->crud->addField([
            'label' => 'Phone',
            'name'  => 'phone',
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
            'label' => 'Département',
            'name'  => 'department',
            'type' => 'select_from_array',
            'allows_null' => true,
            'options' => config('taxonomies.departments.terms'),
        ]);

        $this->crud->addField([
            'label' => 'Regular city',
            'name'  => 'regular_city',
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
            'label' => 'Engaged',
            'name' => 'engaged',
            'type' => 'radio',
            'options' => [
                'Oui' => "Oui",
                'Non' => "Non"
            ],
            'inline' => true,
        ]);

        $this->crud->addField([
            'label' => 'Engaged structure',
            'name'  => 'engaged_structure',
            'type' => 'textarea'
        ]);

        $this->crud->addField([
            'label' => 'Interest defense',
            'name'  => 'interet_defense',
            'type' => 'radio',
            'options' => config('taxonomies.interest_ratings.terms'),
            'inline' => true,
        ]);

        $this->crud->addField([
            'label' => 'Interest defense_type',
            'name'  => 'interet_defense_type',
            'type' => 'radio',
            'options' => config('taxonomies.interet_defense_types.terms'),
            'inline' => true,
        ]);

        $this->crud->addField([
            'label' => 'Interest defense domaine',
            'name'  => 'interet_defense_domaine',
            'type' => 'radio',
            'options' => config('taxonomies.interet_defense_domaines.terms'),
            'inline' => true,
        ]);

        $this->crud->addField([
            'label' => 'Interest defense motivation',
            'name'  => 'interet_defense_motivation',
            'type' => 'textarea'
        ]);

        $this->crud->addField([
            'label' => 'Interest securité',
            'name'  => 'interet_securite',
            'type' => 'radio',
            'options' => config('taxonomies.interest_ratings.terms'),
            'inline' => true,
        ]);

        $this->crud->addField([
            'label' => 'Interest securité domaine',
            'name'  => 'interet_securite_domaine',
            'type' => 'radio',
            'options' => config('taxonomies.interet_securite_domaines.terms'),
            'inline' => true,
        ]);

        $this->crud->addField([
            'label' => 'Interest solidarite',
            'name'  => 'interet_solidarite',
            'type' => 'radio',
            'options' => config('taxonomies.interest_ratings.terms'),
            'inline' => true,
        ]);

        $this->crud->addField([
            'label' => 'Interest sante',
            'name'  => 'interet_sante',
            'type' => 'radio',
            'options' => config('taxonomies.interest_ratings.terms'),
            'inline' => true,
        ]);

        $this->crud->addField([
            'label' => 'Interest education',
            'name'  => 'interet_education',
            'type' => 'radio',
            'options' => config('taxonomies.interest_ratings.terms'),
            'inline' => true,
        ]);

        $this->crud->addField([
            'label' => 'Interest culture',
            'name'  => 'interet_culture',
            'type' => 'radio',
            'options' => config('taxonomies.interest_ratings.terms'),
            'inline' => true,
        ]);

        $this->crud->addField([
            'label' => 'Interest sport',
            'name'  => 'interet_sport',
            'type' => 'radio',
            'options' => config('taxonomies.interest_ratings.terms'),
            'inline' => true,
        ]);

        $this->crud->addField([
            'label' => 'Interest environnement',
            'name'  => 'interet_environnement',
            'type' => 'radio',
            'options' => config('taxonomies.interest_ratings.terms'),
            'inline' => true,
        ]);

        $this->crud->addField([
            'label' => 'Interest citoyenneté',
            'name'  => 'interet_citoyennete',
            'type' => 'radio',
            'options' => config('taxonomies.interest_ratings.terms'),
            'inline' => true,
        ]);

        $this->crud->addField([
            'label' => 'Mission format',
            'name'  => 'mission_format',
            'type' => 'select_from_array',
            'allows_null' => true,
            'options' => config('taxonomies.mission_formats.terms'),
        ]);

        $this->crud->addField([
            'label' => 'Mission autonome project',
            'name'  => 'mission_autonome_projet',
        ]);

        $this->crud->addField([
            'label' => 'Mission autonome structure',
            'name'  => 'mission_autonome_structure',
        ]);

        $this->crud->addField([
            'label' => 'Contraintes',
            'name'  => 'contraintes',
            'type' => 'textarea'
        ]);

        $this->crud->addField([
            'label' => 'Situation',
            'name'  => 'situation',
            'type' => 'select_from_array',
            'allows_null' => true,
            'options' => config('taxonomies.young_situations.terms'),
        ]);

        $this->crud->addField([
            'label' => 'Genre',
            'name' => 'genre',
            'type' => 'radio',
            'options' => [
                'Fille' => "Fille",
                'Garçon' => "Garçon"
            ],
            'inline' => true,
        ]);

        $this->crud->addField([
            'label' => 'Statut',
            'name'  => 'state',
            'type' => 'select_from_array',
            'allows_null' => true,
            'options' => config('taxonomies.young_workflow_states.terms'),
        ]);

        $this->crud->addField([
            'label' => 'Mission',
            'type'  => 'select2',
            'name'  => 'mission_id',
            'entity'=> 'mission', //
            'attribute' => 'name',
            'model' => 'App\Models\Mission',
        ]);

        $this->crud->addField([
            'label' => 'Notes',
            'name'  => 'notes',
            'type' => 'textarea'
        ]);

        // LIST TABLE
        $this->crud->addColumn([
            'label' => 'ID',
            'name'  => 'id',
        ]);

        $this->crud->addColumn([
            'label' => 'First name',
            'name'  => 'first_name',
        ]);

        $this->crud->addColumn([
            'label' => 'Last name',
            'name'  => 'last_name',
        ]);

        $this->crud->addColumn([
            'label' => 'Département',
            'name'  => 'department',
        ]);


        // add asterisk for fields that are required in YoungRequest
        $this->crud->setRequiredFields(YoungRequest::class, 'create');
        $this->crud->setRequiredFields(YoungRequest::class, 'edit');
    }

    public function store(YoungRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(YoungRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
