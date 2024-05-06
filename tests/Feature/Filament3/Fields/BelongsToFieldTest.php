<?php

use App\Enums\CrudFieldTypes;
use App\Models\Crud;
use App\Models\CrudField;
use App\Models\CrudFieldOptions;
use Generators\Filament3\Generators\Fields\RetrieveGeneratorForField;

test('belongs to field can be displayed in form with basic data', function () {
    $field = new CrudField([
        'key' => 'name',
        'label' => 'Name',
        'type' => CrudFieldTypes::BELONGS_TO,
        'in_create' => true,
        'in_edit' => true,
    ]);
    $field->setRelation(
        'crudFieldOptions',
        (new CrudFieldOptions([
            'relationship' => 'permissions',
        ]))
            ->setRelation('relatedCrudField', new CrudField([
                'key' => 'title',
            ]))
            ->setRelation('crud', new Crud([
                'title' => 'permissions',
            ]))
    );

    $generator = RetrieveGeneratorForField::for($field);
    $output = $generator->formComponent();

    expect($output)
        ->toBeString()
        ->toContain('Select::make(\'name_id\')')
        ->toContain('->relationship(\'permissions\', \'title\')');
});

test('belongs to field can be hidden on edit', function () {
    $field = new CrudField([
        'key' => 'name',
        'label' => 'Name',
        'type' => CrudFieldTypes::BELONGS_TO,
        'in_create' => true,
        'in_edit' => false,
    ]);

    $field->setRelation(
        'crudFieldOptions',
        (new CrudFieldOptions([
            'relationship' => 'permissions',
        ]))
            ->setRelation('relatedCrudField', new CrudField([
                'key' => 'title',
            ]))
            ->setRelation('crud', new Crud([
                'title' => 'permissions',
            ]))
    );

    $generator = RetrieveGeneratorForField::for($field);
    $output = $generator->formComponent();

    expect($output)
        ->toBeString()
        ->toContain('->hiddenOn(\'edit\')');
});

test('belongs to field can be hidden on create', function () {
    $field = new CrudField([
        'key' => 'name',
        'label' => 'Name',
        'type' => CrudFieldTypes::BELONGS_TO,
        'in_create' => false,
        'in_edit' => true,
    ]);

    $field->setRelation(
        'crudFieldOptions',
        (new CrudFieldOptions([
            'relationship' => 'permissions',
        ]))
            ->setRelation('relatedCrudField', new CrudField([
                'key' => 'title',
            ]))
            ->setRelation('crud', new Crud([
                'title' => 'permissions',
            ]))
    );

    $generator = RetrieveGeneratorForField::for($field);
    $output = $generator->formComponent();

    expect($output)
        ->toBeString()
        ->toContain('->hiddenOn(\'create\')');
});

test('belongs to field can be required', function () {
    $field = new CrudField([
        'key' => 'name',
        'label' => 'Name',
        'type' => CrudFieldTypes::BELONGS_TO,
        'in_create' => true,
        'in_edit' => true,
        'validation' => 'required',
    ]);
    $field->setRelation(
        'crudFieldOptions',
        (new CrudFieldOptions([
            'relationship' => 'permissions',
        ]))
            ->setRelation('relatedCrudField', new CrudField([
                'key' => 'title',
            ]))
            ->setRelation('crud', new Crud([
                'title' => 'permissions',
            ]))
    );

    $generator = RetrieveGeneratorForField::for($field);
    $output = $generator->formComponent();

    expect($output)
        ->toBeString()
        ->toContain('->required(true)');
});

test('belongs to field can have placeholder', function () {
    $field = new CrudField([
        'key' => 'name',
        'label' => 'Name',
        'type' => CrudFieldTypes::BELONGS_TO,
        'in_create' => true,
        'in_edit' => true,
        'tooltip' => 'Enter your name',
    ]);

    $field->setRelation(
        'crudFieldOptions',
        (new CrudFieldOptions([
            'relationship' => 'permissions',
        ]))
            ->setRelation('relatedCrudField', new CrudField([
                'key' => 'title',
            ]))
            ->setRelation('crud', new Crud([
                'title' => 'permissions',
            ]))
    );

    $generator = RetrieveGeneratorForField::for($field);
    $output = $generator->formComponent();

    expect($output)
        ->toBeString()
        ->toContain('->placeholder(\'Enter your name\')');
});

