import pandas as pd

df = pd.read_excel('Technicians.xlsx', header=None)
df_clean = df[df[0].notna() & (df[0] != 'ID')]
df_clean = df_clean[pd.to_numeric(df_clean[0], errors='coerce').notna()]

print('Total technicians:', len(df_clean))
print('\nUnique venues:', df_clean[7].nunique())
print('\nAll venues:')
venues = df_clean[7].dropna().unique()
for i, v in enumerate(venues, 1):
    print(f'{i}. {v}')

print('\n\nVenue counts:')
print(df_clean[7].value_counts())
