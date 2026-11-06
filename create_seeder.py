import openpyxl
import os
import re

file_path = "Asistensi Kepegawaian 2026.xlsx"
wb = openpyxl.load_workbook(file_path, data_only=True)
sheet = wb.active

headers = []
rows = []
for r, row in enumerate(sheet.iter_rows(values_only=True)):
    if r == 0:
        headers = [str(cell).strip() if cell else f"col_{c}" for c, cell in enumerate(row)]
        continue
    if any(row):
        row_dict = {}
        for c, cell in enumerate(row):
            if c < len(headers):
                row_dict[headers[c]] = cell
        rows.append(row_dict)

kecamatans = [
    'Tuban', 'Semanding', 'Jenu', 'Merakurak', 'Palang', 'Rengel', 'Plumpang', 
    'Soko', 'Grabagan', 'Kerek', 'Montong', 'Singgahan', 'Senori', 'Bangilan', 
    'Jatirogo', 'Kenduruan', 'Bancar', 'Tambakboyo', 'Widang', 'Parengan'
]

# Mapping untuk data kecamatan dan sekolah agar kita bisa insert dulu
kecamatan_data = set()
sekolah_data = set() # tuple of (kecamatan, nama, jenjang)
layanan_records = []

for row in rows:
    timestamp = str(row.get("Timestamp", "2026-01-27 00:00:00"))
    if " " in timestamp:
        t_parts = timestamp.split(" ")
        tanggal = t_parts[0]
        jam = t_parts[1].split(".")[0]
    else:
        tanggal = "2026-01-27"
        jam = "00:00:00"
        
    nama_asn = str(row.get("NAMA LENGKAP", "ASN")).strip()
    nip = re.sub(r'\D+', '', str(row.get("NIP / NIPPPK / NIK", "199001012020121001")))
    no_hp = re.sub(r'(?!^)\D+', '', str(row.get("NO. WHATSAPP", "08123456789")))
    if not no_hp.startswith("0") and not no_hp.startswith("+"):
        no_hp = "0" + no_hp
    email = f"{nama_asn.lower().replace(' ', '').replace('.', '')[:10]}@pena.test"
    
    instansi_raw = str(row.get("Asal Instansi / Unit Kerja", "")).strip()
    
    # Deteksi kecamatan
    kecamatan_found = None
    for kec in kecamatans:
        if re.search(r'\b' + re.escape(kec) + r'\b', instansi_raw, re.IGNORECASE):
            kecamatan_found = kec
            break
            
    # Deteksi apakah sekolah
    is_sekolah = False
    jenjang = None
    nama_sekolah = None
    unit_kerja = None
    
    instansi_lower = instansi_raw.lower()
    if "sdn" in instansi_lower or "sd neger" in instansi_lower or "sd " in instansi_lower:
        is_sekolah = True
        jenjang = "SD sederajat"
    elif "smp" in instansi_lower or "smpn" in instansi_lower:
        is_sekolah = True
        jenjang = "SMP sederajat"
    elif "tk " in instansi_lower or "taman kanak" in instansi_lower:
        is_sekolah = True
        jenjang = "TK sederajat"
    elif "kb " in instansi_lower or "kelompok bermain" in instansi_lower:
        is_sekolah = True
        jenjang = "KB sederajat"
        
    if is_sekolah:
        # Bersihkan nama sekolah, hilangkan kata "Kec. Tuban" dsb di belakangnya
        clean_nama = instansi_raw
        # Cari pola Kec. xxx
        clean_nama = re.sub(r'\s+Kec\.\s+\w+', '', clean_nama, flags=re.IGNORECASE)
        clean_nama = re.sub(r'\s+Kecamatan\s+\w+', '', clean_nama, flags=re.IGNORECASE)
        # Hilangkan akhiran nama kecamatan seperti "Tuban", "Parengan" di ujung jika ada
        for kec in kecamatans:
            clean_nama = re.sub(r'\s+-\s+' + re.escape(kec) + r'$', '', clean_nama, flags=re.IGNORECASE)
            clean_nama = re.sub(r'\s+' + re.escape(kec) + r'$', '', clean_nama, flags=re.IGNORECASE)
        
        nama_sekolah = clean_nama.strip()
        if not kecamatan_found:
            kecamatan_found = "Tuban" # default
    else:
        unit_kerja = instansi_raw
        
    if kecamatan_found:
        kecamatan_data.add(kecamatan_found)
        if is_sekolah and nama_sekolah:
            sekolah_data.add((kecamatan_found, nama_sekolah, jenjang))
            
    # Deteksi layanan & sub_layanan
    hal = str(row.get("Hal yang dikonsultasikan", "Layanan SIASN")).strip()
    if "sijempol" in hal.lower():
        layanan = "Layanan SIJEMPOL"
        sub_layanan = str(row.get("Layanan SIJEMPOL  yang akan dikonsultasikan", "Tata cara unggah dokumen cuti / Dispensasi")).strip()
    elif "siteman" in hal.lower():
        layanan = "Layanan SITEMAN"
        sub_layanan = str(row.get("Layanan SITEMAN yang akan dikonsultasikan", "Lainnya")).strip()
    else:
        layanan = "Layanan SIASN"
        sub_layanan = str(row.get("Layanan SIASN yang akan dikonsultasikan", "Reset Password SIASN")).strip()
        
    if not sub_layanan or sub_layanan == "None":
        sub_layanan = "Lainnya"
        
    permasalahan = f"Konsultasi mengenai {sub_layanan} pada {layanan}."
    tindak_lanjut = str(row.get("TINDAK LANJUT", f"Dilakukan pendampingan penyelesaian kendala {sub_layanan}. Perbaikan selesai.")).strip()
    
    layanan_records.append({
        'tanggal': tanggal,
        'jam': jam,
        'nama_asn': nama_asn,
        'nip': nip,
        'no_hp': no_hp,
        'email': email,
        'kecamatan': kecamatan_found,
        'nama_sekolah': nama_sekolah if is_sekolah else None,
        'jenjang': jenjang,
        'unit_kerja': unit_kerja,
        'layanan': layanan,
        'sub_layanan': sub_layanan,
        'permasalahan': permasalahan,
        'tindak_lanjut': tindak_lanjut,
        'hasil_layanan': 'Selesai'
    })

