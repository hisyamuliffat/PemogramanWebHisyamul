
async function muatDataTabel(jsonFile, keys) {
    const tbody = document.querySelector(".table-responsive table tbody");
    const loading = document.getElementById("loading-indicator");
    const searchInput = document.getElementById("search-input");
    if (!tbody) return;

    if (searchInput) searchInput.value = "";
    if (loading) loading.style.display = "block";
    tbody.innerHTML = "";

    try {
        await new Promise((resolve) => setTimeout(resolve, 3000));

        const res = await fetch(`../data/${jsonFile}`);
        if (!res.ok) {
            throw new Error("Gagal mengambil data (status " + res.status + ")");
        }
        const daftarData = await res.json();

        daftarData.forEach(function (item) {
            const tr = document.createElement("tr");

            let cellsHTML = keys.map(key => `<td>${item[key] !== undefined ? item[key] : ''}</td>`).join("");

            cellsHTML += `
                <td>
                    <button type="button">Edit</button> 
                    <button type="button" class="btn-hapus">Hapus</button>
                </td>
            `;

            tr.innerHTML = cellsHTML;
            tbody.appendChild(tr);
        });

        if (typeof updateRowCounter === "function") {
            updateRowCounter();
        }
    } catch (err) {
        const totalColspan = keys.length + 1;
        tbody.innerHTML = `<tr><td colspan="${totalColspan}">Gagal memuat data: ${err.message}</td></tr>`;
    } finally {
        if (loading) loading.style.display = "none";
    }
}


function initHapusConfirm() {
    document.addEventListener("click", function (e) {
        // Log elemen yang diklik ke Console
        console.log("Elemen yang diklik (e.target):", e.target);

        // Saring hanya tombol dengan class .btn-hapus
        const btnHapus = e.target.closest(".btn-hapus");
        if (!btnHapus) return;

        // Ambil baris tabel (tr) tempat tombol hapus berada
        const tr = btnHapus.closest("tr");
        const judulBuku = tr ? tr.children[0].textContent : "data ini";

        const konfirmasi = confirm(`Apakah Anda yakin ingin menghapus "${judulBuku}"?`);
        if (konfirmasi) {
            tr.remove();
            if (typeof updateRowCounter === "function") {
                updateRowCounter();
            }
        }
    });
}

// 3. Jalankan Event Listener saat Halaman Selesai Dimuat
document.addEventListener("DOMContentLoaded", function () {
    initHapusConfirm();
});