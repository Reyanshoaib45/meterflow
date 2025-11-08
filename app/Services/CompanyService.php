<?php

namespace App\Services;

use App\Models\Company;
use App\Traits\LogsActivity;

class CompanyService
{
    use LogsActivity;

    /**
     * Get paginated companies.
     */
    public function getPaginatedCompanies(int $perPage = 15)
    {
        return Company::latest()->paginate($perPage);
    }

    /**
     * Create a new company.
     */
    public function create(array $data): Company
    {
        $company = Company::create($data);
        
        self::logActivity('Companies', 'Created', 'Company', $company->id, null, $data);
        
        return $company;
    }

    /**
     * Update an existing company.
     */
    public function update(Company $company, array $data): Company
    {
        $oldValues = $company->toArray();
        
        $company->update($data);
        
        self::logActivity('Companies', 'Updated', 'Company', $company->id, $oldValues, $data);
        
        return $company;
    }

    /**
     * Delete a company.
     */
    public function delete(Company $company): bool
    {
        $oldValues = $company->toArray();
        $result = $company->delete();
        
        self::logActivity('Companies', 'Deleted', 'Company', $company->id, $oldValues, null);
        
        return $result;
    }
}
