function increaseQty(id) {
    var input = document.getElementById("qtyInput" + id);
    input.value = parseInt(input.value) + 1;
}

function decreaseQty(id) {
    var input = document.getElementById("qtyInput" + id);
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
    }
}
$(document).ready(function () {
    $(document).on("click", ".btn-add-to-cart", function (e) {
        const stock = parseInt($(this).data("stock"));
        const product = $(this).data("product");

        if (stock <= 0) {
            e.preventDefault(); // cegah buka modal
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Stok produk "' + product + '" habis!',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        }
    });
});
