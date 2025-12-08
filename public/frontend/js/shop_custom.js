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