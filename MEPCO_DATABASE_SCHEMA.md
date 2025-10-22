# MEPCO Employer System - Database Schema

## Overview

This document outlines the complete relational database schema for the MEPCO Employer System, which manages customer meter applications, LESCO/LIS admin roles, and the full data flow from application submission to final approval.

## Tables and Relationships

### 1. Users Table (Modified Existing)

**Migration:** `2025_10_21_000003_add_role_to_users_table.php`

**Columns:**

-   id (Primary Key)
-   name
-   email (Unique)
-   password
-   role (Enum: admin, lis)
-   timestamps()

**Purpose:** Manages system access and authorization.

### 2. Companies Table

**Migration:** `2025_10_21_000004_create_companies_table.php`
**Model:** `App\Models\Company`

**Columns:**

-   id (Primary Key)
-   name (Unique)
-   timestamps()

**Relationships:**

-   HasMany: Subdivisions
-   HasMany: Applications
-   HasMany: ExtraSummaries

### 3. Subdivisions Table

**Migration:** `2025_10_21_000005_create_subdivisions_table.php`
**Model:** `App\Models\Subdivision`

**Columns:**

-   id (Primary Key)
-   company_id (Foreign Key to Companies, Nullable)
-   name
-   code
-   timestamps()

**Relationships:**

-   BelongsTo: Company
-   HasMany: Applications

### 4. Applications Table

**Migration:** `2025_10_21_000006_create_applications_table.php`
**Model:** `App\Models\Application`

**Columns:**

-   id (Primary Key)
-   application_no (Unique)
-   customer_name
-   address
-   phone
-   cnic
-   meter_type (Unique)
-   load_demand
-   subdivision_id (Foreign Key to Subdivisions)
-   company_id (Foreign Key to Companies)
-   status (Enum: pending, approved, rejected | Default: pending)
-   timestamps()

**Relationships:**

-   BelongsTo: Subdivision
-   BelongsTo: Company
-   HasOne: Meter
-   HasMany: ApplicationHistories
-   HasOne: ApplicationSummary

### 5. Meters Table

**Migration:** `2025_10_21_000007_create_meters_table.php`
**Model:** `App\Models\Meter`

**Columns:**

-   id (Primary Key)
-   meter_no
-   meter_make
-   reading
-   remarks
-   sim_number
-   application_id (Foreign Key to Applications)
-   timestamps()

**Relationships:**

-   BelongsTo: Application

### 6. Application Histories Table

**Migration:** `2025_10_21_000008_create_application_histories_table.php`
**Model:** `App\Models\ApplicationHistory`

**Columns:**

-   id (Primary Key)
-   application_id (Foreign Key to Applications)
-   meter_number
-   name
-   email
-   phone_number
-   subdivision_id (Foreign Key to Subdivisions)
-   company_id (Foreign Key to Companies)
-   action_type (Enum: submitted, verified, approved, rejected)
-   remarks (Nullable)
-   timestamps()

**Relationships:**

-   BelongsTo: Application
-   BelongsTo: Subdivision
-   BelongsTo: Company

### 7. Application Summaries Table

**Migration:** `2025_10_21_000009_create_application_summaries_table.php`
**Model:** `App\Models\ApplicationSummary`

**Columns:**

-   id (Primary Key)
-   application_id (Foreign Key to Applications)
-   total_meters
-   total_load
-   avg_reading
-   remarks (Nullable)
-   timestamps()

**Relationships:**

-   BelongsTo: Application

### 8. Extra Summaries Table

**Migration:** `2025_10_21_000010_create_extra_summaries_table.php`
**Model:** `App\Models\ExtraSummary`

**Columns:**

-   id (Primary Key)
-   company_id (Foreign Key to Companies)
-   subdivision_id (Foreign Key to Subdivisions)
-   total_applications
-   approved_count
-   rejected_count
-   pending_count
-   last_updated
-   timestamps()

**Relationships:**

-   BelongsTo: Company
-   BelongsTo: Subdivision

## Eloquent Relationships Summary

1. **Company → Subdivisions**: `$company->subdivisions()` (OneToMany)
2. **Subdivision → Company**: `$subdivision->company()` (ManyToOne)
3. **Subdivision → Applications**: `$subdivision->applications()` (OneToMany)
4. **Application → Subdivision**: `$application->subdivision()` (ManyToOne)
5. **Application → Company**: `$application->company()` (ManyToOne)
6. **Application → Meter**: `$application->meter()` (OneToOne)
7. **Application → Histories**: `$application->histories()` (OneToMany)
8. **Application → Summary**: `$application->summary()` (OneToOne)
9. **Company → ExtraSummaries**: `$company->extraSummaries()` (OneToMany)
10. **Meter → Application**: `$meter->application()` (ManyToOne)
11. **History → Application**: `$history->application()` (ManyToOne)
12. **History → Subdivision**: `$history->subdivision()` (ManyToOne)
13. **History → Company**: `$history->company()` (ManyToOne)
14. **Summary → Application**: `$summary->application()` (ManyToOne)
15. **ExtraSummary → Company**: `$extraSummary->company()` (ManyToOne)
16. **ExtraSummary → Subdivision**: `$extraSummary->subdivision()` (ManyToOne)

## Key Features

-   All tables use `id` as primary key
-   All tables include `timestamps()`
-   Proper foreign keys with cascading deletes
-   Optimized for Eloquent model relationships
-   Unique constraints where required
-   Enum fields for status management
