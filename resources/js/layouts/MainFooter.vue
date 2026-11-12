<template>
    <footer class="site-footer">
        <div class="site-footer__inner">
            <section class="site-footer__brand">
                <img v-if="siteInfo.logo_url" :src="siteInfo.logo_url" :alt="siteInfo.app_name" class="site-footer__logo">
                <div>
                    <strong>{{ siteInfo.app_name }}</strong>
                    <p v-if="siteInfo.tagline">{{ siteInfo.tagline }}</p>
                </div>
            </section>

            <section class="site-footer__contact" aria-labelledby="footer-contact-title">
                <h2 id="footer-contact-title">Informasi Perpustakaan</h2>
                <strong v-if="siteInfo.institution_name">{{ siteInfo.institution_name }}</strong>
                <p v-if="siteInfo.address">
                    <i class="bi bi-geo-alt-fill" aria-hidden="true"></i>
                    <span>{{ siteInfo.address }}</span>
                </p>
                <a v-if="siteInfo.phone" :href="`tel:${siteInfo.phone_url}`">
                    <i class="bi bi-telephone-fill" aria-hidden="true"></i>
                    <span>{{ siteInfo.phone }}</span>
                </a>
                <a v-if="siteInfo.website" :href="siteInfo.website" target="_blank" rel="noopener noreferrer">
                    <i class="bi bi-globe2" aria-hidden="true"></i>
                    <span>{{ siteInfo.website }}.pustara.id</span>
                </a>
            </section>

            <section v-if="siteInfo.social_links && siteInfo.social_links.length" class="site-footer__social" aria-labelledby="footer-social-title">
                <h2 id="footer-social-title">Media Sosial</h2>
                <div class="site-footer__social-links">
                    <a
                        v-for="social in siteInfo.social_links"
                        :key="social.label"
                        :href="social.url"
                        :aria-label="social.label"
                        :title="social.label"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <i class="bi" :class="social.icon" aria-hidden="true"></i>
                    </a>
                </div>
            </section>
        </div>

        <div class="site-footer__bottom">
            <span>&copy; {{ currentYear }} {{ siteInfo.app_name }}</span>
        </div>
    </footer>
</template>

<script>
export default {
    name: 'footerItems',

    props: {
        siteInfo: {
            type: Object,
            required: true,
        },
    },

    computed: {
        currentYear() {
            return new Date().getFullYear();
        },
    },
};
</script>

<style scoped>
.site-footer {
    width: 100%;
    padding: 1.5rem clamp(1.25rem, 3vw, 3rem) 1rem;
    color: var(--pustara-text);
    background: linear-gradient(145deg, var(--pustara-surface), var(--pustara-ice));
    border-top: 1px solid var(--pustara-border);
}

.site-footer__inner,
.site-footer__bottom {
    width: 100%;
    max-width: 1500px;
    margin-inline: auto;
}

.site-footer__inner {
    display: grid;
    grid-template-columns: minmax(0, 1.2fr) minmax(0, 1.5fr) minmax(150px, .7fr);
    gap: clamp(1.5rem, 4vw, 3.5rem);
    padding: .5rem 0 1.5rem;
}

.site-footer__brand {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: .75rem;
}

.site-footer__logo {
    width: 180px;
    height: 60px;
    object-fit: contain;
    object-position: left center;
    flex-shrink: 0;
}

.site-footer__brand strong {
    display: block;
    color: var(--pustara-primary);
    font-size: 1.05rem;
}

.site-footer__brand p {
    margin: .35rem 0 0;
    color: var(--pustara-muted);
    line-height: 1.6;
}

.site-footer h2 {
    margin: 0 0 .85rem;
    color: var(--pustara-primary);
    font-size: .92rem;
    font-weight: 800;
}

.site-footer__contact {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: .5rem;
}

.site-footer__contact h2 {
    margin-bottom: .15rem;
}

.site-footer__contact strong {
    font-size: .9rem;
}

.site-footer__contact p,
.site-footer__contact a {
    display: flex;
    align-items: flex-start;
    gap: .55rem;
    margin: 0;
    color: var(--pustara-muted);
    font-size: .86rem;
    line-height: 1.55;
    text-decoration: none;
    overflow-wrap: anywhere;
}

.site-footer__contact i {
    flex: 0 0 auto;
    margin-top: .12rem;
    color: var(--pustara-turquoise-strong);
}

.site-footer__contact a:hover {
    color: var(--pustara-primary);
}

.site-footer__social-links {
    display: flex;
    flex-wrap: wrap;
    gap: .55rem;
}

.site-footer__social-links a {
    display: grid;
    width: 40px;
    height: 40px;
    place-items: center;
    color: var(--pustara-primary);
    background: var(--pustara-surface);
    border: 1px solid var(--pustara-border);
    border-radius: 50%;
    text-decoration: none;
    transition: color .18s ease, background-color .18s ease, border-color .18s ease, transform .18s ease;
}

.site-footer__social-links a:hover {
    color: var(--pustara-button-text);
    background: var(--pustara-button);
    border-color: var(--pustara-button);
    transform: translateY(-2px);
}

.site-footer__social-links i {
    font-size: 1.05rem;
}

.site-footer__bottom {
    padding-top: .85rem;
    color: var(--pustara-muted);
    border-top: 1px solid var(--pustara-border);
    font-size: .78rem;
}

@media (max-width: 767px) {
    .site-footer {
        padding-inline: 1rem;
    }

    .site-footer__inner {
        grid-template-columns: minmax(0, 1fr);
        gap: 1.35rem;
    }
}
</style>
