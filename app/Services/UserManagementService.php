<?php

namespace App\Services;

use App\Models\User;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Hash;

class UserManagementService
{
    use LogsActivity;

    /**
     * Get paginated users with subdivision relationship.
     */
    public function getPaginatedUsers(?string $role = null, int $perPage = 15)
    {
        $query = User::with('assignedSubdivision:id,name');
        
        if ($role) {
            $query->where('role', $role);
        }
        
        return $query->latest()->paginate($perPage);
    }

    /**
     * Create a new user.
     */
    public function create(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        
        $user = User::create($data);
        
        // Don't log password
        $logData = array_diff_key($data, ['password' => '']);
        self::logActivity('Users', 'Created', 'User', $user->id, null, $logData);
        
        return $user;
    }

    /**
     * Update an existing user.
     */
    public function update(User $user, array $data): User
    {
        $oldValues = $user->toArray();
        
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        
        $user->update($data);
        
        // Don't log password
        $logData = array_diff_key($data, ['password' => '']);
        self::logActivity('Users', 'Updated', 'User', $user->id, $oldValues, $logData);
        
        return $user;
    }

    /**
     * Delete a user.
     */
    public function delete(User $user): bool
    {
        $oldValues = array_diff_key($user->toArray(), ['password' => '']);
        $result = $user->delete();
        
        self::logActivity('Users', 'Deleted', 'User', $user->id, $oldValues, null);
        
        return $result;
    }

    /**
     * Update user permissions.
     */
    public function updatePermissions(User $user, array $permissions): User
    {
        $oldValues = ['permissions' => $user->permissions];
        
        $user->update(['permissions' => $permissions]);
        
        self::logActivity('Users', 'Updated Permissions', 'User', $user->id, $oldValues, ['permissions' => $permissions]);
        
        return $user;
    }

    /**
     * Suspend a user.
     */
    public function suspend(User $user): User
    {
        $oldValues = ['is_active' => $user->is_active];
        
        $user->update(['is_active' => false]);
        
        self::logActivity('Users', 'Suspended', 'User', $user->id, $oldValues, ['is_active' => false]);
        
        return $user;
    }

    /**
     * Activate a user.
     */
    public function activate(User $user): User
    {
        $oldValues = ['is_active' => $user->is_active];
        
        $user->update(['is_active' => true]);
        
        self::logActivity('Users', 'Activated', 'User', $user->id, $oldValues, ['is_active' => true]);
        
        return $user;
    }
}
