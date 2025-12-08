document.addEventListener("DOMContentLoaded", function () {
    const successMeta = document.querySelector('meta[name="flash-success"]');
    const errorMeta = document.querySelector('meta[name="flash-error"]');

    const success = successMeta?.content;
    const error = errorMeta?.content;

    if (success) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: success,
            timer: 2000,
            showConfirmButton: false
        });
        successMeta.remove(); // Hapus agar tidak muncul ulang saat Back
    }

    if (error) {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: error,
            timer: 3000,
            showConfirmButton: false
        });
        errorMeta.remove(); // Hapus agar tidak muncul ulang saat Back
    }
});
