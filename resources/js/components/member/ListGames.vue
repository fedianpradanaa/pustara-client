<template>
    <div class="page-content game-library-page">
		<header class="game-library-heading">
			<span class="game-library-eyebrow">JEDA &amp; BERMAIN</span>
			<h1>Permainan</h1>
		</header>
		<section class="row game-library-grid" aria-label="Pilih permainan">
				<div class="col-12 col-lg-4 mb-2 mt-2">
					<router-link :to="{ name: 'game-math' }">
						<article class="card h-100 mb-0 hover-shadow game-choice-card">
							<div class="card-content d-flex flex-column">
								<div class="product-image mb-0 pb-0">
									<img src="game/math/math.png" class="img-fluid" alt="Game Perhitungan">
								</div>
								<div class="card-body">
									<h2 class="card-title">Game Perhitungan</h2>
									<span class="game-choice-action">Mainkan <i class="bi bi-arrow-right" aria-hidden="true"></i></span>
								</div>
							</div>
						</article>
					</router-link>
				</div>
				<div class="col-12 col-lg-4 mb-2 mt-2">
					<router-link :to="{ name: 'word-scramble' }">
						<article class="card h-100 mb-0 hover-shadow game-choice-card">
							<div class="card-content d-flex flex-column">
								<div class="product-image mb-0 pb-0">
									<img src="game/scramble/scramble.png" class="img-fluid" alt="Game Susun Kata">
								</div>
								<div class="card-body">
									<h2 class="card-title">Game Susun Kata</h2>
									<span class="game-choice-action">Mainkan <i class="bi bi-arrow-right" aria-hidden="true"></i></span>
								</div>
							</div>
						</article>
					</router-link>
				</div>
				<div class="col-12 col-lg-4 mb-2 mt-2">
					<router-link :to="{ name: 'flying-bird' }">
						<article class="card h-100 mb-0 hover-shadow game-choice-card">
							<div class="card-content d-flex flex-column">
								<div class="product-image mb-0 pb-0">
									<img src="game/flyingbird/flyingbird.png" class="img-fluid" alt="Game Flying Bird">
								</div>
								<div class="card-body">
									<h2 class="card-title">Game Flying Bird</h2>
									<span class="game-choice-action">Mainkan <i class="bi bi-arrow-right" aria-hidden="true"></i></span>
								</div>
							</div>
						</article>
					</router-link>
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

    mounted() {
    },

    methods: {
        
    },

    computed: {
        
    },

    watch: {
        
    }
};
</script>

<style scoped>
    .game-library-heading {
        margin-bottom: 1.25rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--member-line, #e4e8e2);
    }

    .game-library-heading h1 {
        margin: .25rem 0 0;
        font-size: clamp(1.8rem, 4vw, 2.8rem);
    }

    .game-library-eyebrow {
        color: var(--member-accent, #236b58);
        font-size: .72rem;
        font-weight: 800;
        letter-spacing: .12em;
    }

    .game-library-grid {
        --bs-gutter-x: 1.25rem;
        --bs-gutter-y: 1.25rem;
    }

    .game-choice-card .card-body {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .75rem;
    }

    .game-choice-card .card-title {
        margin: 0;
        font-size: 1.2rem;
    }

    .game-choice-action {
        flex: 0 0 auto;
        color: var(--member-accent, #236b58);
        font-weight: 700;
    }

    .game-choice-card {
        overflow: hidden;
        border-radius: 9px;
        transition: transform .18s ease, border-color .18s ease;
    }

    .game-choice-card:hover {
        transform: translateY(-3px);
        border-color: var(--member-accent, #236b58);
        box-shadow: none !important;
    }

    .game-choice-card .product-image {
        height: 220px;
        overflow: hidden;
        background: var(--member-soft, #edf4ef);
    }

    .game-choice-card .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 0 !important;
    }

    .game-choice-card .card-body {
        padding: 1.15rem 1.25rem;
    }

    .game-choice-card .card-title {
        margin: 0;
    }

    .game-choice-card .card-title a {
        color: inherit;
        text-decoration: none;
    }

    .game-choice-card h5 {
        margin: 0;
        font-size: 1.15rem;
    }

    @media (max-width: 575px) {
        .game-choice-card .product-image {
            height: 200px;
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