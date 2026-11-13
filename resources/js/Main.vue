<template>
    <div id="app-usr">
        <div id="main" class="member-layout" :class="{ 'auth-layout': ['mlogin', 'mregister'].includes($route.name) }">
            <headerItems
                :isAuthenticated="isAuthenticated"
                :name="user.name"
                :avatar="user.avatar"
                :appname="siteInfo.app_name"
                :tagline="siteInfo.tagline"
                :logoUrl="siteInfo.logo_url"
                :smallLogoUrl="siteInfo.small_logo_url"
                :register="param.register"
                :additional_features="param.additional_features"
            ></headerItems>
        
            <main class="content-wrapper">
                <router-view :isAuthenticated="isAuthenticated" :register="param.register" :emailVerificationRequired="param.emailVerificationRequired" :approvalRequired="param.approvalRequired" :additional_features="param.additional_features"></router-view>
            </main>

            <template v-if="param.fromOffline">
                <button class="floating-btn" @click="goOffline" title="Ke Mode Offline">
                    <i class="bi bi-cloud-slash"></i>
                </button>
            </template>

            <footer class="bottom-mobile">
                <footerItems :site-info="siteInfo"></footerItems>
            </footer>

            <nav class="mobile-app-nav" aria-label="Navigasi bawah">
                <router-link to="/" class="mobile-app-nav__item" active-class="is-active" exact-active-class="is-active">
                    <i class="bi bi-house-door" aria-hidden="true"></i>
                    <span>Beranda</span>
                </router-link>
                <router-link to="/koleksi-buku" class="mobile-app-nav__item" active-class="is-active">
                    <i class="bi bi-bookshelf" aria-hidden="true"></i>
                    <span>Koleksi</span>
                </router-link>
                <button
                    type="button"
                    class="mobile-app-nav__item"
                    data-bs-toggle="modal"
                    data-bs-target="#search-modal"
                    aria-label="Cari buku"
                >
                    <i class="bi bi-search" aria-hidden="true"></i>
                    <span>Cari</span>
                </button>
                <router-link v-if="isAuthenticated" to="/history-pinjaman" class="mobile-app-nav__item" active-class="is-active">
                    <i class="bi bi-journal-check" aria-hidden="true"></i>
                    <span>Pinjaman</span>
                </router-link>
                <router-link :to="isAuthenticated ? '/profile' : '/mlogin'" class="mobile-app-nav__item" active-class="is-active">
                    <i :class="isAuthenticated ? 'bi bi-person' : 'bi bi-box-arrow-in-right'" aria-hidden="true"></i>
                    <span>{{ isAuthenticated ? 'Profil' : 'Masuk' }}</span>
                </router-link>
            </nav>
        </div>

        <!-- search modal -->
        <div class="modal fade" id="search-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel33">Cari</h4>
                        <button type="button" id="close-search" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group position-relative has-icon-right">
                            <input type="text" class="form-control rounded-pill" placeholder="Cari Judul, Penulis, ISBN" v-model="searchQuery" @keypress.enter="searchBooks">
                            <div class="form-control-icon">
                                <i class="bi bi-search" @click="searchBooks"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- search modal -->
    </div>
</template>

<script>
import headerItems from './layouts/MainHeader.vue'
import footerItems from './layouts/MainFooter.vue'

