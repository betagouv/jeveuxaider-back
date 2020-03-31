<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\FaqRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class ProfileCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class FaqCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Faq');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/faq');
        $this->crud->setEntityNameStrings('faq', 'faqs');
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
            'label' => 'Description',
            'name'  => 'description',
            'type' => 'wysiwyg'
        ]);

        $this->crud->addField([
            'label' => 'Poids',
            'name'  => 'weight',
            'type' => 'number',
            'default' => 0
        ]);

        // LIST TABLE
        $this->crud->addColumn([
            'label' => 'ID',
            'name'  => 'id',
        ]);

        $this->crud->addColumn([
            'label' => 'Poids',
            'name'  => 'weight',
        ]);

        $this->crud->addColumn([
            'label' => 'Title',
            'name'  => 'title',
        ]);

        // add asterisk for fields that are required in FaqRequest
        $this->crud->setRequiredFields(FaqRequest::class, 'create');
        $this->crud->setRequiredFields(FaqRequest::class, 'edit');
    }

    public function store(FaqRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(FaqRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
