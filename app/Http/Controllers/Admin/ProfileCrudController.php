<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ProfileRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class ProfileCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ProfileCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Profile');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/profile');
        $this->crud->setEntityNameStrings('profile', 'profiles');
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
            'label' => 'First name',
            'name'  => 'first_name',
        ]);

        $this->crud->addField([
            'label' => 'Last name',
            'name'  => 'last_name',
        ]);

        $this->crud->addField([
            'label' => 'Email',
            'name'  => 'email',
            'type' => 'email',
        ]);

        $this->crud->addField([
            'label' => 'Phone',
            'name'  => 'phone',
        ]);

        $this->crud->addField([
            'label' => 'Mobile',
            'name'  => 'mobile',
        ]);

        $this->crud->addField([
            'label' => 'Avatar',
            'name'  => 'avatar',
            'type' => 'image',
            'upload' => true,
            'crop' => true, // set to true to allow cropping, false to disable
            'aspect_ratio' => 1, // ommit or set to 0 to allow any aspect ratio
        ]);

        $this->crud->addField([
            'label' => 'User',
            'type'  => 'select2',
            'name'  => 'user_id',
            'entity'=> 'user', //
            'attribute' => 'name',
            'model' => 'App\Models\User',
        ]);

        $this->crud->addField([
            'label' => 'Réseau National',
            'type'  => 'select2',
            'name'  => 'reseau_id',
            'entity'=> 'reseau', //
            'attribute' => 'name',
            'model' => 'App\Models\Structure',
        ]);

        $this->crud->addField([
            'label' => 'Référent departement',
            'name'  => 'referent_department',
            'type' => 'select_from_array',
            'allows_null' => true,
            'options' => config('taxonomies.departments.terms'),
        ]);

        // LIST TABLE
        $this->crud->addColumn([
            'label' => 'ID',
            'name'  => 'id',
        ]);

        $this->crud->addColumn([
            'label' => 'Avatar',
            'name'  => 'avatar',
            'type' => 'image',
            'height' => '30px',
            'width' => '30px',
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
            'label' => 'Email',
            'name'  => 'email',
        ]);

        $this->crud->addColumn([
            'label' => 'User',
            'type'  => 'select',
            'name'  => 'user_id',
            'entity'=> 'user',
            'attribute' => 'name',
            'model' => 'App\Models\User'
        ]);

        // add asterisk for fields that are required in ProfileRequest
        $this->crud->setRequiredFields(ProfileRequest::class, 'create');
        $this->crud->setRequiredFields(ProfileRequest::class, 'edit');
    }

    public function store(ProfileRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(ProfileRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
