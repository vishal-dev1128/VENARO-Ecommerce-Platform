    </div>
    <!-- End Main Content -->

    <!-- Footer -->
    <footer class="bg-white border-top mt-5 py-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0 text-muted small">© <?php echo date('Y'); ?> VÉNARO. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0 text-muted small">Version 1.0</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS (Deferred for Performance) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>

    <!-- Global Confirmation Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; background: #ffffff;">
                <div class="modal-body p-4 text-center">
                    <div class="mb-3">
                        <i class="material-icons text-danger" style="font-size: 64px; opacity: 0.2;">help_outline</i>
                    </div>
                    <h5 class="modal-title mb-2" id="confirmModalLabel" style="font-weight: 700; color: #1a1a1a;">Confirm Action</h5>
                    <p class="text-muted mb-4" id="confirmModalMessage">Are you sure you want to proceed with this action?</p>
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 10px; font-weight: 600;">Cancel</button>
                        <button type="button" class="btn btn-danger px-4" id="confirmModalBtn" style="border-radius: 10px; font-weight: 600;">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Replacement for native alert() -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999;">
        <div id="adminToast" class="toast hide border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true" style="border-radius: 12px; background: #ffffff;">
            <div class="d-flex p-3">
                <div class="toast-body d-flex align-items-center gap-2" id="adminToastMessage" style="font-weight: 500; color: #1a1a1a;">
                    <!-- Message content will be injected here -->
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script>
        /**
         * Global confirmation handler to replace window.confirm
         */
        const venaroConfirm = (message, onConfirm, options = {}) => {
            const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
            document.getElementById('confirmModalMessage').textContent = message;
            document.getElementById('confirmModalLabel').textContent = options.title || 'Confirm Action';

            const confirmBtn = document.getElementById('confirmModalBtn');
            confirmBtn.textContent = options.confirmText || 'Confirm';
            confirmBtn.className = `btn btn-${options.confirmClass || 'danger'} px-4`;

            // Remove previous event listeners
            const newConfirmBtn = confirmBtn.cloneNode(true);
            confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);

            newConfirmBtn.addEventListener('click', () => {
                onConfirm();
                modal.hide();
            });

            modal.show();
        };

        /**
         * Global toast handler to replace window.alert
         */
        const venaroAlert = (message, type = 'info') => {
            const toastEl = document.getElementById('adminToast');
            const toastMessage = document.getElementById('adminToastMessage');
            const toast = new bootstrap.Toast(toastEl, {
                delay: 4000
            });

            const icon = type === 'success' ? 'check_circle' : (type === 'danger' ? 'error' : 'info');
            const iconColor = type === 'success' ? '#28a745' : (type === 'danger' ? '#dc3545' : '#1a73e8');

            toastMessage.innerHTML = `<i class="material-icons" style="color: ${iconColor};">${icon}</i> ${message}`;
            toast.show();
        };

        // Expose to window for global access
        window.venaroConfirm = venaroConfirm;
        window.venaroAlert = venaroAlert;
    </script>
    </body>

    </html>