# Tulis file seeder
seeder_content = """<?php

namespace Database\\Seeders;

use Illuminate\\Database\\Seeder;
use App\\Models\\Kecamatan;
use App\\Models\\Sekolah;
use App\\Models\\LayananKepegawaian;
use App\\Models\\User;
use Illuminate\\Support\\Carbon;
use Illuminate\\Support\\Facades\\DB;

class LayananExcelSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Pastikan user admin utama ada
        $admin = User::firstOrCreate(
            ['email' => 'asdmaparatur@gmail.com'],
            [
                'name' => 'Administrator PENA',
                'password' => 'Asdmaparatur123!',
                'password_changed_at' => now(),
                'must_change_password' => false,
                'role' => 'admin',
            ]
        );

        // 2. Buat Kecamatan
        $kecMap = [];
"""

for kec in sorted(list(kecamatan_data)):
    seeder_content += f"        $kecMap['{kec}'] = Kecamatan::firstOrCreate(['nama' => '{kec}']);\n"

seeder_content += "\n        // 3. Buat Sekolah\n        $sekMap = [];\n"
for kec, nama, jenjang in sorted(list(sekolah_data)):
    # escape single quotes
    nama_esc = nama.replace("'", "\\'")
    seeder_content += f"        if (isset($kecMap['{kec}'])) {{\n"
    seeder_content += f"            $sekMap['{nama_esc}'] = Sekolah::firstOrCreate([\n"
    seeder_content += f"                'kecamatan_id' => $kecMap['{kec}']->id,\n"
    seeder_content += f"                'nama' => '{nama_esc}',\n"
    seeder_content += f"                'jenjang' => '{jenjang}',\n"
    seeder_content += "            ]);\n"
    seeder_content += "        }\n"

seeder_content += "\n        // 4. Buat Record Layanan Kepegawaian\n"
seeder_content += "        $services = [\n"

