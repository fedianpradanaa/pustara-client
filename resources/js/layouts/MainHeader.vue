<template>
    <aside id="member-sidebar" class="member-sidebar offcanvas offcanvas-xl offcanvas-start" tabindex="-1" aria-labelledby="member-sidebar-title">
        <div class="sidebar-brand">
            <router-link to="/" class="brand-link">
                <img v-if="logoUrl" :src="logoUrl" :alt="appname" class="brand-logo" data-bs-dismiss="offcanvas">
            </router-link>
            <button type="button" class="sidebar-close d-xl-none" data-bs-dismiss="offcanvas" aria-label="Tutup navigasi">
                <i class="bi bi-x-lg" aria-hidden="true"></i>
            </button>
        </div>

        <nav class="sidebar-navigation" aria-label="Navigasi utama">
            <span class="nav-caption">Temukan bacaan</span>
            <router-link to="/" class="sidebar-link">
                <i class="bi bi-house-door" aria-hidden="true" data-bs-dismiss="offcanvas"></i><span data-bs-dismiss="offcanvas">Beranda</span>
            </router-link>
            <router-link to="/koleksi-buku" class="sidebar-link">
                <i class="bi bi-bookshelf" aria-hidden="true" data-bs-dismiss="offcanvas"></i><span data-bs-dismiss="offcanvas">Koleksi Buku</span>
            </router-link>

            <div class="sidebar-group">
                <span class="nav-caption">Bacaan & wawasan</span>
                <router-link :to="{ name: 'artikel', params: { idart: 'TU' } }" class="sidebar-link sidebar-reading-link">
                    <i class="bi bi-newspaper" aria-hidden="true" data-bs-dismiss="offcanvas"></i><span data-bs-dismiss="offcanvas">Tajuk Utama</span>
                </router-link>
                <router-link :to="{ name: 'artikel', params: { idart: 'WA' } }" class="sidebar-link sidebar-reading-link">
                    <i class="bi bi-lightbulb" aria-hidden="true" data-bs-dismiss="offcanvas"></i><span data-bs-dismiss="offcanvas">Wawasan</span>
                </router-link>
                <router-link :to="{ name: 'artikel', params: { idart: 'RB' } }" class="sidebar-link sidebar-reading-link">
                    <i class="bi bi-bookmark-star" aria-hidden="true" data-bs-dismiss="offcanvas"></i><span data-bs-dismiss="offcanvas">Review Buku</span>
                </router-link>
                <router-link :to="{ name: 'artikel', params: { idart: 'LP' } }" class="sidebar-link sidebar-reading-link">
                    <i class="bi bi-person-lines-fill" aria-hidden="true" data-bs-dismiss="offcanvas"></i><span data-bs-dismiss="offcanvas">Layar Penulis</span>
                </router-link>
                <router-link :to="{ name: 'artikel', params: { idart: 'TF' } }" class="sidebar-link sidebar-reading-link">
                    <i class="bi bi-bullseye" aria-hidden="true" data-bs-dismiss="offcanvas"></i><span data-bs-dismiss="offcanvas">Titik Fokus</span>
                </router-link>
                <router-link :to="{ name: 'artikel', params: { idart: 'HU' } }" class="sidebar-link sidebar-reading-link">
                    <i class="bi bi-emoji-smile" aria-hidden="true" data-bs-dismiss="offcanvas"></i><span data-bs-dismiss="offcanvas">Humoria</span>
                </router-link>
            </div>

            <template v-if="isAuthenticated">
                <div class="sidebar-group">
                    <span class="nav-caption">Aktivitas</span>
                    <router-link v-if="additional_features==2 || additional_features==3" to="/quiz" class="sidebar-link">
                        <i class="bi bi-patch-question" aria-hidden="true" data-bs-dismiss="offcanvas"></i><span data-bs-dismiss="offcanvas">Kuis</span>
                    </router-link>
                    <router-link v-if="additional_features==1 || additional_features==3" to="/list-games" class="sidebar-link">
                        <i class="bi bi-controller" aria-hidden="true" data-bs-dismiss="offcanvas"></i><span data-bs-dismiss="offcanvas">Permainan</span>
                    </router-link>
                    <router-link to="/history-pinjaman" class="sidebar-link">
                        <i class="bi bi-bookmark-check" aria-hidden="true" data-bs-dismiss="offcanvas"></i><span data-bs-dismiss="offcanvas">Riwayat Pinjaman</span>
                    </router-link>
                </div>
            </template>
        </nav>

        <div class="sidebar-bottom">
            <div class="theme-control">
                <span><i class="bi bi-circle-half" aria-hidden="true"></i> Tema tampilan</span>
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input me-0" type="checkbox" id="toggle-dark" aria-label="Ganti tema" />
                </div>
            </div>
            <template v-if="isAuthenticated">
                <router-link to="/profile" class="sidebar-account">
                    <span class="account-avatar" data-bs-dismiss="offcanvas"><img :src="avatar" :alt="name"></span>
                    <span class="account-copy" data-bs-dismiss="offcanvas"><strong>{{ name }}</strong><small>Profil member</small></span>
                    <i class="bi bi-chevron-right" aria-hidden="true" data-bs-dismiss="offcanvas"></i>
                </router-link>
                <button type="button" class="sidebar-logout" @click.prevent="logout">
                    <i class="bi bi-box-arrow-right" aria-hidden="true"></i><span>Keluar</span>
                </button>
            </template>
            <template v-else>
                <div class="sidebar-auth">
                    <router-link :to="{ name: 'mlogin' }" class="sidebar-login"><span data-bs-dismiss="offcanvas">Masuk</span></router-link>
                    <router-link v-if="register" :to="{ name: 'mregister' }" class="sidebar-register"><span data-bs-dismiss="offcanvas">Daftar</span></router-link>
                </div>
            </template>
        </div>
    </aside>

    <header class="member-topbar">
        <div class="topbar-start">
            <button class="sidebar-toggle d-xl-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#member-sidebar" aria-controls="member-sidebar" aria-label="Buka navigasi">
                <i class="bi bi-list" aria-hidden="true"></i>
            </button>
            <router-link v-if="smallLogoUrl" to="/" class="mobile-brand d-xl-none" aria-label="Beranda">
                <img v-if="smallLogoUrl" :src="smallLogoUrl" :alt="appname">
            </router-link>
            <div class="topbar-context">
                <span v-if="tagline" class="topbar-kicker">{{ tagline }}</span>
                <strong>{{ appname }}</strong>
            </div>
        </div>

        <div class="topbar-actions">
            <div class="topbar-search d-none d-md-flex">
                <i class="bi bi-search" aria-hidden="true"></i>
                <input type="search" aria-label="Cari judul, penulis, atau ISBN" placeholder="Cari judul, penulis, ISBN" v-model="searchQuery" @keypress.enter="searchBooks">
                <button type="button" @click="searchBooks" aria-label="Cari"><i class="bi bi-arrow-return-left" aria-hidden="true"></i></button>
            </div>
            <button type="button" class="mobile-search-trigger d-md-none" data-bs-toggle="modal" data-bs-target="#search-modal" aria-label="Cari buku">
                <i class="bi bi-search" aria-hidden="true"></i>
            </button>
        </div>
    </header>
