# Seeded Users Reference

## Password for All Users
**Password:** `kayeera`

## Admin Users (5)
- admin1@creec.com
- admin2@creec.com
- admin3@creec.com
- admin4@creec.com
- admin5@creec.com

## Trainer Users (15)
- trainer1@creec.com through trainer15@creec.com
- All have complete profiles with:
  - Specialty (E-Cooking Training, Safety Training, etc.)
  - Phone numbers
  - Experience (1-15 years)
  - Qualifications
  - Location (Various cities in Uganda)
  - Profile images

## Technician Users (15)
- technician1@creec.com through technician15@creec.com
- All have complete profiles with:
  - Specialty (Equipment Repair, Electrical Systems, etc.)
  - Phone numbers
  - License numbers (TECH-0001 to TECH-0015)
  - Experience (1-20 years)
  - Hourly rates ($30-$100)
  - Skills arrays
  - Certifications arrays
  - Location (Various cities in Uganda)
  - Profile images

## Running the Seeder

```bash
# Fresh migration and seed
php artisan migrate:fresh --seed

# Or run specific seeder
php artisan db:seed --class=ComprehensiveUserSeeder
```

## Total Users Created
- **5 Admins**
- **15 Trainers** (with linked trainer profiles)
- **15 Technicians** (with linked technician profiles)
- **Total: 35 users**
