import openpyxl
import os

file_path = "Asistensi Kepegawaian 2026.xlsx"
wb = openpyxl.load_workbook(file_path, data_only=True)
sheet = wb.active

hal_konsul = set()
layanan_siasn = set()
layanan_sijempol = set()
layanan_siteman = set()
instansi = set()

for r, row in enumerate(sheet.iter_rows(values_only=True)):
    if r == 0:
        headers = [str(cell).strip() if cell else f"col_{c}" for c, cell in enumerate(row)]
        continue
    if any(row):
        row_dict = {}
        for c, cell in enumerate(row):
            if c < len(headers):
                row_dict[headers[c]] = cell
        
        hal = row_dict.get("Hal yang dikonsultasikan")
        if hal: hal_konsul.add(hal)
        
        siasn = row_dict.get("Layanan SIASN yang akan dikonsultasikan")
        if siasn: layanan_siasn.add(siasn)
        
        sijempol = row_dict.get("Layanan SIJEMPOL  yang akan dikonsultasikan")
        if sijempol: layanan_sijempol.add(sijempol)
        
        siteman = row_dict.get("Layanan SITEMAN yang akan dikonsultasikan")
        if siteman: layanan_siteman.add(siteman)
        
        ins = row_dict.get("Asal Instansi / Unit Kerja")
        if ins: instansi.add(ins)

print("=== Hal yang dikonsultasikan ===")
for x in hal_konsul: print(f"- {x}")
print("\n=== Layanan SIASN ===")
for x in layanan_siasn: print(f"- {x}")
print("\n=== Layanan SIJEMPOL ===")
for x in layanan_sijempol: print(f"- {x}")
print("\n=== Layanan SITEMAN ===")
for x in layanan_siteman: print(f"- {x}")
print("\n=== Contoh Instansi (10) ===")
for x in list(instansi)[:10]: print(f"- {x}")
