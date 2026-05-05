<?php

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

$asesorRole = Role::findByName('asesor', 'web');

$signaturePermissions = Permission::where('name', 'like', '%signature::inbox%')
    ->where(function($q) {
        $q->where('name', 'like', 'view_%')
          ->orWhere('name', 'like', 'update_%');
    })
    ->get();

$asesorRole->givePermissionTo($signaturePermissions);

echo "Signature inbox permissions added to Asesor role.\n";
