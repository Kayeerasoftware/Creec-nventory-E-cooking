import pandas as pd
import json

# Read the Excel file
df = pd.read_excel('inventory_records_detailed_updated.xls')

# Display basic info
print("Column names:")
print(df.columns.tolist())
print("\nFirst few rows:")
print(df.head())
print("\nData types:")
print(df.dtypes)
print("\nShape:", df.shape)
print("\nSample data:")
print(df.head(10).to_dict('records'))
