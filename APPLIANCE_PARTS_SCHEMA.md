# Appliance and Spare Parts Database Schema

## Tables and Fields

### 1. **brands**
- `id` - Primary key
- `name` - Brand name
- `created_at` - Timestamp
- `updated_at` - Timestamp

### 2. **appliances**
- `id` - Primary key
- `name` - Appliance name
- `icon` - Icon reference
- `model` - Model number
- `power` - Power specification
- `sku` - Stock keeping unit
- `status` - Status (active/inactive)
- `brand_id` - Foreign key to brands
- `description` - Appliance description
- `price` - Appliance price
- `color` - Appliance color
- `created_at` - Timestamp
- `updated_at` - Timestamp

### 3. **specific_appliances**
- `id` - Primary key
- `name` - Specific appliance name
- `appliance_id` - Foreign key to appliances
- `brand_id` - Foreign key to brands
- `created_at` - Timestamp
- `updated_at` - Timestamp

### 4. **parts** (Spare Parts)
- `id` - Primary key
- `part_number` - Unique part number
- `name` - Part name
- `appliance_id` - Foreign key to appliances
- `location` - Storage location
- `description` - Part description
- `availability` - Boolean (in stock/out of stock)
- `comments` - Additional comments
- `image_path` - Part image path
- `price` - Part price
- `created_at` - Timestamp
- `updated_at` - Timestamp

### 5. **part_brands** (Many-to-Many: Parts ↔ Brands)
- `id` - Primary key
- `part_id` - Foreign key to parts
- `brand_id` - Foreign key to brands
- `created_at` - Timestamp
- `updated_at` - Timestamp

### 6. **part_specific_appliances** (Many-to-Many: Parts ↔ Specific Appliances)
- `id` - Primary key
- `part_id` - Foreign key to parts
- `specific_appliance_id` - Foreign key to specific_appliances
- `created_at` - Timestamp
- `updated_at` - Timestamp

## Relationships

```
brands
  ├─→ appliances (one-to-many)
  ├─→ specific_appliances (one-to-many)
  └─→ parts (many-to-many via part_brands)

appliances
  ├─→ specific_appliances (one-to-many)
  └─→ parts (one-to-many)

specific_appliances
  └─→ parts (many-to-many via part_specific_appliances)

parts
  ├─→ brands (many-to-many via part_brands)
  └─→ specific_appliances (many-to-many via part_specific_appliances)
```

## Summary
- **6 tables** total
- **3 main entity tables**: brands, appliances, parts
- **2 specific/variant tables**: specific_appliances
- **2 pivot tables**: part_brands, part_specific_appliances
- All tables have timestamps (created_at, updated_at)
- Foreign keys with cascade delete for data integrity