</template>

<script>
import "~@fontsource/libre-franklin";
import "~@fontsource/libre-franklin/900.css";
import "~@fontsource/libre-franklin/800.css";

export default {
    name: 'headerItems',

    props: {
        isAuthenticated: {
            type: Boolean,
            required: true
        },
        name: {
            type: String,
            required: true
        },
        avatar: {
            type: String,
            required: true
        },
        appname: {
            type: String,
            required: true
        },
        tagline: {
            type: String,
            required: true
        },
        logoUrl: {
            type: String,
            required: true
        },
        smallLogoUrl: {
            type: String,
            required: true
        },
        register: {
            type: Boolean,
            required: true
        },
        additional_features: {
            type: Number,
            required: true
        }
    },

    data() {
        return {
            user: {},
            searchQuery: ''
        }
    },

    mounted() {

    },

    methods: {
        logout() {
            let loader = this.$loading.show();
            window.axios.post('/logout').then((e) => {
                loader.hide();
                window.location = '/'
            })
        },

        searchBooks() {
            if(this.searchQuery!=''){
                this.$router.push({ name: 'koleksi-buku', query: { search: this.searchQuery } });
                this.searchQuery = '';
            }
        },
    },

    computed: {},
}
</script>

<style scoped>
.custom-button {
  cursor: pointer;
  border: 0;
  border-radius: 4px;
  font-weight: 600;
  margin: 0 10px;
  width: 100px;
  padding: 10px 0;
  box-shadow: 0 0 20px rgba(104, 85, 224, 0.2);
  transition: 0.4s;
}
.reg {
  color: white;
  background-color: #145783;
}

