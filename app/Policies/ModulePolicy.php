<?php

namespace App\Policies;

use App\Models\User;

class ModulePolicy
{
    /**
     * Verifica si un usuario tiene permiso para realizar una acción en un módulo específico.
     *
     * @param  User   $user
     * @param  string $module
     * @param  string $action
     * @return bool
     */
    public function canPerform(User $user, string $module, string $action): bool
    {
        $policy = $user->getPolicyForModule($module);

        if (!$policy) {
            // No hay política definida para el módulo, denegar por defecto
            return false;
        }

        $permissions = $policy->permissions;

        // Validar si la acción está definida y si su valor es '1' (permitido)
        return isset($permissions[$action]) && $permissions[$action] === '1';
    }
}
