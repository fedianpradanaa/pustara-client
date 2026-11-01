<template>
    <div class="page-content catalog-page catalog-books">
        <section class="catalog-shell">
            <aside class="catalog-sidebar">
                <div class="catalog-sidebar-header">
                    <span class="eyebrow">Filter</span>
                    <h2>Koleksi</h2>
                </div>

                <div class="catalog-summary">
                    <span class="summary-label">Katalog</span>
                    <strong>{{ totalBuku }} judul</strong>
                </div>

                <div class="catalog-filter-panel">
                    <div class="filter-header">
                        <span>Kategori</span>
                        <button type="button" class="btn btn-link btn-sm" @click="selectedCategories = []; filterBooks();">
                            Reset
                        </button>
                    </div>

                    <div class="filter-list">
                        <label v-for="cat in category" :key="cat.id" class="check-item">
                            <input type="checkbox" :id="cat.id" :value="cat.id" v-model="selectedCategories" @change="filterBooks">
                            <span>{{ cat.description }}</span>
                            <em>{{ cat.total }}</em>
                        </label>
                    </div>
                </div>

                <div v-if="seeks" class="catalog-search-pill">
                    Hasil pencarian: <strong>"{{ seeks }}"</strong>
                </div>
            </aside>

            <div class="catalog-main-panel">
                <div class="catalog-heading">
                    <div class="catalog-heading-copy">
                        <span class="catalog-eyebrow">JELAJAHI PERPUSTAKAAN</span>
                        <h1>Koleksi Buku</h1>
                        <p>Temukan bacaan yang paling sesuai untuk kebutuhanmu hari ini.</p>
                    </div>
                </div>

                <template v-if="paginatedBuku.length > 0">
                    <div class="catalog-grid-toolbar">
                        <span>{{ totalBuku }} judul tersedia</span>
                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Kategori
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end catalog-menu">
                            <li v-for="cat in category" :key="cat.id" class="dropdown-item px-3 py-2">
                                <label class="d-flex align-items-center gap-2 w-100 mb-0">
                                    <input type="checkbox" :id="cat.id + '-mobile'" :value="cat.id" v-model="selectedCategories" @change="filterBooks">
                                    <span>{{ cat.description }}</span>
                                    <em class="ms-auto">{{ cat.total }}</em>
                                </label>
                            </li>
                        </ul>
                    </div>

                    <div class="row row-cols-2 row-cols-md-3 row-cols-xl-5 catalog-grid">
                        <template v-for="(group, groupIndex) in paginatedBuku" :key="groupIndex">
                            <div v-for="buku in group" :key="buku.id" class="col mb-4 catalog-item">
                                <router-link :to="{ name: 'detail-buku', params: { idb: buku.isbn } }">
                                    <div class="card h-100 catalog-card">
                                        <div class="card-content">
                                            <div class="product-image catalog-cover">
                                                <img :src="buku.image.replace('&amp;', '&')" :alt="buku.alt" class="img-fluid">
                                            </div>
                                            <div class="card-body pt-0 catalog-book-copy">
                                                <p class="card-meta">{{ buku.writer }}</p>
                                                <h6 class="card-title" :title="buku.title">{{ buku.title }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </router-link>
                            </div>
                        </template>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <nav aria-label="Page navigation" class="catalog-pagination-wrap">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item" :class="{ disabled: currentPage === 1 }">
                                        <a class="page-link" href="#" @click.prevent="previousPage">Sebelumnya</a>
                                    </li>

                                    <li v-if="currentPage > 3" class="page-item">
                                        <a class="page-link" href="#" @click.prevent="goToPage(1)">1</a>
                                    </li>
                                    <li v-if="currentPage > 4" class="page-item disabled"><span class="page-link">...</span></li>

                                    <li v-for="page in visiblePages" :key="page" :class="{ active: currentPage === page }" class="page-item">
                                        <a class="page-link" href="#" @click.prevent="goToPage(page)">{{ page }}</a>
                                    </li>

                                    <li v-if="currentPage < totalPages - 3" class="page-item disabled"><span class="page-link">...</span></li>
                                    <li v-if="currentPage < totalPages - 2" class="page-item">
                                        <a class="page-link" href="#" @click.prevent="goToPage(totalPages)">{{ totalPages }}</a>
                                    </li>

                                    <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                                        <a class="page-link" href="#" @click.prevent="nextPage">Selanjutnya</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </template>

                <template v-else>
                    <div class="catalog-empty">
                        <span class="catalog-empty-mark"><i class="bi bi-journal-bookmark"></i></span>
                        <h2>{{ seeks ? 'Buku tidak ditemukan' : 'Koleksi belum tersedia' }}</h2>
                        <p>{{ seeks ? 'Coba kata kunci atau kategori yang berbeda.' : 'Belum ada buku yang dapat ditampilkan saat ini.' }}</p>
                    </div>
                </template>
            </div>
        </section>
    </div>
</template>

<script>
export default {
    data(){
        return {
            buku: [],
            category: [],
            seeks: '',
            selectedCategories: [], 
            currentPage: 1,
            itemsPerPage: 18
        }
    },

    mounted(){
        this.filterBooks();
        this.getCategory();
    },

    methods: {
        filterBooks() {
            let loader = this.$loading.show();
            window.axios.get('/getBook', {
                params: {
                    categories: this.selectedCategories,
                    search: this.searchQuery
                }
            })
            .then((response) => {
                this.seeks = this.searchQuery;
                this.buku = response.data;
                
                this.$router.push({ 
                    path: this.$route.path, 
                    query: {} 
                });

                loader.hide();
            })
            .catch((e) => {
                loader.hide();
                console.error(e)
            });
        },
        
        getCategory() {
            window.axios
            .get('/getCategory')
            .then((response) => {
                this.category = response.data;
            })
            .catch((e) => {
                console.error(e)
            });
        },

        goToPage(page) {
            this.currentPage = page;
            // Lakukan sesuatu dengan halaman baru, seperti mengupdate data atau memanggil API
        },
        // Navigasi ke halaman sebelumnya
        previousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                // Lakukan sesuatu setelah halaman sebelumnya dipilih
            }
        },
        // Navigasi ke halaman berikutnya
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
                // Lakukan sesuatu setelah halaman berikutnya dipilih
            }
        }
    },

    computed:{
        searchQuery() {
            return this.$route.query.search || '';
        },

        totalBuku() {
            return this.buku.length;
        },

        totalPages() {
            return Math.ceil(this.totalBuku / this.itemsPerPage);
        },

        paginatedBuku() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            const currentItems = this.buku.slice(start, end);

            const chunkSize = 4;
            const grouped = [];

            for (let i = 0; i < currentItems.length; i += chunkSize) {
                grouped.push(currentItems.slice(i, i + chunkSize));
            }

            return grouped;
        },

        visiblePages() {
            const range = 2;  // Menampilkan 2 halaman di sebelah kiri dan kanan halaman aktif
            let start = Math.max(1, this.currentPage - range);
            let end = Math.min(this.totalPages, this.currentPage + range);

            const pages = [];
            for (let i = start; i <= end; i++) {
                pages.push(i);
            }
            return pages;
        }
    },

    watch: {
        searchQuery(newValue, oldValue) {
            if (newValue !== '') {
                this.selectedCategories = [];
                this.filterBooks();
            }
        }
    }
}
</script>

