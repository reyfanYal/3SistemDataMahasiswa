    </main>
</div>

<!-- GLOBAL SOFT DELETE CONFIRMATION MODAL -->
<div id="deleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-primary/40 backdrop-blur-sm hidden opacity-0 transition-opacity duration-200">
    <div class="bg-surface-container-lowest max-w-md w-full rounded-2xl p-space-lg shadow-2xl border border-outline-variant/30 transform scale-95 transition-transform duration-200" id="deleteModalBox">
        <div class="flex items-center gap-space-md mb-space-md">
            <div class="w-12 h-12 rounded-xl bg-error-container text-error flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[28px]">delete_forever</span>
            </div>
            <div>
                <span class="font-badge-label uppercase tracking-wider text-error font-bold text-[11px]">Konfirmasi Soft Delete</span>
                <h3 class="font-headline-sm text-primary font-bold">Nonaktifkan Mahasiswa?</h3>
            </div>
        </div>

        <p class="font-body-md text-on-surface-variant mb-space-lg leading-relaxed text-sm">
            Data mahasiswa <strong class="text-primary" id="deleteModalMhsName"></strong> (NIM: <span class="font-code-nim text-secondary" id="deleteModalMhsNim"></span>) akan dinonaktifkan (arsip <em class="not-italic font-bold">is_deleted = 1</em>) dan riwayat tindakan akan dicatat di <span class="font-code-nim text-[12px]">audit_logs</span>.
        </p>

        <div class="flex items-center justify-end gap-space-sm pt-space-xs border-t border-outline-variant/20">
            <button type="button" onclick="closeDeleteModal()" class="px-4 py-2.5 rounded-xl text-on-surface-variant font-label-field hover:bg-surface-container transition-colors text-sm">
                Batal
            </button>
            <a id="deleteModalConfirmBtn" href="#" class="px-5 py-2.5 rounded-xl bg-error text-on-error font-label-field font-bold shadow-sm hover:bg-[#93000a] transition-all text-sm inline-flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px]">archive</span>
                Ya, Nonaktifkan
            </a>
        </div>
    </div>
</div>

<script>
    // Global Soft Delete Modal Controller
    function openDeleteModal(id, nama, nim, csrfToken) {
        const modal = document.getElementById('deleteModal');
        const box = document.getElementById('deleteModalBox');
        document.getElementById('deleteModalMhsName').textContent = nama;
        document.getElementById('deleteModalMhsNim').textContent = nim;
        document.getElementById('deleteModalConfirmBtn').href = 'hapus.php?id=' + encodeURIComponent(id) + '&csrf_token=' + encodeURIComponent(csrfToken);

        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            box.classList.remove('scale-95');
            box.classList.add('scale-100');
        }, 10);
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        const box = document.getElementById('deleteModalBox');
        modal.classList.add('opacity-0');
        box.classList.remove('scale-100');
        box.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }

    // Alt + K shortcut to focus search input
    document.addEventListener('keydown', function(e) {
        if ((e.altKey || e.metaKey) && e.key.toLowerCase() === 'k') {
            e.preventDefault();
            const s = document.getElementById('header-search-input') || document.getElementById('table-search-input');
            if (s) {
                s.focus();
                s.select();
            }
        }
    });
</script>
</body>
</html>

