<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\UserRequest as StoreRequest;
use App\Http\Requests\UserRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class UserCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\BackpackUser');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/user');
        $this->crud->setEntityNameStrings('user', 'users');
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
            'label' => 'Email',
            'name'  => 'email',
            'type' => 'email',
        ]);

        $this->crud->addField([
            'label' => 'Admin',
            'name'  => 'is_admin',
            'type' => 'checkbox'
        ]);

        // LIST TABLE
        $this->crud->addColumn([
            'label' => 'ID',
            'name'  => 'id',
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'label' => 'Name',
            'name'  => 'name',
        ]);

        $this->crud->addColumn([
            'label' => 'Email',
            'name'  => 'email',
        ]);

        $this->crud->addColumn([
            'label' => 'Structures',
            'type'  => 'select',
            'name'  => 'structures',
            'entity'=> 'structures',
            'attribute' => 'name',
            'visibleInTable' => false
        ]);

        $this->crud->addColumn([
            'label' => 'Missions',
            'type'  => 'select',
            'name'  => 'missions',
            'entity'=> 'missions',
            'attribute' => 'name',
            'visibleInTable' => false
        ]);

        $this->crud->addColumn([
            'label' => 'Profile',
            'type'  => 'select',
            'model' => "App\Models\Profile",
            'name'  => 'profile',
            'entity'=> 'profile',
            'attribute' => 'full_name',
        ]);

        // add asterisk for fields that are required in UserRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here

        // Hash password before save
        if (!empty($request->password)) {
            $request->offsetSet('password', Hash::make($request->password));
        }

        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