<style scoped>
.catalog-page {
    padding-top: .75rem;
}

.catalog-shell {
    display: grid;
    grid-template-columns: minmax(240px, 290px) minmax(0, 1fr);
    gap: 1.5rem;
    align-items: start;
}

.catalog-sidebar,
.catalog-main-panel {
    background: var(--member-surface, #fff);
    border: 1px solid var(--member-line, #e4e8e2);
    border-radius: 18px;
}

.catalog-sidebar {
    position: sticky;
    top: 90px;
    padding: 1.25rem;
}

.catalog-sidebar-header {
    margin-bottom: 1rem;
    padding-bottom: .9rem;
    border-bottom: 1px solid var(--member-line, #e4e8e2);
}

.catalog-sidebar-header .eyebrow,
.catalog-eyebrow {
    display: inline-block;
    color: var(--member-accent, #236b58);
    font-size: .72rem;
    font-weight: 800;
    letter-spacing: .12em;
    text-transform: uppercase;
}

.catalog-sidebar-header h2 {
    margin: .35rem 0 0;
    font-size: 1.5rem;
}

.catalog-summary {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .75rem;
    margin-bottom: 1rem;
    padding: .8rem .9rem;
    background: var(--member-soft, #edf4ef);
    border-radius: 12px;
}

.catalog-summary .summary-label {
    color: var(--member-muted, #64716c);
    font-size: .72rem;
    letter-spacing: .08em;
    text-transform: uppercase;
}

.catalog-summary strong {
    font-size: 1rem;
}

.catalog-filter-panel {
    padding: .15rem 0 0;
}

.filter-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: .75rem;
    font-weight: 700;
}

.filter-header .btn-link {
    padding: 0;
    color: var(--member-accent, #236b58);
    text-decoration: none;
}

.filter-list {
    display: grid;
    gap: .6rem;
}

.check-item {
    display: grid;
    grid-template-columns: 18px minmax(0, 1fr) auto;
    align-items: center;
    gap: .55rem;
    width: 100%;
    padding: .7rem .8rem;
    border: 1px solid var(--member-line, #e4e8e2);
    border-radius: 10px;
    background: var(--member-surface, #fff);
    cursor: pointer;
}

.check-item input {
    margin: 0;
    accent-color: var(--member-accent, #236b58);
}

.check-item span {
    color: var(--member-ink, #1d2b29);
}

.check-item em {
    color: var(--member-muted, #64716c);
    font-style: normal;
    font-size: .8rem;
}

.catalog-search-pill {
    margin-top: 1.1rem;
    padding: .8rem .9rem;
    border-radius: 10px;
    background: rgba(35, 107, 88, .08);
    color: var(--member-ink, #1d2b29);
    line-height: 1.5;
}

.catalog-main-panel {
    padding: 1.15rem;
}

.catalog-heading {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 1rem;
    margin: 0 0 1.25rem;
    padding: clamp(1.15rem, 2vw, 1.8rem);
    border-left: 4px solid var(--member-accent, #236b58);
    background: linear-gradient(135deg, rgba(35, 107, 88, .06), rgba(35, 107, 88, .02));
    border-radius: 16px;
}

.catalog-heading-copy {
    min-width: 0;
}

.catalog-page .catalog-heading h1 {
    margin: .3rem 0 0;
    font-size: clamp(1.9rem, 4vw, 3rem);
    line-height: 1.1;
}

.catalog-heading p {
    margin: .6rem 0 0;
    color: var(--member-muted, #64716c);
}

.catalog-grid-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .75rem;
    margin-bottom: 1rem;
    padding: .8rem 0;
}

.catalog-grid-toolbar > span {
    color: var(--member-muted, #64716c);
    font-size: .9rem;
}

.catalog-grid-toolbar .dropdown-toggle {
    min-width: 130px;
}

.catalog-menu {
    max-height: 360px;
    overflow-y: auto;
}

.catalog-menu label {
    width: 100%;
    margin: 0;
    font-size: .92rem;
}

.catalog-page .catalog-grid {
    --bs-gutter-x: 1rem;
    --bs-gutter-y: 1rem;
}

.catalog-page .catalog-card {
    overflow: hidden;
    border: 1px solid var(--member-line, #e4e8e2);
    border-radius: 16px;
    transition: transform .18s ease, border-color .18s ease, box-shadow .18s ease;
}

.catalog-page .catalog-card:hover {
    transform: translateY(-4px);
    border-color: var(--member-accent, #236b58);
    box-shadow: 0 12px 26px rgba(15, 28, 24, .08);
}

.catalog-page .catalog-cover {
    min-height: 238px;
    margin: .75rem .75rem 0;
    background: linear-gradient(135deg, #f2f5f1, #e7efe9);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.catalog-page .catalog-cover img {
    width: auto;
    max-width: 100%;
    height: 210px;
    object-fit: cover;
}

.catalog-page .catalog-book-copy {
    padding: .85rem .85rem 1rem;
}

.catalog-page .card-meta {
    margin: 0;
    color: var(--member-muted, #64716c);
    font-size: .76rem;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
    overflow: hidden;
}

.catalog-page .catalog-book-copy .card-title {
    margin: .45rem 0 0;
    min-height: 2.8em;
    font-size: .96rem;
    line-height: 1.4;
    color: var(--member-ink, #1d2b29);
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
    overflow: hidden;
}

.catalog-pagination-wrap {
    margin-top: 1rem;
}

.catalog-pagination-wrap .pagination {
    gap: .3rem;
}

.catalog-pagination-wrap .page-item .page-link {
    border-radius: 999px;
    min-width: 42px;
    height: 42px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: var(--member-ink, #1d2b29);
    border: 1px solid var(--member-line, #e4e8e2);
}

.catalog-pagination-wrap .page-item.active .page-link {
    background: var(--member-button, #236b58);
    border-color: var(--member-button, #236b58);
    color: #fff;
}

.catalog-empty {
    display: grid;
    justify-items: center;
    gap: .65rem;
    min-height: 280px;
    align-content: center;
    padding: 2rem;
    border: 1px dashed var(--member-line, #e4e8e2);
    background: var(--member-surface, #fff);
    border-radius: 16px;
    text-align: center;
}

.catalog-empty-mark {
    display: grid;
    width: 56px;
    height: 56px;
    place-items: center;
    color: var(--member-accent, #236b58);
    background: var(--member-soft, #edf4ef);
    border-radius: 50%;
    font-size: 1.4rem;
}

.catalog-empty h2 {
    margin: .25rem 0 0;
    font-size: 1.25rem;
}

.catalog-empty p {
    margin: 0;
    color: var(--member-muted, #64716c);
}

@media (max-width: 991px) {
    .catalog-shell {
        grid-template-columns: 1fr;
    }

    .catalog-sidebar {
        position: static;
    }
}

@media (max-width: 575px) {
    .catalog-main-panel,
    .catalog-sidebar {
        padding: 1rem;
    }

    .filter-list {
        max-height: 14rem;
        overflow-y: auto;
        overscroll-behavior: contain;
        padding-right: .25rem;
    }

    .catalog-heading {
        align-items: flex-start;
        flex-direction: column;
        padding: 1.05rem;
    }

    .catalog-page .catalog-cover {
        min-height: 180px;
        margin: .55rem .55rem 0;
    }

    .catalog-page .catalog-cover img {
        height: 165px;
    }

    .catalog-grid-toolbar {
        flex-wrap: wrap;
    }
}
</style>
