<template>
    <div class="page-content catalog-page catalog-video">
        <section class="row">
            <div class="catalog-heading">
                <div class="catalog-heading-copy">
                    <span class="catalog-eyebrow">TONTON KOLEKSI</span>
                    <h1>Koleksi Video Buku</h1>
                    <p>Jelajahi buku yang tersedia dalam format video.</p>
                </div>
                <div v-if="seeks">
                    <p class="catalog-search-summary">Hasil pencarian untuk <b>"{{ seeks }}"</b></p>
                </div>
            </div>
            <!-- Dropdown button to toggle filter options -->
			<div class="col-12 catalog-toolbar mb-3">
				<div class="btn-group">
					<button type="button" class="btn btn-primary dropdown-toggle px-5 py-2" data-bs-toggle="dropdown" aria-expanded="false" data-bs-offset="0,15">
						Kategori
					</button>
					<ul class="dropdown-menu">
						<li v-for="cat in category" :key="cat.id" class="dropdown-item py-3">
							<input type="checkbox" :id="cat.id" class="form-check-input me-2" :value="cat.id" v-model="selectedCategories" @change="filterBooks">
							<label :for="cat.id">
								<span class="text-start">{{ cat.description }}</span>
								<span class="text-muted text-end">
									( {{ cat.total }} )
								</span>
							</label>
						</li>
					</ul>
				</div>
			</div>
            <div class="col-12">
                <template v-if="paginatedBuku.length > 0">
                    <div class="row row-cols-2 row-cols-md-3 row-cols-xl-6 catalog-grid">
                        <template v-for="(group, groupIndex) in paginatedBuku" :key="groupIndex">
                            <div v-for="buku in group" :key="buku.id" class="col mb-4 catalog-item">
                                <router-link :to="{ name: 'detail-buku', params: { idb: buku.isbn } }">
                                    <div class="card h-100 catalog-card">
                                        <div class="card-content">
                                            <div class="product-image catalog-cover">
                                                <img :src="buku.image.replace('&amp;', '&')" :alt="buku.alt" class="img-fluid" style="width: 150px; height: 220px;">
                                            </div>
                                            <div class="card-body pt-0 catalog-book-copy">
                                                <p class="card-title mb-0">
                                                    <div data-bs-toggle="tooltip" data-bs-placement="bottom" style="display: -webkit-box; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;" :title="buku.writer">{{ buku.writer }}</div>
                                                </p>
                                                <h6 class="card-title mt-2" data-bs-toggle="tooltip" data-bs-placement="bottom" style="display: -webkit-box; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;" :title="buku.title">{{ buku.title }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </router-link>
                            </div>
                        </template>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center">
                                    <!-- Tombol Previous -->
                                    <li class="page-item" :class="{ disabled: currentPage === 1 }">
                                        <a class="page-link" href="#" @click.prevent="previousPage">Sebelumnya</a>
                                    </li>

                                    <!-- Tombol halaman pertama (1) -->
                                    <li v-if="currentPage > 3" class="page-item">
                                        <a class="page-link" href="#" @click.prevent="goToPage(1)">1</a>
                                    </li>

                                    <!-- Tombol titik-titik ("...") jika ada halaman yang terlewat sebelum halaman aktif -->
                                    <li v-if="currentPage > 4" class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>

                                    <!-- Halaman sekitar halaman aktif (misalnya 2 halaman sebelumnya dan 2 halaman setelahnya) -->
                                    <li v-for="page in visiblePages" :key="page" :class="{ active: currentPage === page }" class="page-item">
                                        <a class="page-link" href="#" @click.prevent="goToPage(page)">{{ page }}</a>
                                    </li>

                                    <!-- Tombol titik-titik ("...") jika ada halaman yang terlewat setelah halaman aktif -->
                                    <li v-if="currentPage < totalPages - 3" class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>

                                    <!-- Tombol halaman terakhir -->
                                    <li v-if="currentPage < totalPages - 2" class="page-item">
                                        <a class="page-link" href="#" @click.prevent="goToPage(totalPages)">{{ totalPages }}</a>
                                    </li>

                                    <!-- Tombol Next -->
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
                        <span class="catalog-empty-mark"><i class="bi bi-play-btn"></i></span>
                        <h2>{{ seeks ? 'Buku video tidak ditemukan' : 'Koleksi video belum tersedia' }}</h2>
                        <p>{{ seeks ? 'Coba kata kunci atau kategori yang berbeda.' : 'Belum ada buku video yang dapat ditampilkan saat ini.' }}</p>
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
            window.axios.get('/getAudioBook', {
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
    .catalog-page .catalog-heading {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 1rem;
        margin: 0 0 1.5rem;
        padding: clamp(1.25rem, 3vw, 2.25rem);
        border-left: 4px solid var(--member-accent, #236b58);
        background: var(--member-soft, #edf4ef);
    }

    .catalog-heading-copy { min-width: 0; }
    .catalog-eyebrow {
        display: block;
        margin-bottom: .45rem;
        color: var(--member-accent, #236b58);
        font-size: .72rem;
        font-weight: 800;
        letter-spacing: .14em;
    }
    .catalog-page .catalog-heading h1 {
        margin: 0;
        font-size: clamp(1.6rem, 4vw, 2.5rem);
    }
    .catalog-heading p {
        margin: .5rem 0 0;
        color: var(--member-muted, #64716c);
    }
    .catalog-search-summary {
        max-width: 320px;
        margin: 0 !important;
        padding: .7rem .9rem;
        border: 1px solid var(--member-line, #e4e8e2);
        background: var(--member-surface, #fff);
        color: var(--member-muted, #64716c);
        font-size: .9rem;
    }
    .catalog-toolbar { display: flex; align-items: center; justify-content: flex-end; }
    .catalog-page .catalog-grid { --bs-gutter-x: 1.1rem; --bs-gutter-y: .35rem; }
    .catalog-page .catalog-card {
        overflow: hidden;
        border-radius: 9px;
        transition: transform .18s ease, border-color .18s ease;
    }
    .catalog-page .catalog-card:hover {
        transform: translateY(-3px);
        border-color: var(--member-accent, #236b58);
    }
    .catalog-page .catalog-cover {
        min-height: 244px;
        margin: .65rem .65rem 0;
        background: linear-gradient(135deg, #f2f2ed, #e9eee8);
    }
    .catalog-page .catalog-cover img { max-height: 216px; }
    .catalog-page .catalog-book-copy { padding: .85rem .8rem 1rem; }
    .catalog-page .catalog-book-copy p {
        color: var(--member-muted, #64716c);
        font-size: .82rem;
        line-height: 1.4;
    }
    .catalog-page .catalog-book-copy h6 {
        min-height: 2.8em;
        margin: .45rem 0 0;
        font-size: .94rem;
        line-height: 1.4;
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
    .catalog-empty h2 { margin: .25rem 0 0; font-size: 1.25rem; }
    .catalog-empty p { margin: 0; color: var(--member-muted, #64716c); }
    @media (max-width: 575px) {
        .catalog-page .catalog-heading { align-items: flex-start; flex-direction: column; padding: 1.1rem; }
        .catalog-page .catalog-cover { min-height: 170px; margin: .45rem .45rem 0; }
        .catalog-page .catalog-cover img { max-height: 155px; }
        .catalog-page .catalog-book-copy { padding: .65rem; }
    }

    .product-card {
        width: 220px;
        border-radius: 10px;
        -webkit-box-shadow: 0px 0px 47px -20px rgba(0,0,0,1);
        -moz-box-shadow: 0px 0px 47px -20px rgba(0,0,0,1);
        box-shadow: 0px 0px 47px -20px rgba(0,0,0,1);
        margin: 60px 0;
        background-color: #fff;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .product-image {
        height: 250px;
        overflow: hidden;
        position: relative;
        padding: 0px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .product-image img {
        width: 75%;
        object-fit: cover;
        transition: transform 0.5s;
        border-radius: 10px;
    }

    .product-image:hover img {
        transform: scale(1.1);
    }

    /* Media Queries untuk Mobile View */
    @media (max-width: 1200px) {
        .product-image {
            height: auto;
            padding: 10px;
        }

        .product-image img {
            width: 70%;
        }
    }

	.dropdown-menu {
        max-height: 350px; /* Limit height */
        overflow-y: auto;  /* Enable vertical scrolling */
    }

    .dropdown-item {
        display: flex;
        align-items: center;
    }

    .form-check-input {
        margin-right: 5px; /* Adjust checkbox spacing */
    }
</style>
