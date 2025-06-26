#!/bin/bash

# Script untuk mengimpor data obat dari SatuSehat API secara otomatis
# 
# Cara penggunaan:
# ./import_medications.sh [jumlah] [kategori1,kategori2,...]
# 
# Contoh:
# ./import_medications.sh 100 antibiotik,vitamin

# Pindah ke direktori root project
cd "$(dirname "$0")/.." || exit 1

COUNT=${1:-100}
CATEGORIES=$2

if [ -z "$CATEGORIES" ]; then
    php artisan satusehat:import-medications "$COUNT"
else
    # Konversi daftar kategori menjadi parameter --category
    CATEGORY_PARAMS=""
    IFS=',' read -ra CATEGORY_ARRAY <<< "$CATEGORIES"
    for category in "${CATEGORY_ARRAY[@]}"; do
        CATEGORY_PARAMS="$CATEGORY_PARAMS --category=$category"
    done
    
    php artisan satusehat:import-medications "$COUNT" $CATEGORY_PARAMS
fi

echo ""
echo "Import selesai."
echo ""