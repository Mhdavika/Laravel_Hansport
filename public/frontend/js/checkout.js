document.addEventListener("DOMContentLoaded", function () {
    const paymentRadios = document.querySelectorAll(
        'input[name="payment_method"]'
    );
    const bankOptions = document.getElementById("bank-options");
    const ewalletOptions = document.getElementById("ewallet-options");

    paymentRadios.forEach((radio) => {
        radio.addEventListener("change", function () {
            if (this.value === "transfer") {
                bankOptions.classList.remove("d-none");
                ewalletOptions.classList.add("d-none");
            } else if (this.value === "ewallet") {
                ewalletOptions.classList.remove("d-none");
                bankOptions.classList.add("d-none");
            } else {
                bankOptions.classList.add("d-none");
                ewalletOptions.classList.add("d-none");
            }
        });
    });
});