.log {
  color: #145783;
  background-color: rgba(255, 255, 255, 1);
  border: 1px solid #145783;
}

.log:hover {
  color: white;
  box-shadow: 0 0 20px rgba(20, 87, 131, 0.2);
  background-color: #145783;
}
.reg:hover {
  color: #ffffff;
  box-shadow: 0 0 20px rgba(32, 183, 165, .22);
  background-color: #104765;
}
.header-title {
    font-size: 23px;
    margin: 0px 0px 0px;
    font-family: 'Libre Franklin';
    font-weight: 800;
    color: #34495A;
}
.svg-color {
    color: #617484;
}

.header {
    z-index: 1020;
    margin: 0 !important;
    background: #fff;
    border-bottom: 1px solid #e4e8e2;
    box-shadow: 0 4px 16px rgba(31, 48, 38, .045);
}

.header-top {
    background: #fff;
}

.header-top > .container {
    display: flex;
    align-items: center;
    min-height: 74px;
    max-width: 1320px;
    padding: 0 1.25rem;
}

.logo-header img {
    max-width: 100%;
    object-fit: contain;
}

.header-top .header-title {
    margin: 0;
    font-size: clamp(1rem, 1.6vw, 1.35rem);
    line-height: 1.25;
    text-align: center;
}

.header-top-right {
    margin-left: auto;
}

.user-dropdown {
    color: #34495A;
    text-decoration: none;
}

.user-dropdown-name {
    margin-bottom: .1rem;
    color: #34495A;
    font-weight: 800;
}

.user-dropdown-status {
    margin: 0;
}

.custom-button {
    width: auto;
    min-width: 88px;
    margin: 0 0 0 .45rem;
    padding: .55rem 1rem;
    border: 1px solid #145783;
    border-radius: 7px;
    box-shadow: none;
    font-size: .9rem;
    font-weight: 700;
}

.reg,
.reg:hover {
    color: #fff;
    background: #145783;
}

.log,
.log:hover {
    color: #145783;
    background: #fff;
}

.main-navbar {
    padding: 0 !important;
    background: #fff;
    border-top: 1px solid #f0f1ed;
}

.main-navbar > .container {
    max-width: 1320px;
    padding: 0 1.25rem;
}

.main-navbar > .container > ul {
    display: flex;
    align-items: center;
    gap: .25rem;
    min-height: 50px;
    margin: 0;
    padding: 0;
    list-style: none;
}

.main-navbar .menu-item {
    display: flex;
    align-items: center;
    min-height: 50px;
    margin: 0;
}

.main-navbar .menu-link {
    display: flex;
    align-items: center;
    height: 50px;
    padding: 0 .8rem;
    color: #34495A;
    border-bottom: 2px solid transparent;
    text-decoration: none;
    font-size: .92rem;
    font-weight: 700;
    transition: color .15s ease, border-color .15s ease;
}

.main-navbar .menu-link:hover,
.main-navbar .menu-link.active {
    color: #145783;
    border-bottom-color: #20B7A5;
}

.main-navbar .submenu {
    top: 100%;
    min-width: 220px;
    padding: .45rem;
    background: #fff;
    border: 1px solid #e4e8e2;
    border-radius: 0 0 10px 10px;
    box-shadow: 0 12px 28px rgba(31, 48, 38, .1);
}

.main-navbar .submenu-link {
    display: block;
    padding: .65rem .75rem;
    color: #34495A;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
}

.main-navbar .submenu-link:hover,
.main-navbar .submenu-link.active {
    color: #104765;
    background: #EAF4FA;
}

