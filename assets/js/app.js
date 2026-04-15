document.addEventListener('DOMContentLoaded', () => {
    const quantityInput = document.getElementById('quantityInput');
    const rateInput = document.getElementById('rateInput');
    const totalPreview = document.getElementById('totalPreview');
    const statusPreview = document.getElementById('statusPreview');

    if (!quantityInput || !rateInput || !totalPreview || !statusPreview) {
        return;
    }

    const rate = Number.parseInt(rateInput.value, 10);
    const validRate = Number.isNaN(rate) ? 12 : rate;

    const updatePreview = () => {
        const raw = quantityInput.value.trim();

        if (raw === '') {
            totalPreview.value = '0';
            statusPreview.value = 'No Order';
            return;
        }

        const quantity = Number.parseInt(raw, 10);

        if (Number.isNaN(quantity) || quantity < 0) {
            totalPreview.value = '0';
            statusPreview.value = 'Invalid quantity';
            return;
        }

        totalPreview.value = String(quantity * validRate);
        statusPreview.value = 'Completed';
    };

    quantityInput.addEventListener('input', updatePreview);
    updatePreview();
});
