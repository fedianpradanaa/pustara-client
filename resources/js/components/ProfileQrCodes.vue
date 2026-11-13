<template>
    <div class="qr-actions">
        <button
            v-for="category in categories"
            :key="category.key"
            type="button"
            class="qr-action"
            @click="openQr(category, $event)"
        >
            <span class="qr-action-icon"><i :class="category.icon" aria-hidden="true"></i></span>
            <span>{{ category.title }}</span>
            <i class="bi bi-arrow-up-right qr-action-arrow" aria-hidden="true"></i>
        </button>
    </div>

    <Teleport to="body">
        <div ref="modal" class="modal" tabindex="-1" aria-labelledby="profile-qr-title" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content qr-modal">
                    <div class="modal-header">
                        <div>
                            <p class="qr-eyebrow mb-1">PUSTARA · KODE AKSES</p>
                            <h4 id="profile-qr-title" class="modal-title">{{ selected.title }}</h4>
                        </div>
                        <button type="button" class="btn-close" aria-label="Tutup" :disabled="exporting" @click="closeQr"></button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="qr-preview">
                            <img v-if="qrImage" :src="qrImage" :alt="selected.title" width="240" height="240" />
                            <span v-else-if="loading" role="status">Menyiapkan QR code...</span>
                            <span v-else>QR code belum tersedia.</span>
                        </div>
                        <p class="text-muted mt-3 mb-1">Pindai kode untuk membuka tautan berikut</p>
                        <p class="qr-url mb-0">{{ url }}</p>
                        <p v-if="selected.key === 'offline'" class="small text-muted mt-2 mb-0">
                            Alamat 127.0.0.1 mengarah ke perangkat yang memindai QR, bukan otomatis ke server perpustakaan.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" :disabled="exporting" @click="closeQr">Tutup</button>
                        <button type="button" class="btn btn-primary" :disabled="!qrImage || exporting" @click="exportPdf">
                            <i class="bi bi-file-earmark-pdf me-1" aria-hidden="true"></i>
                            {{ exporting ? 'Menyiapkan PDF...' : 'Cetak / Download PDF' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="pdf-staging" aria-hidden="true">
            <div ref="pdf" class="pdf-sheet">
                <div class="pdf-header">
                    <div class="pdf-brand">PUSTARA <span> / PERPUSTAKAAN</span></div>
                    <div class="pdf-header-label">KODE AKSES DIGITAL</div>
                </div>
                <div class="pdf-content">
                    <div class="pdf-tag">SCAN &amp; AKSES</div>
                    <h1>{{ selected.title }}</h1>
                    <p class="pdf-instruction">{{ selected.instruction }}</p>
                    <div class="pdf-qr-frame">
                        <img v-if="qrImage" :src="qrImage" :alt="selected.title" width="260" height="260" />
                    </div>
                    <p class="pdf-link-label">ATAU KUNJUNGI TAUTAN</p>
                    <p class="pdf-url">{{ url }}</p>
                </div>
                <div class="pdf-footer">
                    <div>{{ libraryName || 'Perpustakaan Pustara' }}</div>
                    <span>DOKUMEN RESMI · PUSTARA</span>
                </div>
            </div>
        </div>
    </Teleport>
</template>

<script>
import { Modal } from 'bootstrap'
import QRCode from 'qrcode'

export default {
    props: {
        libraryName: { type: String, default: '' },
    },
    data() {
        return {
            categories: [
                { key: 'online', title: 'QR Code Perpustakaan Online', icon: 'bi bi-globe2', instruction: 'Pindai kode ini untuk mengunjungi perpustakaan online.' },
                { key: 'offline', title: 'QR Code Perpustakaan Offline', icon: 'bi bi-hdd-network', instruction: 'Pindai kode ini untuk mengakses perpustakaan offline.' },
                { key: 'visitor', title: 'QR Code Pengunjung Fisik', icon: 'bi bi-person-check', instruction: 'Pindai kode ini untuk mengisi daftar kunjungan perpustakaan.' },
            ],
            selected: {},
            url: '',
            qrImage: '',
            loading: false,
            exporting: false,
            requestId: 0,
            modalInstance: null,
            trigger: null,
        }
    },
    mounted() {
        this.modalInstance = new Modal(this.$refs.modal)
        this.$refs.modal.addEventListener('hidden.bs.modal', this.onHidden)
        this.$refs.modal.addEventListener('hide.bs.modal', this.onHide)
    },
    beforeUnmount() {
        this.requestId++
        this.exporting = false
        this.modalInstance.hide()
        this.$refs.modal.removeEventListener('hidden.bs.modal', this.onHidden)
        this.$refs.modal.removeEventListener('hide.bs.modal', this.onHide)
        this.modalInstance.dispose()
    },
    methods: {
        async openQr(category, event) {
            const requestId = ++this.requestId
            this.trigger = event.currentTarget
            this.selected = category
            this.url = category.key === 'offline'
                ? 'http://127.0.0.1:8082'
                : window.location.origin + (category.key === 'visitor' ? '/offline-visitor' : '')
            this.qrImage = ''
            this.loading = true
            this.modalInstance.show()
            try {
                const image = await QRCode.toDataURL(this.url, { width: 600, margin: 4, errorCorrectionLevel: 'M' })
                if (requestId === this.requestId) this.qrImage = image
            } catch (error) {
                console.error('Gagal membuat QR code:', error)
                if (requestId === this.requestId) {
                    this.$swal({ icon: 'error', title: 'QR code gagal dibuat', text: 'Silakan coba lagi.' })
                }
            } finally {
                if (requestId === this.requestId) this.loading = false
            }
        },
        closeQr() {
            if (!this.exporting) this.modalInstance.hide()
        },
        onHide(event) {
            if (this.exporting) event.preventDefault()
        },
        onHidden() {
            this.requestId++
            this.loading = false
            if (this.trigger && this.trigger.isConnected) this.trigger.focus()
        },
        async exportPdf() {
            if (!this.qrImage || this.exporting) return
            this.exporting = true
            try {
                const { default: html2pdf } = await import('html2pdf.js')
                await html2pdf().set({
                    margin: 0,
                    filename: `pustara-qr-${this.selected.key}.pdf`,
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2, backgroundColor: '#ffffff' },
                    jsPDF: { unit: 'mm', format: 'a5', orientation: 'portrait' },
                }).from(this.$refs.pdf).save()
            } catch (error) {
                console.error('Gagal membuat PDF QR code:', error)
                this.$swal({ icon: 'error', title: 'PDF gagal dibuat', text: 'Silakan coba lagi.' })
            } finally {
                this.exporting = false
            }
        },
    },
}
</script>

<style scoped>
.qr-actions { display: flex; flex-wrap: wrap; justify-content: center; gap: 12px; margin-top: 12px; }
.qr-action { display: flex; align-items: center; gap: 10px; padding: 10px 14px; border: 1px solid var(--admin-border, #d3e4eb); border-radius: 12px; background: var(--admin-surface, #fff); color: var(--admin-text, #34495a); font-weight: 700; text-align: left; transition: border-color .2s, box-shadow .2s; }
.qr-action:hover, .qr-action:focus-visible { border-color: var(--admin-accent, #20b7a5); box-shadow: 0 4px 14px rgba(20, 87, 131, .16); }
.qr-action-icon { display: grid; place-items: center; width: 36px; height: 36px; flex-shrink: 0; border-radius: 10px; background: var(--admin-mint, #e8f7f4); color: var(--admin-primary, #145783); font-size: 18px; }
.qr-action-arrow { color: var(--admin-muted, #617484); }
.qr-modal { border: 0; border-radius: 18px; overflow: hidden; }
.qr-eyebrow { color: var(--admin-primary, #145783); font-size: 11px; font-weight: 800; letter-spacing: .12em; }
.qr-preview { display: flex; align-items: center; justify-content: center; width: 264px; height: 264px; max-width: 100%; margin: 0 auto; border: 1px solid var(--admin-border, #d3e4eb); border-radius: 18px; background: #fff; color: #34495a; }
.qr-preview img { max-width: 100%; height: auto; }
.qr-url { color: var(--admin-primary, #145783); font-weight: 700; overflow-wrap: anywhere; }
.pdf-staging { position: fixed; left: -10000px; top: 0; width: 559px; pointer-events: none; }
.pdf-sheet { width: 100%; height: 780px; background: #fff; color: var(--admin-print-text, #34495a); font-family: Arial, sans-serif; }
.pdf-header { height: 100px; padding: 30px 38px; background: var(--admin-print-ice, #eaf4fa); color: var(--admin-print-primary, #145783); border-bottom: 3px solid var(--admin-print-accent, #20b7a5); }
.pdf-brand { font-size: 20px; font-weight: 800; letter-spacing: .08em; }
.pdf-brand span { color: var(--admin-print-muted, #617484); font-size: 10px; letter-spacing: .12em; }
.pdf-header-label { margin-top: 8px; color: var(--admin-print-muted, #617484); font-size: 10px; letter-spacing: .16em; }
.pdf-content { height: 580px; padding: 25px 38px 0; text-align: center; }
.pdf-tag { display: inline-block; padding: 6px 12px; border-radius: 20px; background: var(--admin-print-mint, #e8f7f4); color: var(--admin-print-primary, #145783); font-size: 10px; font-weight: 700; letter-spacing: .12em; }
.pdf-content h1 { margin: 14px 0 8px; color: var(--admin-print-primary, #145783); font-size: 23px; font-weight: 700; line-height: 1.2; }
.pdf-instruction { margin: 0; color: var(--admin-print-muted, #617484); font-size: 13px; }
.pdf-qr-frame { display: flex; align-items: center; justify-content: center; width: 286px; height: 286px; margin: 20px auto; border: 1px solid var(--admin-print-border, #d3e4eb); border-radius: 18px; background: #fff; }
.pdf-link-label { margin: 0 0 7px; color: var(--admin-print-muted, #617484); font-size: 10px; font-weight: 700; letter-spacing: .12em; }
.pdf-url { margin: 0; color: var(--admin-print-primary, #145783); font-size: 13px; font-weight: 700; overflow-wrap: anywhere; }
.pdf-footer { height: 100px; padding: 22px 38px; border-top: 1px solid var(--admin-print-border, #d3e4eb); color: var(--admin-print-text, #34495a); font-size: 13px; font-weight: 700; overflow-wrap: anywhere; }
.pdf-footer span { display: block; margin-top: 6px; color: var(--admin-print-muted, #617484); font-size: 10px; font-weight: 400; letter-spacing: .1em; }
</style>