for i, rec in enumerate(layanan_records):
    nama_asn = rec['nama_asn'].replace("'", "\\'")
    unit_kerja = rec['unit_kerja'].replace("'", "\\'") if rec['unit_kerja'] else 'null'
    if unit_kerja != 'null':
        unit_kerja = f"'{unit_kerja}'"
    nama_sekolah = rec['nama_sekolah'].replace("'", "\\'") if rec['nama_sekolah'] else 'null'
    if nama_sekolah != 'null':
        nama_sekolah = f"'{nama_sekolah}'"
    sub_layanan = rec['sub_layanan'].replace("'", "\\'")
    permasalahan = rec['permasalahan'].replace("'", "\\'")
    tindak_lanjut = rec['tindak_lanjut'].replace("'", "\\'")
    
    # Generate nomor_layanan unik: PENA-YYYYMMDD-0000X
    tgl_clean = rec['tanggal'].replace("-", "")
    nomor_layanan = f"PENA-{tgl_clean}-{str(i+1).zfill(5)}"
    
    seeder_content += "            [\n"
    seeder_content += f"                'nomor_layanan' => '{nomor_layanan}',\n"
    seeder_content += f"                'tanggal' => '{rec['tanggal']}',\n"
    seeder_content += f"                'jam' => '{rec['jam']}',\n"
    seeder_content += f"                'nama_asn' => '{nama_asn}',\n"
    seeder_content += f"                'nip' => '{rec['nip']}',\n"
    seeder_content += f"                'no_hp' => '{rec['no_hp']}',\n"
    seeder_content += f"                'email' => '{rec['email']}',\n"
    seeder_content += f"                'kecamatan_name' => '{rec['kecamatan']}',\n"
    seeder_content += f"                'sekolah_name' => {nama_sekolah},\n"
    seeder_content += f"                'jenjang' => '{rec['jenjang'] or ''}',\n"
    seeder_content += f"                'unit_kerja' => {unit_kerja},\n"
    seeder_content += f"                'layanan' => '{rec['layanan']}',\n"
    seeder_content += f"                'sub_layanan' => '{sub_layanan}',\n"
    seeder_content += f"                'permasalahan' => '{permasalahan}',\n"
    seeder_content += f"                'tindak_lanjut' => '{tindak_lanjut}',\n"
    seeder_content += f"                'hasil_layanan' => 'Selesai',\n"
    seeder_content += "            ],\n"

seeder_content += """        ];

        foreach ($services as $srv) {
            $kecId = isset($kecMap[$srv['kecamatan_name']]) ? $kecMap[$srv['kecamatan_name']]->id : null;
            $sekId = isset($sekMap[$srv['sekolah_name']]) ? $sekMap[$srv['sekolah_name']]->id : null;

            LayananKepegawaian::firstOrCreate(
                ['nomor_layanan' => $srv['nomor_layanan']],
                [
                    'tanggal' => $srv['tanggal'],
                    'jam' => $srv['jam'],
                    'nama_asn' => $srv['nama_asn'],
                    'nip' => $srv['nip'],
                    'no_hp' => $srv['no_hp'],
                    'email' => $srv['email'],
                    'kecamatan_id' => $kecId,
                    'sekolah_id' => $sekId,
                    'kecamatan' => $srv['kecamatan_name'],
                    'jenjang' => $srv['jenjang'] ?: null,
                    'nama_sekolah' => $srv['sekolah_name'],
                    'unit_kerja' => $srv['unit_kerja'],
                    'layanan' => $srv['layanan'],
                    'sub_layanan' => $srv['sub_layanan'],
                    'permasalahan' => $srv['permasalahan'],
                    'tindak_lanjut' => $srv['tindak_lanjut'],
                    'hasil_layanan' => $srv['hasil_layanan'],
                    'created_by' => $admin->id,
                ]
            );
        }
    }
}
"""

with open("database/seeders/LayananExcelSeeder.php", "w", encoding="utf-8") as f:
    f.write(seeder_content)
print("Seeder created successfully!")
