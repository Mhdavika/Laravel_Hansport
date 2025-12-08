// Timer Countdown untuk Deal of the Week
document.addEventListener("DOMContentLoaded", function () {
    const timer = document.querySelector(".timer");
    if (!timer) return;

    const endTime = new Date(timer.dataset.deadline).getTime();

    function updateCountdown() {
        const now = new Date().getTime();
        const distance = endTime - now;

        if (distance < 0) {
            timer.closest(".deal_ofthe_week").style.display = "none";
            return;
        }

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        timer.querySelector("#days").innerText = String(days).padStart(2, "0");
        timer.querySelector("#hours").innerText = String(hours).padStart(2, "0");
        timer.querySelector("#minutes").innerText = String(minutes).padStart(2, "0");
        timer.querySelector("#seconds").innerText = String(seconds).padStart(2, "0");
    }

    updateCountdown();
    setInterval(updateCountdown, 1000);
});

// Inisialisasi tombol + dan - jumlah produk
$(document).ready(function () {
    $(document).on("click", ".btn-plus", function () {
        const id = $(this).data("id");
        const input = $("#qtyInput" + id);
        const max = parseInt(input.data("max")) || 1;
        let current = parseInt(input.val()) || 1;

        if (current < max) {
            input.val(current + 1);
        }
    });

    $(document).on("click", ".btn-minus", function () {
        const id = $(this).data("id");
        const input = $("#qtyInput" + id);
        let current = parseInt(input.val()) || 1;

        if (current > 1) {
            input.val(current - 1);
        }
    });

    // Cek stok sebelum buka modal
    $(document).on("click", ".btn-add-to-cart", function (e) {
        const stock = parseInt($(this).data("stock"));
        const productName = $(this).data("product") || "Produk ini";

        if (stock <= 0) {
            e.preventDefault();
            Swal.fire({
                toast: true,
                position: "top-end",
                icon: "error",
                title: productName + " sedang habis!",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        }
    });
});
