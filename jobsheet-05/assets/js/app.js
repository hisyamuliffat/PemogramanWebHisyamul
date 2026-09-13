// ===== Hamburger menu (JS-driven, menggantikan checkbox hack) =====
function initNavToggle() {
    const toggleBtn = document.getElementById("nav-toggle-btn");
    const nav = document.querySelector("header nav");
    if (!toggleBtn || !nav) return;

    toggleBtn.addEventListener("click", function () {
        nav.classList.toggle("nav-open");
    });
}

// ===== Konfirmasi hapus (front-end only, belum ke server) =====
function initHapusConfirm() {
    const deleteButtons = document.querySelectorAll('.btn-hapus');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const row = this.closest('tr');
            const judulBuku = row ? row.cells[0].textContent : 'buku ini';

            if (confirm(`Apakah Anda yakin ingin menghapus "${judulBuku}"?`)) {
                row.remove();
                // Perbarui counter setiap kali baris berhasil dihapus
                updateRowCounter();
            }
        });
    });
}

// Jalankan fungsi saat DOM siap
document.addEventListener('DOMContentLoaded', () => {
    initTableFilter();
    initHapusConfirm();
});
// ===== Filter/pencarian tabel real-time =====
function initTableFilter() {
    const searchInput = document.getElementById('search-input');
    const filterColumn = document.getElementById('filter-column');
    const tableBody = document.querySelector('tbody');

    if (!searchInput || !tableBody) return;

   function filterTable() {
        const query = searchInput.value.toLowerCase().trim();
        const selectedColumn = filterColumn ? filterColumn.value : 'all';
        const rows = tableBody.querySelectorAll('tr');

        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length === 0) return;

            let isMatch = false;

            if (selectedColumn === 'all') {
                isMatch = row.textContent.toLowerCase().includes(query);
            } else {
                const colIndex = parseInt(selectedColumn, 10);
                if (cells[colIndex]) {
                    const cellText = cells[colIndex].textContent.toLowerCase();
                    isMatch = cellText.includes(query);
                }
            }

            row.style.display = isMatch ? '' : 'none';
        });

        // Perbarui counter setiap kali selesai memfilter
        updateRowCounter();
    }

    searchInput.addEventListener('input', filterTable);
    if (filterColumn) {
        filterColumn.addEventListener('change', filterTable);
    }

    // Jalankan pertama kali saat halaman dimuat
    updateRowCounter();
}

    // Jalankan filter saat user mengetik
    searchInput.addEventListener('input', filterTable);

    // Jalankan filter saat user mengubah pilihan kolom
    if (filterColumn) {
        filterColumn.addEventListener('change', filterTable);
    }


// ===== Validasi form (client-side) =====
function tampilkanError(input, pesan) {
    hapusError(input);
    const span = document.createElement("span");
    span.className = "error";
    span.textContent = pesan;
    input.insertAdjacentElement("afterend", span);
}

function hapusError(input) {
    const next = input.nextElementSibling;
    if (next && next.classList.contains("error")) {
        next.remove();
    }
}

function initValidasiForm() {
    const form = document.getElementById('form-tambah');
    if (!form) return;

 
    const fieldsToValidate = [
        { id: 'judul', name: 'Judul Buku' },
        { id: 'pengarang', name: 'Nama Pengarang' },
        { id: 'tahun', name: 'Tahun Terbit' },
        { id: 'stok', name: 'Jumlah Stok' }
    ];

    form.addEventListener('submit', function (e) {
        let isValid = true;

       
        form.querySelectorAll('.error').forEach(el => el.remove());

      
        fieldsToValidate.forEach(field => {
            const input = document.getElementById(field.id);
            if (!input) return;

            const value = input.value.trim();

       
            if (value === '') {
                showError(input, `${field.name} tidak boleh kosong.`);
                isValid = false;
            }
        });

       
        if (!isValid) {
            e.preventDefault();
        }
    });

    // Helper function untuk menampilkan pesan kesalahan di bawah input
    function showError(inputElement, message) {
        const errorSpan = document.createElement('span');
        errorSpan.className = 'error';
        errorSpan.textContent = message;
        inputElement.parentNode.appendChild(errorSpan);
    }
}
function updateRowCounter() {
    const tableBody = document.querySelector('tbody');
    const rowCounter = document.getElementById('row-counter');

    if (!tableBody || !rowCounter) return;

    const allRows = tableBody.querySelectorAll('tr');
    let visibleRowsCount = 0;

    allRows.forEach(row => {
        // Baris dihitung jika tidak disembunyikan (display bukan 'none')
        if (row.style.display !== 'none') {
            visibleRowsCount++;
        }
    });

    // Tampilkan informasi "Menampilkan X dari Y buku"
    rowCounter.textContent = `Menampilkan ${visibleRowsCount} dari ${allRows.length} buku`;
}

document.addEventListener("DOMContentLoaded", function () {
    initNavToggle();
    initHapusConfirm();
    initTableFilter();
    initValidasiForm();
});
