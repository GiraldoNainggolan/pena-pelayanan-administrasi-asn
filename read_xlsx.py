import openpyxl
import json
import os

file_path = "Asistensi Kepegawaian 2026.xlsx"
if not os.path.exists(file_path):
    print("File not found")
    exit()

wb = openpyxl.load_workbook(file_path, data_only=True)
sheet = wb.active

data = []
headers = []
for r, row in enumerate(sheet.iter_rows(values_only=True)):
    if r == 0:
        headers = [str(cell).strip() if cell else f"col_{c}" for c, cell in enumerate(row)]
        continue
    if any(row):
        row_dict = {}
        for c, cell in enumerate(row):
            if c < len(headers):
                row_dict[headers[c]] = cell
        data.append(row_dict)

print(json.dumps(data[:5], indent=2, default=str))
print(f"Total rows: {len(data)}")