.main-navbar .form-group {
    margin: 0;
}

.main-navbar > .container > ul > .d-none.d-xl-block {
    position: static !important;
    right: auto !important;
    margin-left: auto;
}

.main-navbar .form-control {
    width: clamp(190px, 18vw, 250px);
    min-height: 38px;
    padding-right: 2.5rem;
    border: 1px solid #dbe7ee;
    border-radius: 7px !important;
    background: #F7FAFC;
}

.main-navbar .form-control-icon {
    right: .4rem;
    color: #617484;
}

.main-navbar .theme-toogle {
    padding: 0 .25rem;
}

.main-navbar .theme-toggle {
    align-items: center;
    margin: 0 !important;
}

.burger-btn {
    color: #34495A;
}

:global(html[data-bs-theme="dark"] .layout-horizontal .header) {
    background: #182b38 !important;
    border-bottom-color: #345061;
}

:global(html[data-bs-theme="dark"] .layout-horizontal .header-top),
:global(html[data-bs-theme="dark"] .layout-horizontal .main-navbar) {
    background: #182b38 !important;
    border-color: #345061;
}

:global(html[data-bs-theme="dark"] .layout-horizontal .header-title),
:global(html[data-bs-theme="dark"] .layout-horizontal .user-dropdown),
:global(html[data-bs-theme="dark"] .layout-horizontal .user-dropdown-name),
:global(html[data-bs-theme="dark"] .layout-horizontal .burger-btn),
:global(html[data-bs-theme="dark"] .layout-horizontal .main-navbar .menu-link) {
    color: #dceaf1;
}

:global(html[data-bs-theme="dark"] .layout-horizontal .main-navbar .menu-link:hover),
:global(html[data-bs-theme="dark"] .layout-horizontal .main-navbar .menu-link.active) {
    color: #80c4df;
    border-bottom-color: #20B7A5;
}

:global(html[data-bs-theme="dark"] .layout-horizontal .main-navbar .submenu) {
    background: #182b38;
    border-color: #345061;
}

:global(html[data-bs-theme="dark"] .layout-horizontal .main-navbar .submenu-link) {
    color: #dceaf1;
}

:global(html[data-bs-theme="dark"] .layout-horizontal .main-navbar .submenu-link:hover),
:global(html[data-bs-theme="dark"] .layout-horizontal .main-navbar .submenu-link.active) {
    color: #dceaf1;
    background: #203b4d;
}

:global(html[data-bs-theme="dark"] .layout-horizontal .main-navbar .form-control) {
    color: #dceaf1;
    background: #101d27;
    border-color: #345061;
}

