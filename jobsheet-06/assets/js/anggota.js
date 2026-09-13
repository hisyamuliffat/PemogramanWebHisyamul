const KUNCI_ANGGOTA = ["no_anggota", "nama", "alamat", "no_hp"];

function muatDaftarAnggota() {
    muatDataTabel("anggota.json", KUNCI_ANGGOTA);
}

document.addEventListener("DOMContentLoaded", function () {
    muatDaftarAnggota();

    const btnReload = document.getElementById("btn-reload");
    if (btnReload) {
        btnReload.addEventListener("click", muatDaftarAnggota);
    }
});