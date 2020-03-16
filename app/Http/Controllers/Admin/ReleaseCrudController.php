<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ReleaseRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class ProfileCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ReleaseCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Release');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/release');
        $this->crud->setEntityNameStrings('release', 'releases');
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
            'label' => 'Title',
            'name'  => 'title',
        ]);

        $this->crud->addField([
            'label' => 'Date',
            'name'  => 'date',
            'type' => 'datetime_picker',
            'default' => date("Y-m-d H:i:s")
        ]);

        $this->crud->addField([
            'label' => 'Description',
            'name'  => 'description',
            'type' => 'wysiwyg'
        ]);

        // LIST TABLE
        $this->crud->addColumn([
            'label' => 'ID',
            'name'  => 'id',
        ]);

        $this->crud->addColumn([
            'label' => 'Title',
            'name'  => 'title',
        ]);

        // add asterisk for fields that are required in ReleaseRequest
        $this->crud->setRequiredFields(ReleaseRequest::class, 'create');
        $this->crud->setRequiredFields(ReleaseRequest::class, 'edit');
    }

    public function store(ReleaseRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(ReleaseRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