export default {
    components: {
        headerItems,
        footerItems,
    },

    created() {
        const fromOffline = sessionStorage.getItem("fromOffline");
        if (fromOffline === "Y") {
            this.param.fromOffline = true;
        }
    },

    data() {
        return {
            user: {
                name: '',
                avatar: ''
            },
            param: {
                register: true,
				additional_features: 0,
                emailVerificationRequired: true,
                approvalRequired: false,
                fromOffline: false
            },
            siteInfo: {
                app_name: '',
                tagline: '',
                logo_url: '',
                small_logo_url: '',
                institution_name: '',
                address: '',
                phone: '',
                phone_url: '',
                website: '',
                social_links: [],
            },
            searchQuery: '',
            isAuthenticated: false,
        }
    },

    mounted() {
        this.getInfo();
        this.getAppInfo();
        this.getParam();
        this.initializeSubMenu();
        
        document.addEventListener('DOMContentLoaded', () => {
            let loader = this.$loading.show()
            setTimeout(() => {
                loader.hide()
            }, 1000)
        });

        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        }, false);
        // console.log(sessionStorage.getItem("fromOffline"));
    },

    methods: {
        getInfo() {
            this.user = {
                name: '',
                avatar: ''
            };

            axios.get('/getInfo')
            .then((response) => {
                this.user = response.data;

                if(response.data.name!=''){
                    this.isAuthenticated = true;
                }
            })
            .catch((e) => {
                console.error(e)
            });
        },

        getAppInfo() {
            axios.get('/getAppInfo')
            .then((response) => {
                this.siteInfo = response.data;
            })
            .catch((e) => {
                console.error(e)
            });
        },

        getParam() {
            axios.get('/getParam')
            .then((response) => {
                this.param.register = (response.data.reg_member==1) ? true : false;
                this.param.additional_features = parseInt(response.data.additional_features) || 0;
                this.param.emailVerificationRequired = parseInt(response.data.verify_email_member ?? 1) === 1;
                this.param.approvalRequired = parseInt(response.data.app_reg_member ?? 0) === 1;
            })
            .catch((e) => {
                console.error(e)
            });
        },

        searchBooks() {
            if(this.searchQuery!=''){
                this.$router.push({ name: 'koleksi-buku', query: { search: this.searchQuery } });
                this.searchQuery = '';
                const closeButton = document.getElementById('close-search');
                closeButton.click();
            }
        },

        initializeSubMenu() {
            let submenus = document.querySelectorAll('.submenu-item')
    
            submenus.forEach((submenu) => {
                submenu.querySelector('.submenu-link').addEventListener('click', (e) => {
                    let navbar = document.querySelector('.main-navbar');
                    if (navbar.classList.contains('active')) {
                        navbar.classList.remove('active');
                    }
                    e.preventDefault()
                })
            })
        },

        goOffline() {
            sessionStorage.removeItem("fromOffline");

            const url_offline = import.meta.env.VITE_URL_OFFLINE
            window.location.replace(url_offline);

            setTimeout(() => {
                history.pushState(null, '', window.location.href);
                window.onpopstate = () => history.go(1);
            }, 500);
        }
    },

    computed: {},
}
</script>

