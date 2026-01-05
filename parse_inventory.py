import pandas as pd
import json

df = pd.read_excel('inventory_records_detailed_updated.xls')

records = []
for _, row in df.iterrows():
    record = {
        'part_number': str(row['Part Number']),
        'name': str(row['Spare Part Name']),
        'appliance_type': str(row['Appliance Type specific for the part']),
        'location': str(row['Part Location']),
        'brands': str(row['Compatible Brands']).split('|') if pd.notna(row['Compatible Brands']) else [],
        'appliances': str(row['Compatible Appliances ']).split('|') if pd.notna(row['Compatible Appliances ']) else [],
        'description': str(row['Description']) if pd.notna(row['Description']) else '',
        'comments': str(row['Comments']) if pd.notna(row['Comments']) else ''
    }
    records.append(record)

with open('inventory_data.json', 'w') as f:
    json.dump(records, f, indent=2)

print(f"Parsed {len(records)} records")
