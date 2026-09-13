// Tambahkan "kategori" ke dalam daftar kunci yang ingin ditampilkan
const KUNCI_BUKU = ["judul", "pengarang", "tahun", "stok", "kategori"];

function muatDaftarBuku() {
    muatDataTabel("buku.json", KUNCI_BUKU);
}

document.addEventListener("DOMContentLoaded", function () {
    muatDaftarBuku();

    const btnReload = document.getElementById("btn-reload");
    if (btnReload) {
        btnReload.addEventListener("click", muatDaftarBuku);
    }
});