<style scoped>
    #app-usr {
        --member-ink: var(--pustara-text);
        --member-muted: var(--pustara-muted);
        --member-paper: var(--pustara-background);
        --member-surface: var(--pustara-surface);
        --member-line: var(--pustara-border);
        --member-accent: var(--pustara-primary);
        --member-accent-dark: var(--pustara-primary-hover);
        --member-button: #20b7a5;
        --member-button-hover: #128d82;
        --member-button-text: var(--pustara-button-text);
        --member-soft: var(--pustara-ice);
        --member-panel: var(--pustara-mint);
        --member-editorial-orange: var(--pustara-turquoise);
        --member-quote-bg: var(--pustara-ice);
        --member-quote-card: rgba(255, 255, 255, .64);
        --member-review-bg: var(--pustara-cloud);
        --member-review-line: var(--pustara-border);
        --member-cover-bg: var(--pustara-ice);
        --member-humoria-bg: var(--pustara-mint);
        --member-humoria-line: var(--pustara-turquoise-soft);
        --member-humoria-card-line: var(--pustara-turquoise-soft);
        --member-humoria-ink: var(--pustara-text);
        --member-humoria-muted: var(--pustara-muted);
        --member-humoria-link: var(--pustara-primary);
        --member-humoria-number: var(--pustara-primary);
        --member-humoria-image: var(--pustara-mint);
        --member-humoria-sticker: var(--pustara-ice);
        --member-humoria-sticker-ink: var(--pustara-primary);
        --member-humoria-sticker-line: var(--pustara-surface);
        min-height: 100vh;
        min-height: 100dvh;
        color: var(--member-ink);
        background: var(--member-paper);
        font-family: 'Nunito', sans-serif;
        -webkit-font-smoothing: antialiased;
    }

    :global(html[data-bs-theme="dark"] #app-usr) {
        --member-ink: var(--pustara-text);
        --member-muted: var(--pustara-muted);
        --member-paper: var(--pustara-background);
        --member-surface: var(--pustara-surface);
        --member-line: var(--pustara-border);
        --member-accent: var(--pustara-turquoise);
        --member-accent-dark: var(--pustara-turquoise-strong);
        --member-button: #20b7a5;
        --member-button-hover: #128d82;
        --member-button-text: var(--pustara-button-text);
        --member-soft: var(--pustara-surface-raised);
        --member-panel: var(--pustara-surface-raised);
        --member-editorial-orange: var(--pustara-turquoise);
        --member-quote-bg: var(--pustara-surface-raised);
        --member-quote-card: rgba(13, 20, 16, .55);
        --member-review-bg: var(--pustara-surface);
        --member-review-line: var(--pustara-border);
        --member-cover-bg: var(--pustara-surface-raised);
        --member-humoria-bg: var(--pustara-surface-raised);
        --member-humoria-line: var(--pustara-border);
        --member-humoria-card-line: var(--pustara-border);
        --member-humoria-ink: var(--pustara-text);
        --member-humoria-muted: var(--pustara-muted);
        --member-humoria-link: var(--pustara-turquoise);
        --member-humoria-number: var(--pustara-turquoise);
        --member-humoria-image: var(--pustara-surface);
        --member-humoria-sticker: var(--pustara-surface-raised);
        --member-humoria-sticker-ink: var(--pustara-turquoise);
        --member-humoria-sticker-line: var(--pustara-border);
        color-scheme: dark;
    }

    :global(html[data-bs-theme="dark"] #app-usr .bg-white),
    :global(html[data-bs-theme="dark"] #app-usr .bg-light),
    :global(html[data-bs-theme="dark"] #app-usr .bg-body) {
        color: var(--member-ink);
        background-color: var(--member-surface) !important;
    }

    :global(html[data-bs-theme="dark"] #app-usr .bg-white .text-white),
    :global(html[data-bs-theme="dark"] #app-usr .bg-light .text-white),
    :global(html[data-bs-theme="dark"] #app-usr .card .text-white) {
        color: var(--member-ink) !important;
    }

    :global(html[data-bs-theme="dark"] #app-usr .text-dark),
    :global(html[data-bs-theme="dark"] #app-usr .card .text-dark) {
        color: var(--member-ink) !important;
    }

    :global(html[data-bs-theme="dark"] #app-usr .text-secondary),
    :global(html[data-bs-theme="dark"] #app-usr .text-body-secondary) {
        color: var(--member-muted) !important;
    }

    :global(html[data-bs-theme="dark"] #app-usr .text-primary),
    :global(html[data-bs-theme="dark"] #app-usr .link-primary) {
        color: var(--member-accent) !important;
    }

    :global(html[data-bs-theme="dark"] #app-usr .form-control),
    :global(html[data-bs-theme="dark"] #app-usr .form-select),
    :global(html[data-bs-theme="dark"] #app-usr .input-group-text) {
        color: var(--member-ink);
        background-color: var(--member-surface);
        border-color: var(--member-line);
    }

    :global(html[data-bs-theme="dark"] #app-usr .form-control:disabled),
    :global(html[data-bs-theme="dark"] #app-usr .form-select:disabled) {
        color: var(--member-muted);
        background-color: var(--member-soft);
    }

    :global(html[data-bs-theme="dark"] #app-usr .form-control::placeholder) {
        color: var(--member-muted);
        opacity: 1;
    }

    :global(html[data-bs-theme="dark"] #app-usr .table-light) {
        --bs-table-color: var(--member-ink);
        --bs-table-bg: var(--member-surface);
        --bs-table-border-color: var(--member-line);
        --bs-table-striped-bg: var(--member-soft);
        --bs-table-striped-color: var(--member-ink);
        --bs-table-active-bg: var(--member-soft);
        --bs-table-active-color: var(--member-ink);
        --bs-table-hover-bg: var(--member-soft);
        --bs-table-hover-color: var(--member-ink);
    }

    :global(html[data-bs-theme="dark"] #app-usr .list-group-item),
    :global(html[data-bs-theme="dark"] #app-usr .accordion-item),
    :global(html[data-bs-theme="dark"] #app-usr .accordion-button) {
        color: var(--member-ink);
        background-color: var(--member-surface);
        border-color: var(--member-line);
    }

    :global(html[data-bs-theme="dark"] #app-usr .accordion-button:not(.collapsed)) {
        color: var(--member-accent);
        background-color: var(--member-soft);
    }

    :global(html[data-bs-theme="dark"] #app-usr .modal-content),
    :global(html[data-bs-theme="dark"] #app-usr .popover) {
        color: var(--member-ink);
        background-color: var(--member-surface);
        border-color: var(--member-line);
    }

    :global(html[data-bs-theme="dark"] #app-usr .modal-header),
    :global(html[data-bs-theme="dark"] #app-usr .modal-footer),
    :global(html[data-bs-theme="dark"] #app-usr .popover-header) {
        color: var(--member-ink);
        background-color: var(--member-surface);
        border-color: var(--member-line);
    }

    :global(html[data-bs-theme="dark"] #app-usr .product-card),
    :global(html[data-bs-theme="dark"] #app-usr #modalContent),
    :global(html[data-bs-theme="dark"] #app-usr .captcha-container) {
        color: var(--member-ink);
        background-color: var(--member-surface);
        border-color: var(--member-line);
    }

    :global(html[data-bs-theme="dark"] #app-usr .team) {
        background-color: #1f4b65;
    }

    :global(html[data-bs-theme="dark"] #app-usr .button-quiz) {
        color: #fff;
        background-color: var(--member-button);
    }

    :global(html[data-bs-theme="dark"] #app-usr .page-content .product-image) {
        background-color: var(--member-paper);
    }

    :global(html[data-bs-theme="dark"] #app-usr #AvatarFileUpload) {
        background-color: var(--member-paper);
        border-color: var(--member-line);
    }

    .member-layout {
        min-height: 100vh;
        min-height: 100dvh;
        overflow-x: clip;
    }

    #main.member-layout {
        display: flex;
        flex-direction: column;
        margin-left: 0;
        padding: 0;
    }

    #app-usr :deep(.content-wrapper) {
        width: auto;
        max-width: 1500px;
        min-height: 0;
        flex: 1 0 auto;
        margin: 76px 0 0 256px;
        padding: 2rem clamp(1.25rem, 3vw, 3rem) 4rem;
    }

    #app-usr :deep(.bottom-mobile) {
        flex: 0 0 auto;
        margin-left: 256px;
        padding: 0 clamp(1.25rem, 3vw, 3rem) max(1rem, env(safe-area-inset-bottom));
    }

    #app-usr :deep(.bottom-mobile .container) {
        width: 100%;
        max-width: 1500px;
    }

    #main.auth-layout :deep(.member-sidebar) {
        display: none !important;
    }

    #main.auth-layout :deep(.bottom-mobile) {
        margin-left: 0;
    }

    #main.auth-layout :deep(.member-topbar) {
        left: 0;
        margin-left: 0;
    }

    #main.auth-layout :deep(.content-wrapper) {
        max-width: 960px;
        margin-right: auto;
        margin-left: auto;
    }

    #main.auth-layout :deep(.topbar-start .sidebar-toggle),
    #main.auth-layout :deep(.mobile-brand) {
        display: none !important;
    }

    :global(html[data-bs-theme="dark"] .member-layout) {
        background: var(--member-paper);
    }

    #app-usr :deep(.page-heading),
    #app-usr :deep(.page-content),
    #app-usr :deep(.section) {
        color: var(--member-ink);
    }

    #app-usr :deep(.page-content > section),
    #app-usr :deep(.page-heading > section),
    #app-usr :deep(.section) {
        margin-bottom: 2rem;
    }

    #app-usr :deep(h1),
    #app-usr :deep(h2),
    #app-usr :deep(h3),
    #app-usr :deep(h4),
    #app-usr :deep(h5),
    #app-usr :deep(h6) {
        color: var(--member-ink);
        font-family: 'Libre Franklin', 'Nunito', sans-serif;
        font-weight: 700;
        letter-spacing: -0.025em;
    }

    #app-usr :deep(.reader-header h1) {
        color: #fff;
    }

    #app-usr :deep(.testimonial-slider h2),
    #app-usr :deep(.video-showcase-section h2) {
        color: var(--member-ink);
    }

    #app-usr :deep(.video-showcase-section .editorial-kicker) {
        color: var(--member-accent);
    }

    #app-usr :deep(.reader-header .reader-eyebrow) {
        color: var(--pustara-turquoise);
    }

    #app-usr :deep(.page-heading h3),
    #app-usr :deep(.page-title h3) {
        font-size: clamp(1.65rem, 3vw, 2.3rem);
        margin-bottom: 1.25rem;
    }

    #app-usr :deep(.divider-left-center) {
        margin: 1rem 0 1.25rem;
    }

    #app-usr :deep(.divider-left-center h2),
    #app-usr :deep(.divider-text) {
        color: var(--member-ink);
        font-size: 1.05rem;
        font-weight: 800;
        letter-spacing: .08em;
    }

    #app-usr :deep(.card) {
        overflow: hidden;
        color: var(--member-ink);
        background: var(--member-surface);
        border: 1px solid var(--member-line);
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(31, 48, 38, .035);
    }

    #app-usr :deep(.card-header),
    #app-usr :deep(.card-footer) {
        color: var(--member-ink);
        background: var(--member-surface);
        border-color: var(--member-line);
    }

    #app-usr :deep(.card-body) {
        color: var(--member-ink);
    }

    #app-usr :deep(.card-title),
    #app-usr :deep(.card a:not(.btn)) {
        color: var(--member-ink);
        text-decoration: none;
    }

    #app-usr :deep(.card a:not(.btn):hover),
    #app-usr :deep(.page-content a:not(.btn):hover) {
        color: var(--member-accent);
    }

    #app-usr :deep(.text-muted),
    #app-usr :deep(.text-subtitle) {
        color: var(--member-muted) !important;
    }

    #app-usr :deep(.btn-primary) {
        --bs-btn-bg: var(--member-button);
        --bs-btn-border-color: var(--member-button);
        --bs-btn-hover-bg: var(--member-button-hover);
        --bs-btn-hover-border-color: var(--member-button-hover);
        --bs-btn-active-bg: var(--member-button-hover);
        --bs-btn-active-border-color: var(--member-button-hover);
        --bs-btn-disabled-bg: var(--member-button);
        --bs-btn-disabled-border-color: var(--member-button);
        --bs-btn-color: #fff;
        --bs-btn-hover-color: #fff;
        --bs-btn-active-color: #fff;
        --bs-btn-disabled-color: #fff;
        color: #fff;
        font-weight: 800;
    }

    #app-usr :deep(.btn-success) {
        --bs-btn-bg: var(--member-button);
        --bs-btn-border-color: var(--member-button);
        --bs-btn-hover-bg: var(--member-button-hover);
        --bs-btn-hover-border-color: var(--member-button-hover);
        --bs-btn-active-bg: var(--member-button-hover);
        --bs-btn-active-border-color: var(--member-button-hover);
        --bs-btn-disabled-bg: var(--member-button);
        --bs-btn-disabled-border-color: var(--member-button);
        --bs-btn-color: #fff;
        --bs-btn-hover-color: #fff;
        --bs-btn-active-color: #fff;
        --bs-btn-disabled-color: #fff;
        color: #fff;
        font-weight: 800;
    }

    #app-usr :deep(.btn) {
        border-radius: .375rem !important;
        font-family: 'Nunito', sans-serif;
        font-size: .85rem;
        font-weight: 800;
    }

    #app-usr :deep(.btn-secondary),
    #app-usr :deep(.btn-light-secondary) {
        border-radius: .375rem;
        font-weight: 800;
    }

    #app-usr :deep(.sidebar-auth .sidebar-register) {
        color: #fff;
        background: var(--member-button);
        border-color: var(--member-button);
    }

    #app-usr :deep(.sidebar-auth .sidebar-register:hover) {
        color: #fff;
        background: var(--member-button-hover);
        border-color: var(--member-button-hover);
    }

    #app-usr :deep(.form-control),
    #app-usr :deep(.form-select) {
        min-height: 44px;
        color: var(--member-ink);
        background-color: var(--member-surface);
        border: 1px solid var(--member-line);
        border-radius: 8px;
        box-shadow: none;
    }

    #app-usr :deep(.form-control::placeholder) {
        color: var(--member-muted);
        opacity: .85;
    }

    #app-usr :deep(.form-control:focus),
    #app-usr :deep(.form-select:focus) {
        border-color: var(--member-accent);
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--member-accent) 16%, transparent);
    }

    #app-usr :deep(.form-group label),
    #app-usr :deep(.form-label) {
        color: var(--member-ink);
        font-weight: 700;
    }

    #app-usr :deep(.form-check-input:checked) {
        background-color: var(--member-button);
        border-color: var(--member-button);
    }

    #app-usr :deep(.nav-tabs) {
        gap: .4rem;
        padding: 0 1.25rem;
        border-bottom-color: var(--member-line);
    }

    #app-usr :deep(.nav-tabs .nav-link) {
        color: var(--member-muted);
        border: 0;
        border-bottom: 2px solid transparent;
        padding: .85rem .9rem;
        font-weight: 700;
    }

    #app-usr :deep(.nav-tabs .nav-link.active) {
        color: var(--member-accent);
        background: transparent;
        border-bottom-color: var(--member-accent);
    }

    #app-usr :deep(.page-content .product-image) {
        min-height: 220px;
        max-height: none;
        padding: 1rem;
        background: #f3f4ef;
    }

    #app-usr :deep(.page-content .product-image img) {
        width: auto !important;
        height: auto !important;
        max-width: 100%;
        max-height: 205px;
        object-fit: contain;
        border-radius: 4px;
        transition: transform .2s ease;
    }

    #app-usr :deep(.page-content .product-image:hover img) {
        transform: translateY(-3px);
    }

    #app-usr :deep(.page-content .row-cols-2 > .col .card),
    #app-usr :deep(.page-content .row-cols-md-6 > .col .card) {
        height: 100%;
    }

    #app-usr :deep(.page-content .card-body .card-title),
    #app-usr :deep(.page-content .card-body h6) {
        line-height: 1.4;
    }

    #app-usr :deep(.page-content .pagination) {
        flex-wrap: wrap;
        gap: .3rem;
    }

    #app-usr :deep(.page-content .page-link) {
        min-width: 40px;
        color: var(--member-ink);
        background: var(--member-surface);
        border-color: var(--member-line);
        border-radius: 7px;
        text-align: center;
    }

    #app-usr :deep(.page-content .page-item.active .page-link) {
        color: #fff;
        background: var(--member-button);
        border-color: var(--member-button);
    }

    #app-usr :deep(.dropdown-menu) {
        max-width: min(92vw, 360px);
        color: var(--member-ink);
        background: var(--member-surface);
        border: 1px solid var(--member-line);
        border-radius: 10px;
        box-shadow: 0 12px 28px rgba(31, 48, 38, .1);
    }

    #app-usr :deep(.dropdown-item) {
        color: var(--member-ink);
        white-space: normal;
    }

    #app-usr :deep(.dropdown-item:hover),
    #app-usr :deep(.dropdown-item:focus) {
        color: var(--member-ink);
        background: var(--member-soft);
    }

    #app-usr :deep(.table) {
        --bs-table-color: var(--member-ink);
        --bs-table-bg: var(--member-surface);
        --bs-table-striped-bg: var(--member-soft);
        --bs-table-hover-bg: var(--member-soft);
        border-color: var(--member-line);
        vertical-align: middle;
    }

    #app-usr :deep(.dataTables_wrapper) {
        overflow-x: auto;
    }

    #app-usr :deep(.modal-content) {
        color: var(--member-ink);
        background: var(--member-surface);
        border: 1px solid var(--member-line);
        border-radius: 14px;
    }

    #app-usr :deep(.modal-header),
    #app-usr :deep(.modal-footer) {
        border-color: var(--member-line);
    }

    #app-usr :deep(.page-heading .auth-title) {
        color: var(--member-ink);
        font-size: clamp(2rem, 5vw, 3rem);
        font-weight: 800;
    }

    #app-usr :deep(.page-heading .auth-subtitle) {
        color: var(--member-muted);
        font-size: 1rem;
    }

    #app-usr :deep(.page-heading #auth-left),
    #app-usr :deep(.page-heading form.form) {
        padding: clamp(1.25rem, 4vw, 2.5rem);
        background: var(--member-surface);
        border: 1px solid var(--member-line);
        border-radius: 14px;
    }

    #app-usr :deep(.card-cover .card) {
        padding: .75rem;
    }

    #app-usr :deep(.card-cover .img-detail) {
        width: auto !important;
        max-width: min(100%, 320px);
        max-height: 480px;
        object-fit: contain;
        border-radius: 4px !important;
    }

    #app-usr :deep(.card-cover .buttons) {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: .5rem;
    }

    #app-usr :deep(.details .comment-body) {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        padding: .75rem 0;
        border-bottom: 1px solid var(--member-line);
    }

    #app-usr :deep(.details .comment-profileName) {
        color: var(--member-muted);
        font-weight: 600;
    }

    #app-usr :deep(.details .comment-time) {
        color: var(--member-ink);
        text-align: right;
        font-weight: 700;
    }

    #app-usr :deep(.page-content article img),
    #app-usr :deep(.page-content > .col-12 img.img-fluid) {
        max-width: 100%;
        height: auto;
    }

    #app-usr :deep(.testimonial-slider) {
        background: var(--member-soft);
        border: 1px solid var(--member-line);
        border-radius: 12px;
        box-shadow: none !important;
    }

    #app-usr :deep(.testimonial-title),
    #app-usr :deep(.testimonial-title h2) {
        color: var(--member-accent-dark);
    }

    #app-usr :deep(.carousel img) {
        max-width: 100%;
        object-fit: cover;
    }

    #app-usr :deep(iframe),
    #app-usr :deep(video),
    #app-usr :deep(audio),
    #app-usr :deep(canvas) {
        max-width: 100%;
    }

    #app-usr :deep(.footer) {
        color: var(--member-muted);
        padding: .75rem 0;
    }

    :global(html[data-bs-theme="dark"] #app-usr .reader-experience),
    :global(html[data-bs-theme="dark"] #app-usr .reader-stage),
    :global(html[data-bs-theme="dark"] #app-usr #pdf-container) {
        color: var(--member-ink);
        background: var(--member-paper);
    }

    :global(html[data-bs-theme="dark"] #app-usr .reader-note) {
        color: var(--member-muted);
    }

    :global(html[data-bs-theme="dark"] #app-usr .reader-stage canvas) {
        border-color: var(--member-line);
    }

    :global(html[data-bs-theme="dark"] #app-usr .pagination-float) {
        background: rgba(24, 43, 56, .97);
        border-color: var(--member-line);
    }

    :global(html[data-bs-theme="dark"] #app-usr .pagination-float .page-link) {
        color: var(--member-ink) !important;
        background: var(--member-surface) !important;
        border-color: var(--member-line) !important;
    }

    :global(html[data-bs-theme="dark"] #app-usr .pagination-float .page-link:not(.disabled):hover) {
        color: var(--member-accent) !important;
        background: var(--member-soft) !important;
    }

    #main.member-layout:has(.pagination-float) :deep(.bottom-mobile) {
        padding-bottom: max(4.5rem, calc(env(safe-area-inset-bottom) + 3.5rem));
    }

    .menu-item {
        color: var(--member-muted);
        text-decoration: none;
        padding: 0.3rem;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }

    .menu-item i {
        font-size: 1.1rem;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        -webkit-text-stroke: 1px;
    }

    .menu-item.active {
        color: var(--member-accent);
        border-bottom: 2px solid var(--member-accent);
    }

    .floating-btn {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: var(--member-button);
        color: white;
        border: none;
        border-radius: 50%;
        width: 56px;
        height: 56px;
        font-size: 22px;
        box-shadow: 0 4px 12px rgba(25, 54, 43, .18);
        cursor: pointer;
        z-index: 9999;
        transition: transform 0.2s ease-in-out;
    }

    .floating-btn:hover {
        background-color: var(--member-button-hover);
    }

    .mobile-app-nav {
        display: none;
    }

    .mobile-app-nav__item {
        display: flex;
        flex: 1 1 0;
        min-width: 0;
        min-height: 54px;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: .2rem;
        padding: .35rem .15rem;
        color: var(--member-muted);
        background: transparent;
        border: 0;
        border-radius: .6rem;
        font-family: inherit;
        font-size: .65rem;
        font-weight: 700;
        line-height: 1.1;
        text-decoration: none;
        transition: color .18s ease, background-color .18s ease;
    }

    .mobile-app-nav__item i {
        font-size: 1.15rem;
        line-height: 1;
    }

    .mobile-app-nav__item.is-active {
        color: var(--member-accent);
        background: var(--member-soft);
    }

    .mobile-app-nav__item:focus-visible {
        outline: 2px solid var(--member-accent);
        outline-offset: 1px;
    }

    .mobile-app-nav__item:hover {
        color: var(--member-accent);
    }

    :global(html[data-bs-theme="dark"] #app-usr .mobile-app-nav) {
        background: rgba(24, 43, 56, .96);
        border-color: var(--member-line);
    }

    @media (max-width: 1199.98px) {
        #app-usr :deep(.content-wrapper) {
            min-height: 0;
            margin: 68px 0 0;
            padding: 1.5rem clamp(1rem, 4vw, 2rem) 3rem;
        }

        #app-usr :deep(.bottom-mobile) {
            margin-left: 0;
            padding-inline: clamp(1rem, 4vw, 2rem);
        }
    }

    @media (max-width: 575px) {
        #app-usr :deep(.content-wrapper) {
            padding: 1rem .875rem max(3rem, calc(env(safe-area-inset-bottom) + 2rem));
        }

        #app-usr :deep(.page-content > section),
        #app-usr :deep(.page-heading > section),
        #app-usr :deep(.section) {
            margin-bottom: 1.25rem;
        }

        #app-usr :deep(.card-body) {
            padding: 1rem;
        }

        #app-usr :deep(.page-content .product-image) {
            min-height: 160px;
            padding: .65rem;
        }

        #app-usr :deep(.page-content .product-image img) {
            max-height: 150px;
        }

        #app-usr :deep(.page-content .pagination .page-link) {
            min-width: 34px;
            padding: .45rem .55rem;
            font-size: .85rem;
        }

        #app-usr :deep(.page-heading form.form) {
            padding: 1rem;
        }
    }

    @media (max-width: 767.98px) {
        .mobile-app-nav {
            position: fixed;
            z-index: 1030;
            right: 0;
            bottom: 0;
            left: 0;
            display: flex;
            align-items: stretch;
            gap: .2rem;
            padding: .35rem .45rem calc(.35rem + env(safe-area-inset-bottom));
            background: rgba(255, 255, 255, .96);
            border-top: 1px solid var(--member-line);
            box-shadow: 0 -8px 24px rgba(20, 40, 31, .08);
            backdrop-filter: blur(16px);
        }

        #app-usr :deep(.content-wrapper) {
            padding-bottom: calc(6rem + env(safe-area-inset-bottom));
        }

        #app-usr :deep(.bottom-mobile) {
            padding-bottom: calc(6rem + env(safe-area-inset-bottom));
        }

        #app-usr .floating-btn {
            bottom: calc(5.5rem + env(safe-area-inset-bottom));
        }
    }

</style>