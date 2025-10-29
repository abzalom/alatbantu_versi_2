const timBappedaList = document.getElementById("tim-bappeda-list");
const timOpdList = document.getElementById("tim-opd-list");

function getCurrentOrderBappeda() {
    // li => Number(li.dataset.id)
    // hasil: array id sesuai urutan tampilan sekarang
    const currentOrder = [...timBappedaList.querySelectorAll(".tim-bappeda-item")].map(function (li) {
        return {
            id_tim: Number(li.dataset.tim_id),
            id_opd: Number(li.dataset.opd_id),
        };
    });
    return currentOrder;
}

function getCurrentOrderOpd() {
    const currentOrder = [...timOpdList.querySelectorAll(".tim-opd-item")].map(function (li) {
        return {
            id_tim: Number(li.dataset.tim_id),
            id_opd: Number(li.dataset.opd_id),
        };
    });
    return currentOrder;
}

async function saveOrderBappeda() {
    const order = getCurrentOrderBappeda();

    // set data order ke server
    const newOrder = [];
    order.forEach((item, index) => {
        const urutanSpan = document.querySelector(`.urutan-tim-bappeda-${item.id_tim}`);
        if (urutanSpan) {
            urutanSpan.textContent = `${index + 1}.`;
        }
        newOrder.push({
            urutan: index,
            tim_pembahas_id: item.id_tim,
            opd_id: item.id_opd,
            tahun: tahunAnggaran,
        });
    });
    // kirim ke server
    try {
        const response = await fetch("/api/config/tim_pembahas/bappeda/set/order", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "x-token": userToken,
            },
            body: JSON.stringify({
                data: newOrder,
            }),
        });
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const result = await response.json();
        // showToast('Urutan Tim Pembahas berhasil disimpan.', 'success');
        // console.log('Urutan Tim Pembahas berhasil disimpan:', result);
    } catch (error) {
        console.log(error);
        console.error("Gagal menyimpan urutan Tim Pembahas:", error);
        showToast("Gagal menyimpan urutan Tim Pembahas. Silakan coba lagi.", "danger");
    }
}

async function saveOrderOpd() {
    const order = getCurrentOrderOpd();

    // set data order ke server
    const newOrder = [];
    order.forEach((item, index) => {
        const urutanSpan = document.querySelector(`.urutan-tim-opd-${item.id_tim}`);
        if (urutanSpan) {
            urutanSpan.textContent = `${index + 1}.`;
        }
        newOrder.push({
            urutan: index,
            tim_pembahas_id: item.id_tim,
            opd_id: item.id_opd,
            tahun: tahunAnggaran,
        });
    });
    // kirim ke server
    try {
        const response = await fetch("/api/config/tim_pembahas/opd/set/order", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "x-token": userToken,
            },
            body: JSON.stringify({
                data: newOrder,
            }),
        });
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const result = await response.json();
        // showToast('Urutan Tim Pembahas berhasil disimpan.', 'success');
        // console.log('Urutan Tim Pembahas berhasil disimpan:', result);
    } catch (error) {
        console.log(error);
        console.error("Gagal menyimpan urutan Tim Pembahas:", error);
        showToast("Gagal menyimpan urutan Tim Pembahas. Silakan coba lagi.", "danger");
    }
}

// event yang sudah kamu punya...
if (timBappedaList) {
    timBappedaList.addEventListener("dragstart", (e) => {
        const li = e.target.closest(".tim-bappeda-item");
        if (!li) return;
        e.dataTransfer.effectAllowed = "move";
        try {
            e.dataTransfer.setData("text/plain", "");
        } catch {}
        setTimeout(() => li.classList.add("dragging"), 0);
    });

    timBappedaList.addEventListener("dragend", async (e) => {
        const li = e.target.closest(".tim-bappeda-item");
        if (!li) return;
        li.classList.remove("dragging");
        // <<<<<< simpan setelah drag selesai
        try {
            await saveOrderBappeda();
        } catch (err) {
            console.error(err);
        }
    });

    timBappedaList.addEventListener("dragover", (e) => {
        e.preventDefault();
        const y = e.clientY;
        const siblings = [...timBappedaList.querySelectorAll(".tim-bappeda-item:not(.dragging)")];
        const nextSibling =
            siblings.find((el) => {
                const r = el.getBoundingClientRect();
                return y <= r.top + r.height / 2;
            }) || null;

        const dragging = timBappedaList.querySelector(".tim-bappeda-item.dragging");
        if (!dragging) return;

        nextSibling ? timBappedaList.insertBefore(dragging, nextSibling) : timBappedaList.appendChild(dragging);
    });
}

if (timOpdList) {
    timOpdList.addEventListener("dragstart", (e) => {
        const li = e.target.closest(".tim-opd-item");
        if (!li) return;
        e.dataTransfer.effectAllowed = "move";
        try {
            e.dataTransfer.setData("text/plain", "");
        } catch {}
        setTimeout(() => li.classList.add("dragging"), 0);
    });

    timOpdList.addEventListener("dragend", async (e) => {
        const li = e.target.closest(".tim-opd-item");
        if (!li) return;
        li.classList.remove("dragging");
        // <<<<<< simpan setelah drag selesai
        try {
            await saveOrderOpd();
        } catch (err) {
            console.error(err);
        }
    });

    timOpdList.addEventListener("dragover", (e) => {
        e.preventDefault();
        const y = e.clientY;
        const siblings = [...timOpdList.querySelectorAll(".tim-opd-item:not(.dragging)")];
        const nextSibling =
            siblings.find((el) => {
                const r = el.getBoundingClientRect();
                return y <= r.top + r.height / 2;
            }) || null;

        const dragging = timOpdList.querySelector(".tim-opd-item.dragging");
        if (!dragging) return;

        nextSibling ? timOpdList.insertBefore(dragging, nextSibling) : timOpdList.appendChild(dragging);
    });
}
