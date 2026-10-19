<template>
    <div class="page-content article-list-page">
        <header class="article-index-header">
            <span class="article-index-eyebrow">KOLEKSI EDITORIAL</span>
            <h1>{{ idart=='TU' ? 'Tajuk Utama' : (idart=='WA' ? 'Wawasan' : (idart=='FR' ? 'Frasa' : (idart=='RB' ? 'Review Buku' : (idart=='LP' ? 'Layar Penulis' : (idart=='TF' ? 'Titik Fokus' : 'Humoria'))))) }}</h1>
        </header>
        <section class="col-12 article-list-shell" :aria-label="idart=='LP' ? 'Artikel dan profil penulis' : 'Daftar artikel'">
                <div v-if="displayedContents <= 0" class="row article-empty-state">
                    <div class="card h-100">
                        <div class="card-content d-flex flex-column">
                            Tidak Ada Data {{ (idart=='TU' ? 'Tajuk Utama' : (idart=='WA' ? 'Wawasan' : (idart=='FR' ? 'Frasa' : (idart=='RB' ? 'Review Buku' : (idart=='LP' ? 'Layar Penulis' : (idart=='TF' ? 'Titik Fokus' : 'Humoria')))))) }}
                        </div>
                    </div>
                </div>
                <section v-if="idart=='LP'" class="row article-grid author-article-grid">
                    <div v-for="(data, i) in displayedContents" :key="i" class="col-12 col-lg-4 mb-2 mt-2">
                        <router-link :to="{ name: 'detail-artikel', params: { idart: idart, detail: data.id } }">
                            <div class="card h-100 px-2 mb-1 hover-shadow article-card">
                                <div class="d-flex justify-content-center align-items-center flex-column mt-3">
                                    <div class="avatar">
                                        <img :src="data.image" style="height: 90px; width:90px;">
                                    </div>
                                </div>
                                <div class="card-body pb-0">
                                    <h5 data-bs-toggle="tooltip" data-bs-placement="bottom" style="display: -webkit-box; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;" :title="data.title">
                                        {{ data.title }}
                                    </h5>
                                    <p class="m-top-sm m-bottom-sm mt-2" data-bs-toggle="tooltip" data-bs-placement="bottom" style="display: -webkit-box; line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;">
                                        <span v-html="data.content"></span>
                                    </p>
                                    <span class="article-card-continue">Lanjutkan Membaca</span>
                                </div>
                            </div>
                        </router-link>
                    </div>
                </section>
                <section v-else class="row article-grid">
                    <div v-for="(data, i) in displayedContents" :key="i" class="col-12 col-lg-4 mb-2 mt-2">
                        <router-link :to="{ name: 'detail-artikel', params: { idart: idart, detail: data.id } }">
                            <div class="card h-100 mb-0 hover-shadow article-card">
                                <div class="card-content d-flex flex-column">
                                    <div class="product-image mb-0 pb-0">
                                        <img :src="data.image" class="img-fluid" style="border-radius: 5px 5px 0 0 !important;">
                                    </div>
                                    <div class="card-body">
                                        <div class="card-title">
                                            <h5 data-bs-toggle="tooltip" data-bs-placement="bottom" style="display: -webkit-box; line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;" :title="data.title">
                                                {{ data.title }}
                                            </h5>
                                        </div>
                                        <div v-if="idart=='TU' || idart=='RB'">
                                            <small class="text-muted">Oleh <strong>{{ data.author }}</strong> | {{ data.published_at }}</small>
                                        </div>
                                        <p class="m-top-sm m-bottom-sm mt-2" data-bs-toggle="tooltip" data-bs-placement="bottom" style="display: -webkit-box; line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;">
                                            <span v-html="data.content"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </router-link>
                    </div>
                </section>
                <div v-if="showLoadMore" class="divider">
                    <div class="divider-text bg-transparent">
                        <button class="btn btn-secondary mt-3 rounded-pill" @click="loadMore">Load More</button>
                    </div>
                </div>
        </section>
    </div>
</template>

<script>

