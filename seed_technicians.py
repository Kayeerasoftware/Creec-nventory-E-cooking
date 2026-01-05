import openpyxl
import mysql.connector
import re
from datetime import datetime

# Load Excel file
wb = openpyxl.load_workbook('Technicians.xlsx')
ws = wb.active

# Database connection
conn = mysql.connector.connect(
    host='127.0.0.1',
    user='root',
    password='',
    database='spare_parts_inventory'
)
cursor = conn.cursor()

current_venue = ''
current_training_dates = ''
cohort_number = 0

for row_idx, row in enumerate(ws.iter_rows(min_row=2, values_only=True), start=2):
    # Check for training dates
    if row[0] and 'training dates' in str(row[0]).lower():
        current_training_dates = str(row[0])
        cohort_number += 1
        continue
    
    # Check for venue
    if row[7] and 'venue' in str(row[7]).lower():
        current_venue = str(row[7]).replace('Venue:', '').replace('Venue', '').strip()
        continue
    
    # Skip header and empty rows
    if row[0] == 'ID' or not row[1]:
        continue
    
    name = str(row[1]).strip()
    if not name:
        continue
    
    # Generate email from name and cohort
    name_parts = name.lower().split()
    email_name = ''.join(name_parts)
    email = f"{email_name}{cohort_number}@creec.com"
    
    # Format phone number
    phone = str(row[5]) if row[5] else ''
    phone = re.sub(r'[^0-9]', '', phone)
    if phone.startswith('0'):
        phone = '+256' + phone[1:]
    elif not phone.startswith('+'):
        phone = '+256' + phone
    
    # Insert into database
    sql = """INSERT INTO technicians 
             (name, email, phone, place_of_work, gender, age, venue, training_dates, 
              cohort_number, specialty, license, location, created_at, updated_at) 
             VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"""
    
    values = (
        name,
        email,
        phone,
        row[4] if row[4] else None,
        row[2] if row[2] else None,
        row[3] if row[3] else None,
        current_venue if current_venue else None,
        current_training_dates if current_training_dates else None,
        cohort_number,
        'E-cooking',
        'N/A',
        current_venue if current_venue else 'Uganda',
        datetime.now(),
        datetime.now()
    )
    
    try:
        cursor.execute(sql, values)
        print(f"Inserted: {name} - {email}")
    except Exception as e:
        print(f"Error inserting {name}: {e}")

conn.commit()
cursor.close()
conn.close()

print(f"\nSeeding completed! Total cohorts: {cohort_number}")