@media (max-width: 1199px) {
    .header {
        margin: 0 !important;
    }

    .header-top > .container {
        min-height: 66px;
        padding: 0 .8rem;
    }

    .header-top .header-title {
        overflow: hidden;
        font-size: 1rem;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .main-navbar {
        display: none;
    }
}

@media (max-width: 1200px) {
    .header-title {
        font-size: 1rem;
    }

    .svg-color {
        color: rgb(152, 153, 172);
    }
}

.member-sidebar {
    --sidebar-ink: #34495A;
    --sidebar-muted: #617484;
    --sidebar-line: #dbe7ee;
    --sidebar-accent: #145783;
    --sidebar-soft: #EAF4FA;
    display: flex;
    flex-direction: column;
    position: fixed;
    z-index: 1045;
    top: 0;
    left: 0;
    width: 256px;
    height: 100vh;
    height: 100dvh;
    min-height: 0;
    overflow: hidden;
    padding: 1.25rem 1rem 1rem;
    color: var(--sidebar-ink);
    background: #fff;
    border-right: 1px solid var(--sidebar-line);
}

.member-sidebar.offcanvas-xl {
    width: 256px !important;
}

.sidebar-brand {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex: 0 0 auto;
    min-height: 62px;
    padding: 0 .3rem 1.15rem;
    border-bottom: 1px solid var(--sidebar-line);
}

.brand-link {
    display: flex;
    align-items: center;
    min-width: 0;
    gap: .7rem;
    color: inherit;
    text-decoration: none;
}

.brand-logo {
    width: 130px;
    height: auto;
    object-fit: contain;
}

.brand-copy,
.account-copy {
    display: grid;
    min-width: 0;
    gap: .12rem;
}

.brand-copy strong {
    overflow: hidden;
    color: var(--sidebar-ink);
    font-family: 'Libre Franklin', sans-serif;
    font-size: .9rem;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.brand-copy small,
.account-copy small {
    color: var(--sidebar-muted);
    font-size: .72rem;
}

.sidebar-close {
    display: grid;
    width: 38px;
    height: 38px;
    place-items: center;
    color: var(--sidebar-ink);
    background: transparent;
    border: 0;
    border-radius: 8px;
}

.sidebar-navigation {
    flex: 1 1 0;
    min-height: 0;
    overflow-y: auto;
    padding: 1.25rem .1rem 1rem;
    overscroll-behavior: contain;
    scrollbar-gutter: stable;
}

.nav-caption {
    display: block;
    margin: 0 .65rem .55rem;
    color: #617484;
    font-size: .67rem;
    font-weight: 800;
    letter-spacing: .12em;
    text-transform: uppercase;
}

.sidebar-link,
.sidebar-group-label {
    display: flex;
    align-items: center;
    min-height: 44px;
    gap: .75rem;
    padding: .65rem .75rem;
    color: #34495A;
    border-radius: 8px;
    font-size: .9rem;
    font-weight: 700;
    text-decoration: none;
    transition: background-color .16s ease, color .16s ease;
}

.sidebar-link i,
.sidebar-group-label i {
    width: 20px;
    color: #617484;
    font-size: 1.05rem;
    text-align: center;
}

.sidebar-link:hover,
.sidebar-sublink:hover {
    color: var(--sidebar-accent);
    background: #EAF4FA;
}

.sidebar-link.router-link-active,
.sidebar-link.router-link-exact-active {
    color: #145783;
    background: var(--sidebar-soft);
}

:global(html[data-bs-theme="dark"] .member-sidebar .sidebar-link.router-link-active),
:global(html[data-bs-theme="dark"] .member-sidebar .sidebar-link.router-link-exact-active) {
    color: #dceaf1;
    background: #203b4d;
}

.sidebar-link.router-link-active i,
.sidebar-link.router-link-exact-active i {
    color: var(--sidebar-accent);
}

:global(html[data-bs-theme="dark"] .member-sidebar .sidebar-link.router-link-active i),
:global(html[data-bs-theme="dark"] .member-sidebar .sidebar-link.router-link-exact-active i) {
    color: #58d7c6;
}

.sidebar-link:focus-visible,
.sidebar-sublink:focus-visible,
.sidebar-auth a:focus-visible,
.sidebar-logout:focus-visible,
.sidebar-toggle:focus-visible,
.topbar-search button:focus-visible {
    outline: 3px solid rgba(32, 183, 165, .35);
    outline-offset: 2px;
}

.sidebar-group {
    margin-top: 1.45rem;
}

.sidebar-group-label {
    min-height: 38px;
    padding-bottom: .35rem;
    color: var(--sidebar-ink);
}

.sidebar-sublink {
    display: block;
    margin: .1rem 0 .1rem 1.25rem;
    padding: .47rem .7rem;
    color: #657269;
    border-radius: 7px;
    font-size: .84rem;
    text-decoration: none;
    transition: background-color .16s ease, color .16s ease;
}

.sidebar-sublink.router-link-active,
.sidebar-sublink.router-link-exact-active {
    color: var(--sidebar-accent);
    background: var(--sidebar-soft);
    font-weight: 800;
}

.sidebar-bottom {
    flex: 0 0 auto;
    position: sticky;
    z-index: 2;
    bottom: 0;
    padding: .7rem .15rem max(.7rem, env(safe-area-inset-bottom));
    background: var(--pustara-cloud, #F7FAFC);
    border-top: 1px solid var(--sidebar-line);
}

.theme-control {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: .3rem .6rem .8rem;
    color: #59675f;
    font-size: .83rem;
    font-weight: 700;
}

.theme-control > span {
    display: flex;
    align-items: center;
    gap: .6rem;
}

.theme-control .form-check-input {
    cursor: pointer;
}

.sidebar-account {
    display: flex;
    align-items: center;
    min-height: 58px;
    gap: .65rem;
    padding: .5rem .55rem;
    color: inherit;
    border-radius: 8px;
    text-decoration: none;
}

.sidebar-account:hover {
    background: #f5f6f2;
}

.account-avatar {
    display: grid;
    flex: 0 0 38px;
    width: 38px;
    height: 38px;
    place-items: center;
    overflow: hidden;
    background: var(--sidebar-soft);
    border-radius: 50%;
}

.account-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.account-copy {
    flex: 1;
}

.account-copy strong {
    overflow: hidden;
    color: var(--sidebar-ink);
    font-size: .84rem;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.sidebar-account > i {
    color: #89938d;
    font-size: .75rem;
}

.sidebar-logout {
    display: flex;
    align-items: center;
    width: 100%;
    gap: .65rem;
    margin-top: .2rem;
    padding: .55rem .65rem;
    color: #647168;
    background: transparent;
    border: 0;
    border-radius: 7px;
    font-size: .84rem;
    text-align: left;
}

.sidebar-logout:hover {
    color: #9e3c34;
    background: #fbf1ef;
}

.sidebar-auth {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: .55rem;
    padding: .3rem .45rem .15rem;
}

.sidebar-auth a {
    display: grid;
    min-height: 42px;
    place-items: center;
    border: 1px solid var(--sidebar-accent);
    border-radius: 12px;
    color: var(--sidebar-accent);
    font-size: .85rem;
    font-weight: 800;
    text-decoration: none;
    transition: color .18s ease, background-color .18s ease, border-color .18s ease, transform .18s ease;
}

.sidebar-auth .sidebar-login:hover {
    color: var(--sidebar-accent);
    background: var(--sidebar-soft);
    transform: translateY(-1px);
}

.sidebar-auth .sidebar-register {
    color: #fff;
    background: var(--member-button);
    border-color: var(--member-button);
}

.sidebar-auth .sidebar-register:hover {
    color: #fff;
    background: var(--member-button-hover);
    border-color: var(--member-button-hover);
    transform: translateY(-1px);
}

.member-topbar {
    position: fixed;
    z-index: 1020;
    top: 0;
    right: 0;
    left: 256px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-height: 76px;
    gap: 1rem;
    padding: .75rem clamp(1.25rem, 3vw, 3rem);
    background: rgba(255, 254, 250, .96);
    border-bottom: 1px solid #e6e9e3;
    backdrop-filter: blur(12px);
}

.topbar-start,
.topbar-actions {
    display: flex;
    align-items: center;
    min-width: 0;
    gap: 1rem;
}

.topbar-context {
    display: grid;
    min-width: 0;
    gap: .12rem;
}

.topbar-kicker {
    color: #75817a;
    font-size: .66rem;
    font-weight: 800;
    letter-spacing: .13em;
    text-transform: uppercase;
}

.topbar-context strong {
    overflow: hidden;
    color: #22322c;
    font-family: 'Libre Franklin', sans-serif;
    font-size: .96rem;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.topbar-search {
    display: flex;
    align-items: center;
    width: clamp(230px, 28vw, 370px);
    min-height: 42px;
    gap: .6rem;
    padding: 0 .5rem 0 .85rem;
    color: #78837c;
    background: #f4f5f1;
    border: 1px solid #e6e9e3;
    border-radius: 7px;
}

.topbar-search:focus-within {
    border-color: #8aaf9f;
    box-shadow: 0 0 0 3px rgba(35, 107, 88, .1);
}

.topbar-search input {
    width: 100%;
    min-width: 0;
    padding: .55rem 0;
    color: #22322c;
    background: transparent;
    border: 0;
    outline: 0;
    font: inherit;
    font-size: .82rem;
}

.topbar-search button,
.mobile-search-trigger,
.sidebar-toggle {
    display: grid;
    flex: 0 0 auto;
    width: 36px;
    height: 36px;
    place-items: center;
    color: #43564b;
    background: transparent;
    border: 0;
    border-radius: 7px;
}

.topbar-search button:hover,
.mobile-search-trigger:hover,
.sidebar-toggle:hover {
    color: var(--sidebar-accent);
    background: var(--sidebar-soft);
}

.topbar-search button {
    width: 30px;
    height: 30px;
}

.mobile-brand img {
    width: 34px;
    height: 34px;
    object-fit: contain;
}

.topbar-signin {
    color: var(--sidebar-accent);
    font-size: .88rem;
    font-weight: 800;
    text-decoration: none;
}

.topbar-signin:hover {
    color: #104765;
}

:global(html[data-bs-theme="dark"] .member-sidebar),
:global(html[data-bs-theme="dark"] .member-topbar) {
    --sidebar-ink: #dceaf1;
    --sidebar-muted: #a9bfcc;
    --sidebar-line: #345061;
    --sidebar-accent: #58d7c6;
    --sidebar-soft: #203b4d;
    color: #dceaf1;
    background: #182b38;
    border-color: #345061;
}

:global(html[data-bs-theme="dark"] .brand-copy strong),
:global(html[data-bs-theme="dark"] .topbar-context strong),
:global(html[data-bs-theme="dark"] .account-copy strong),
:global(html[data-bs-theme="dark"] .sidebar-group-label) {
    color: #dceaf1;
}

:global(html[data-bs-theme="dark"] .topbar-kicker),
:global(html[data-bs-theme="dark"] .sidebar-group-label i) {
    color: #a9bfcc;
}

:global(html[data-bs-theme="dark"] .sidebar-link),
:global(html[data-bs-theme="dark"] .sidebar-sublink),
:global(html[data-bs-theme="dark"] .theme-control),
:global(html[data-bs-theme="dark"] .sidebar-logout) {
    color: #c4d6e0;
}

:global(html[data-bs-theme="dark"] .sidebar-link:hover),
:global(html[data-bs-theme="dark"] .sidebar-sublink:hover),
:global(html[data-bs-theme="dark"] .sidebar-account:hover) {
    color: #dceaf1;
    background: #203b4d;
}

:global(html[data-bs-theme="dark"] .topbar-search) {
    color: #a9bfcc;
    background: #101d27;
    border-color: #345061;
}

:global(html[data-bs-theme="dark"] .topbar-search input) {
    color: #dceaf1;
}

:global(html[data-bs-theme="dark"] .topbar-search input::placeholder) {
    color: #a9bfcc;
    opacity: 1;
}

:global(html[data-bs-theme="dark"] .topbar-search button),
:global(html[data-bs-theme="dark"] .mobile-search-trigger),
:global(html[data-bs-theme="dark"] .sidebar-toggle) {
    color: #c4d6e0;
}

:global(html[data-bs-theme="dark"] .topbar-signin:hover) {
    color: #80c4df;
}

:global(html[data-bs-theme="dark"] .sidebar-auth .sidebar-register) {
    color: #fff;
    background: var(--member-button);
    border-color: var(--member-button);
}

@media (max-width: 1199.98px) {
    .member-sidebar {
        height: 100dvh;
        padding-top: max(1rem, env(safe-area-inset-top));
        padding-bottom: 0;
        border-right: 0;
        box-shadow: 12px 0 34px rgba(20, 35, 28, .12);
    }

    .member-sidebar.offcanvas-xl {
        width: 292px !important;
    }

    .member-topbar {
        left: 0;
        min-height: 68px;
        padding-inline: clamp(.85rem, 3vw, 1.5rem);
    }
}

@media (max-width: 575.98px) {
    .member-sidebar.offcanvas-xl {
        width: min(86vw, 320px) !important;
    }

    .member-topbar {
        min-height: 62px;
        gap: .5rem;
        padding: .55rem .8rem;
    }

    .topbar-start,
    .topbar-actions {
        gap: .45rem;
    }

    .topbar-context strong {
        max-width: 44vw;
        font-size: .84rem;
    }

    .topbar-kicker {
        font-size: .58rem;
    }
}

@media (min-width: 1200px) {
    .member-sidebar.offcanvas-xl {
        visibility: visible !important;
        transform: none !important;
    }
}
</style>