export default {
    props: {
        isAuthenticated: {
            type: Boolean,
            required: true
        }
    },

    data() {
        return {
            idart: '',
            displayedCount: 6,
            articles: [],
        };
    },

    mounted() {
        this.idart = this.$route.params.idart;
        this.getArtikel();
    },

    methods: {
        async getArtikel() {
            try {
                let loader = this.$loading.show();
                axios.get('/getArticle', {
                    params:{
                        id: this.idart
                    }
                })
                .then((response) => {
                    this.articles = response.data;
                    loader.hide();
                })
                .catch((e) => {
                    loader.hide();
                    console.error(e);
                });
            } catch (e) {
                loader.hide();
                console.error(e);
            }
        },

        async refreshData() {
            this.idart = this.$route.params.idart;
            await this.getArtikel();
        },

        loadMore() {
            this.displayedCount += 6;
        }
    },

    computed: {
        displayedContents() {
            return this.articles.slice(0, this.displayedCount);
        },

        showLoadMore() {
            return this.articles.length > this.displayedCount;
        }
    },

    watch: {
        '$route.params.idart': 'refreshData'
    }
};
</script>

<style scoped>
.article-index-header {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    margin: 0 auto 1.25rem;
    padding: clamp(1.1rem, 3vw, 2rem) 0;
    border-bottom: 1px solid var(--member-line, #e4e8e2);
}

.article-index-header h1 {
    margin: .35rem 0 0;
    font-size: clamp(1.8rem, 4vw, 2.8rem);
    letter-spacing: -.035em;
}

.article-index-eyebrow {
    color: var(--member-accent, #236b58);
    font-size: .72rem;
    font-weight: 800;
    letter-spacing: .12em;
}

    .article-list-page .article-list-shell {
        padding: clamp(1rem, 2.5vw, 2rem);
        background: var(--member-surface, #fff);
        border: 1px solid var(--member-line, #e4e8e2);
    }

    .article-grid {
        --bs-gutter-x: 1.25rem;
        --bs-gutter-y: 1.25rem;
    }

    .article-card {
        overflow: hidden;
        border-radius: 8px;
        transition: transform .18s ease, border-color .18s ease;
    }

    .article-card:hover {
        transform: translateY(-3px);
        border-color: var(--member-accent, #236b58) !important;
        box-shadow: none !important;
    }

    .article-card-continue {
        display: inline-block;
        margin-top: .25rem;
        color: var(--member-accent, #236b58);
        font-weight: 700;
    }

    .article-card .product-image {
        min-height: 230px;
        background: var(--member-soft, #edf4ef);
    }

    .article-card .product-image img {
        width: 100%;
        max-height: 250px;
        object-fit: cover;
    }

    .article-card .card-body {
        display: flex;
        flex: 1;
        flex-direction: column;
        align-items: flex-start;
        padding: 1.2rem;
    }

    .article-card .card-body > p {
        color: var(--member-muted, #64716c);
        line-height: 1.65;
    }

    .article-card .card-body > a:last-child {
        margin-top: auto;
    }

    .author-article-grid .article-card {
        padding: 1rem !important;
        border-top: 2px solid var(--member-accent, #236b58);
    }

    .author-article-grid .article-card .card-body {
        padding: 1rem .35rem .3rem;
    }

    .author-article-grid .avatar img {
        width: 88px;
        height: 88px;
        object-fit: cover;
        border: 3px solid var(--member-soft, #edf4ef);
        border-radius: 50%;
    }

    .article-empty .card {
        display: grid;
        min-height: 220px;
        place-items: center;
        padding: 2rem;
        color: var(--member-muted, #64716c);
        text-align: center;
    }

    @media (max-width: 575px) {
        .article-list-page .article-list-shell {
            padding: .8rem;
        }

        .article-card .product-image {
            min-height: 190px;
        }
    }

    .hover-scale {
        transition: all .05s ease-out;
        position: relative;
        transform-origin: center center;
    }
    .hover-scale:hover {
        transition: all .1s;
        transform: scale(1.025) !important;
    }
    .hover-shadow {
        transition: all .05s ease-out;
        box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075) !important;
    }
    .hover-shadow:hover {
        transition: all .1s;
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
    }
    .hover-border {
        transition: all .05s ease-out;
        border: 2px solid transparent;
    }
    .hover-border:hover {
        transition: all .1s;
        border-color: #00afff
    }
    .hover-pointer:hover {
        cursor: pointer !important;
    }

    .hover-child-scale > .hover-target {
        transition: all .05s ease-out;
        position: relative;
        transform-origin: center center;
    }
    .hover-child-scale:hover > .hover-target {
        transition: all .1s;
        transform: scale(1.025) !important;
    }
    .hover-child-shadow > .hover-target {
        transition: all .05s ease-out;
        box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075) !important;
    }
    .hover-child-shadow:hover > .hover-target {
        transition: all .1s;
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
    }
    .hover-child-border > .hover-target {
        transition: all .05s ease-out;
        border: 2px solid transparent;
    }
</style>