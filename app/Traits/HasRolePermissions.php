<?php

namespace App\Traits;

trait HasRolePermissions
{
    /**
     * Check if user has permission to perform action
     */
    public function hasPermission($action)
    {
        $role = $this->role;

        $permissions = [
            'admin' => ['*'], // All permissions
            'trainer' => [
                'view_trainers',
                'update_own_trainer',
                'view_technicians',
                'update_technician_partial'
            ],
            'technician' => [
                'view_technicians',
                'update_own_technician'
            ]
        ];

        if ($role === 'admin') {
            return true;
        }

        return isset($permissions[$role]) && in_array($action, $permissions[$role]);
    }

    /**
     * Check if user can update specific fields
     */
    public function canUpdateFields($fields, $resourceType)
    {
        $role = $this->role;

        if ($role === 'admin') {
            return true;
        }

        $allowedFields = $this->getAllowedFieldsByRole($role, $resourceType);

        foreach ($fields as $field) {
            if (!in_array($field, $allowedFields)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get allowed fields by role and resource type
     */
    private function getAllowedFieldsByRole($role, $resourceType)
    {
        $fieldPermissions = [
            'technician' => [
                'technician' => ['phone_1', 'phone_2', 'whatsapp', 'emergency_contact', 'emergency_phone', 'village', 'postal_code', 'skills', 'certifications', 'training']
            ],
            'trainer' => [
                'trainer' => ['phone', 'whatsapp', 'emergency_contact', 'emergency_phone', 'village', 'postal_code', 'skills', 'qualifications', 'certifications', 'languages', 'notes'],
                'technician' => ['phone_1', 'phone_2', 'whatsapp', 'emergency_contact', 'emergency_phone', 'village', 'postal_code', 'skills', 'certifications', 'training', 'languages', 'equipment_list', 'service_areas', 'notes']
            ]
        ];

        return $fieldPermissions[$role][$resourceType] ?? [];
    }
}
