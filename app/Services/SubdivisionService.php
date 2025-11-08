<?php

namespace App\Services;

use App\Models\Subdivision;
use App\Traits\LogsActivity;

class SubdivisionService
{
    use LogsActivity;

    /**
     * Get paginated subdivisions with company relationship.
     */
    public function getPaginatedSubdivisions(int $perPage = 15)
    {
        return Subdivision::with('company:id,name')->latest()->paginate($perPage);
    }

    /**
     * Get all subdivisions ordered by name.
     */
    public function getAllOrdered()
    {
        return Subdivision::orderBy('name')->get();
    }

    /**
     * Create a new subdivision.
     */
    public function create(array $data): Subdivision
    {
        $subdivision = Subdivision::create($data);
        
        self::logActivity('Subdivisions', 'Created', 'Subdivision', $subdivision->id, null, $data);
        
        return $subdivision;
    }

    /**
     * Update an existing subdivision.
     */
    public function update(Subdivision $subdivision, array $data): Subdivision
    {
        $oldValues = $subdivision->toArray();
        
        $subdivision->update($data);
        
        self::logActivity('Subdivisions', 'Updated', 'Subdivision', $subdivision->id, $oldValues, $data);
        
        return $subdivision;
    }

    /**
     * Update subdivision message.
     */
    public function updateMessage(Subdivision $subdivision, string $message): Subdivision
    {
        $oldValues = ['subdivision_message' => $subdivision->subdivision_message];
        
        $subdivision->update(['subdivision_message' => $message]);
        
        self::logActivity('Subdivisions', 'Updated Message', 'Subdivision', $subdivision->id, $oldValues, ['subdivision_message' => $message]);
        
        return $subdivision;
    }

    /**
     * Delete a subdivision.
     */
    public function delete(Subdivision $subdivision): bool
    {
        $oldValues = $subdivision->toArray();
        $result = $subdivision->delete();
        
        self::logActivity('Subdivisions', 'Deleted', 'Subdivision', $subdivision->id, $oldValues, null);
        
        return $result;
    }

    /**
     * Close a subdivision (set status to closed).
     */
    public function close(Subdivision $subdivision): Subdivision
    {
        $oldValues = ['status' => $subdivision->status];
        
        $subdivision->update(['status' => 'closed']);
        
        self::logActivity('Subdivisions', 'Closed', 'Subdivision', $subdivision->id, $oldValues, ['status' => 'closed']);
        
        return $subdivision;
    }

    /**
     * Open a subdivision (set status to active).
     */
    public function open(Subdivision $subdivision): Subdivision
    {
        $oldValues = ['status' => $subdivision->status];
        
        $subdivision->update(['status' => 'active']);
        
        self::logActivity('Subdivisions', 'Opened', 'Subdivision', $subdivision->id, $oldValues, ['status' => 'active']);
        
        return $subdivision;
    }
}