test('belongs to field can have various names', function ($key, $label, $model, $fieldName, $expected) {
    $field = new CrudField([
        'id' => 1,
        'key' => 'name',
        'label' => 'Name',
        'type' => CrudFieldTypes::BELONGS_TO,
        'in_create' => true,
        'in_edit' => true,
    ]);

    $field->setRelation(
        'crudFieldOptions',
        (new CrudFieldOptions([
            'relationship' => $model,
        ]))
            ->setRelation('relatedCrudField', new CrudField([
                'key' => $fieldName,
            ]))
            ->setRelation('crud', new Crud([
                'title' => $model,
            ]))
    );

    $generator = RetrieveGeneratorForField::for($field);
    $output = $generator->formComponent();

    expect($output)
        ->toBeString()
        ->toContain('Select::make(\'name_id\')')
        ->toContain('->relationship(\''.$model.'\', \''.$fieldName.'\')');
})->with([
    [
        'first_name',
        'First Name',
        'permissions',
        'title',
        'first_name',
    ],
    [
        'randomString',
        'Random String',
        'new roles',
        'title',
        'randomString',
    ],
    [ // We expect the key to not be mutated from the CRUD
        'some gaps',
        'Some Gaps',
        'randomCompany',
        'visual title',
        'some gaps',
    ],
    [
        'related.dot',
        'Related Dot',
        'related',
        'field',
        'related.dot',
    ],
]);

test('belongs to field can be displayed on a table', function () {
    $field = new CrudField([
        'key' => 'name',
        'label' => 'Name',
        'type' => CrudFieldTypes::BELONGS_TO,
        'in_create' => true,
        'in_edit' => true,
    ]);

    $field->setRelation(
        'crudFieldOptions',
        (new CrudFieldOptions([
            'relationship' => 'permissions',
        ]))
            ->setRelation('relatedCrudField', new CrudField([
                'key' => 'title',
            ]))
            ->setRelation('crud', new Crud([
                'title' => 'permissions',
            ]))
    );

    $generator = RetrieveGeneratorForField::for($field);
    $output = $generator->tableColumn();

    expect($output)
        ->toBeString()
        ->toContain('TextColumn::make(\'permissions.title\')');
});

test('belongs to field can be displayed on a table with various names', function ($key, $label, $model, $fieldName, $expected) {
    $field = new CrudField([
        'key' => 'name',
        'label' => 'Name',
        'type' => CrudFieldTypes::BELONGS_TO,
        'in_create' => true,
        'in_edit' => true,
    ]);
    $field->setRelation(
        'crudFieldOptions',
        (new CrudFieldOptions([
            'relationship' => $model,
        ]))
            ->setRelation('relatedCrudField', new CrudField([
                'key' => $fieldName,
            ]))
            ->setRelation('crud', new Crud([
                'title' => $model,
            ]))
    );

    $generator = RetrieveGeneratorForField::for($field);
    $output = $generator->tableColumn();

    expect($output)
        ->toBeString()
        ->toContain('TextColumn::make(\''.$model.'.'.$fieldName.'\')');
})->with([
    [
        'first_name',
        'First Name',
        'permissions',
        'title',
        'first_name',
    ],
    [
        'randomString',
        'Random String',
        'new_roles',
        'title',
        'randomString',
    ],
    [ // We expect the key to not be mutated from the CRUD
        'some gaps',
        'Some Gaps',
        'randomcompany',
        'visual_title',
        'some gaps',
    ],
    [
        'related.dot',
        'Related Dot',
        'related',
        'field',
        'related.dot',
    ],
]);

it('belongs to field gets correct migration', function ($crudTitle, $relatedCrudTitle, $expectedFirstKey, $expectedFirstTable) {
    $crud = new Crud([
        'title' => $crudTitle,
        'visual_title' => 'does not matter',
    ]);
    $field = new CrudField([
        'key' => $expectedFirstKey,
        'label' => 'Name',
        'type' => CrudFieldTypes::BELONGS_TO,
        'in_create' => true,
        'in_edit' => true,
    ]);
    $field->setRelation('crud', $crud);
    $field->setRelation(
        'crudFieldOptions',
        (new CrudFieldOptions([
            'relationship' => 'does not matter',
        ]))
            ->setRelation('relatedCrudField', new CrudField([
                'key' => 'does not matter',
            ]))
            ->setRelation('crud', new Crud([
                'title' => $relatedCrudTitle,
            ]))
    );

    $generator = RetrieveGeneratorForField::for($field);
    $output = $generator->getMigrationLine();

    expect($output)
        ->toBeString()
        ->toContain('$table->foreignId(\''.$expectedFirstKey.'\')->constrained(\''.$expectedFirstTable.'\')');
})->with([
    [
        'Permission',
        'Role',
        'role_id',
        'roles',
    ],
    [
        'AssetStatus',
        'AssetHistory',
        'asset_history_id',
        'asset_histories',
    ],
    [
        'User',
        'Role',
        'role_id',
        'roles',
    ],
    [
        'CompanyData',
        'Company',
        'company_id', // This probably needs a special case!!!
        'companies', // This probably needs a special case!!!
    ],
]);
