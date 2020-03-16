<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\StructureRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class StructureCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class StructureCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Structure');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/structure');
        $this->crud->setEntityNameStrings('structure', 'structures');
        $this->crud->allowAccess(['show']);

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
            'default' => 'Validée',
            'allows_null' => false,
            'options' => config('taxonomies.structure_workflow_states.terms'),
        ]);

        $this->crud->addField([
            'label' => 'Réseau',
            'name'  => 'is_reseau',
            'type' => 'checkbox'
        ]);

        $this->crud->addField([
            'label' => 'Siret',
            'name'  => 'siret',
        ]);

        $this->crud->addField([
            'label' => 'Réseau national',
            'type'  => 'select',
            'name'  => 'reseau_id',
            'entity'=> 'reseau', //
            'attribute' => 'name',
            'model' => 'App\Models\Structure',
        ]);

        $this->crud->addField([
            'label' => 'Legal status',
            'name'  => 'statut_juridique',
            'type' => 'select_from_array',
            'allows_null' => true,
            'options' => config('taxonomies.structure_legal_status.terms'),
        ]);

        $this->crud->addField([
            'label' => 'Association types',
            'name'  => 'association_types',
            'type' => 'select2_from_array',
            'allows_null' => true,
            'allows_multiple' => true,
            'options' => config('taxonomies.association_types.terms'),
        ]);

        $this->crud->addField([
            'label' => 'Structure publique types',
            'name'  => 'structure_publique_type',
            'type' => 'select_from_array',
            'allows_null' => true,
            'options' => config('taxonomies.structure_publique_types.terms'),
        ]);

        $this->crud->addField([
            'label' => 'Structure publique etat types',
            'name'  => 'structure_publique_etat_type',
            'type' => 'select_from_array',
            'allows_null' => true,
            'options' => config('taxonomies.structure_publique_etat_types.terms'),
        ]);

        $this->crud->addField([
            'label' => 'Structure privée types',
            'name'  => 'structure_privee_type',
            'type' => 'select_from_array',
            'allows_null' => true,
            'options' => config('taxonomies.structure_privee_types.terms'),
        ]);

        $this->crud->addField([
            'label' => 'Logo',
            'name'  => 'logo',
            'type' => 'image',
            'upload' => true,
            'crop' => true, // set to true to allow cropping, false to disable
            'aspect_ratio' => 1, // ommit or set to 0 to allow any aspect ratio
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
            'type' => 'select_from_array',
            'allows_null' => true,
            'options' => config('taxonomies.departments.terms'),
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
            'label' => 'Website',
            'name'  => 'website',
        ]);

        $this->crud->addField([
            'label' => 'Instagram',
            'name'  => 'instagram',
        ]);

        $this->crud->addField([
            'label' => 'Facebook',
            'name'  => 'facebook',
        ]);

        $this->crud->addField([
            'label' => 'Twitter',
            'name'  => 'twitter',
        ]);

        $this->crud->addField([
            'label' => "Responsables",
            'type' => 'select2_multiple',
            'name' => 'responsables', // the method that defines the relationship in your Model GET
            'entity' => 'responsables', // the method that defines the relationship in your Model
            'attribute' => 'full_name', // foreign key attribute that is shown to user
            'model' => "App\Models\Profile", // foreign key model
            'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
        ]);

        $this->crud->addField([
            'label' => "Tuteurs",
            'type' => 'select2_multiple',
            'name' => 'tuteurs', // the method that defines the relationship in your Model
            'entity' => 'tuteurs', // the method that defines the relationship in your Model
            'attribute' => 'full_name', // foreign key attribute that is shown to user
            'model' => "App\Models\Profile", // foreign key model
            'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
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
            'label' => 'Logo',
            'name'  => 'logo',
            'type' => 'image',
            'height' => '30px',
            'width' => '30px',
        ]);

        $this->crud->addColumn([
            'label' => 'Name',
            'name'  => 'name',
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

        $this->crud->addColumn([
            'label' => 'Created at',
            'name'  => 'created_at',
        ]);

        // add asterisk for fields that are required in StructureRequest
        $this->crud->setRequiredFields(StructureRequest::class, 'create');
        $this->crud->setRequiredFields(StructureRequest::class, 'edit');
    }

    public function store(StructureRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(StructureRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
