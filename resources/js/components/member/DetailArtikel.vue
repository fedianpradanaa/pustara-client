<template>
    <div class="page-content article-detail-page">
        <div class="col-12 article-detail-shell">
            <article class="card h-100 article-detail-card">
                <div v-if="article.length != 0" class="card-content article-content-layout">
                    <figure class="article-detail-cover">
                        <img :src="article[0].image" :alt="article[0].title" class="img-fluid">
                    </figure>
                    <div class="card-body article-detail-copy">
                        <header class="article-prose-header">
                            <h1>
                                {{ article[0].title }}
                            </h1>
                            <p v-if="idart=='TU' || idart=='RB'" class="article-byline">
                                Oleh <strong>{{ article[0].author }}</strong><span aria-hidden="true"> · </span>{{ article[0].published_at }}
                            </p>
                        </header>
                        <div class="article-prose" v-html="article[0].content"></div>
                    </div>
                </div>
            </article>
        </div>
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
            iddetail: '',
            article: [],
        };
    },

    mounted() {
        this.idart      = this.$route.params.idart;
        this.iddetail   = this.$route.params.detail;
        this.getDetailArtikel();
        this.ReadFitur();
    },

    methods: {
		ReadFitur(){
			try {
                let loader = this.$loading.show();
                axios.post('/api/ReadFitur', {
					category: this.idart,
					id: this.iddetail
                })
                .then((response) => {
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

        async getDetailArtikel() {
            try {
                let loader = this.$loading.show();
                axios.get('/getDetailArticle', {
                    params:{
                        category: this.idart,
                        id: this.iddetail
                    }
                })
                .then((response) => {
                    this.article = response.data;
                    
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
            this.idart      = this.$route.params.idart;
            this.iddetail   = this.$route.params.detail;
            await this.getDetailArtikel();
        }
    },

    computed: {

    },

    watch: {
        '$route.params.detail': 'refreshData'
    }
};
</script>

<style scoped>
.article-detail-shell {
    max-width: 1040px;
    margin: 0 auto;
}

.article-detail-card {
    overflow: hidden;
    border-radius: 10px;
}

.article-detail-cover {
    display: grid;
    min-height: 220px;
    place-items: center;
    overflow: hidden;
    background: var(--member-soft, #edf4ef);
}

.article-detail-cover img {
    display: block;
    width: 100%;
    max-height: min(72vh, 680px);
    object-fit: contain;
}

.article-detail-cover {
    margin: 0;
}

.article-prose-header {
    max-width: 72ch;
    margin: 0 auto 2rem;
    padding-bottom: 1.25rem;
    border-bottom: 1px solid var(--member-line, #e4e8e2);
}

.article-prose-header h1 {
    margin: 0;
    font-size: clamp(1.7rem, 4vw, 2.8rem);
    line-height: 1.15;
    letter-spacing: -.03em;
}

.article-byline {
    margin: .8rem 0 0;
    color: var(--member-muted, #64716c);
}

.article-prose {
    max-width: 72ch;
    margin: 0 auto;
    line-height: 1.8;
}

.article-detail-copy {
    width: min(100%, 820px);
    margin: 0 auto;
    padding: clamp(1.25rem, 4vw, 3rem);
    font-size: 1rem;
    line-height: 1.85;
}

.article-detail-copy .card-title {
    margin-bottom: 1.5rem !important;
    padding-bottom: 1.2rem;
    border-bottom: 1px solid var(--member-line, #e4e8e2);
}

.article-detail-copy .card-title h5 {
    margin-bottom: .75rem;
    font-size: clamp(1.6rem, 4vw, 2.4rem);
    line-height: 1.2;
}

.article-detail-copy :deep(img) {
    max-width: 100%;
    height: auto;
}

@media (max-width: 575px) {
    .article-detail-cover {
        min-height: 160px;
    }
}
</style>