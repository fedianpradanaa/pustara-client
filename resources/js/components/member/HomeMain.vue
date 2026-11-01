<template>
    <div class="page-heading mb-0 pb-0 member-home">
        <template v-if="banner.length>0">
            <div class="col-12 col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div id="webCarousel" class="carousel slide d-none d-sm-block" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div v-for="(item, index) in banner.filter(item => item.display === 'web')" :key="index" :class="['carousel-item', { 'active': index === 0 }]">
                                    <img :src="item.image" class="d-block w-100" :alt="item.description" style="max-height: 500px;">
                                </div>
                            </div>
                            <a class="carousel-control-prev" href="#webCarousel" role="button" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Previous</span></a>
                            <a class="carousel-control-next" href="#webCarousel" role="button" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Next</span></a>
                        </div>

                        <div id="mobileCarousel" class="carousel slide d-block d-sm-none" data-bs-ride="carousel" data-bs-interval="5000" v-if="banner.some(item => item.display === 'mobile')">
                            <div class="carousel-inner">
                                <div v-for="(item, index) in banner.filter(item => item.display === 'mobile')" :key="index" :class="['carousel-item', { 'active': index === 0 }]">
                                    <img :src="item.image" class="d-block w-100" :alt="item.description" style="max-height: 250px;">
                                </div>
                            </div>
                            <a class="carousel-control-prev" href="#mobileCarousel" role="button" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Previous</span></a>
                            <a class="carousel-control-next" href="#mobileCarousel" role="button" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Next</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <div class="page-content member-home-content">
        <section class="editorial-spotlight">
            <div class="spotlight-lead">
                <div class="spotlight-header">
                    <span class="editorial-kicker">Buku populer</span>
                    <h2>Sorotan koleksi</h2>
                </div>

                <div v-if="buku_populer && buku_populer.length" class="lead-book-card">
                    <router-link :to="{ name: 'detail-buku', params: { idb: buku_populer[0].isbn } }" class="lead-book-link">
                        <div class="lead-book-cover">
                            <img :src="buku_populer[0].image.replace('&amp;', '&')" :alt="buku_populer[0].title">
                        </div>
                        <div class="lead-book-copy">
                            <h3>{{ buku_populer[0].title }}</h3>
                            <p>{{ buku_populer[0].writer }}</p>
                        </div>
                    </router-link>
                </div>
            </div>

            <div class="spotlight-stack">
                <div class="stack-header">
                    <span class="editorial-kicker">Buku populer lainnya</span>
                    <router-link to="/koleksi-buku" class="menu-link text-link">Lihat semua</router-link>
                </div>

                <div v-if="buku_populer.length > 1" class="mini-book-list">
                    <router-link v-for="(buku, i) in buku_populer.slice(1, 4)" :key="i" :to="{ name: 'detail-buku', params: { idb: buku.isbn } }" class="mini-book-item">
                        <div class="mini-book-cover">
                            <img :src="buku.image.replace('&amp;', '&')" :alt="buku.title">
                        </div>
                        <div class="mini-book-copy">
                            <strong>{{ buku.title }}</strong>
                            <span>{{ buku.writer }}</span>
                        </div>
                    </router-link>
                </div>
            </div>
        </section>

        <section class="collection-section book-collection-section">
            <div class="section-header editorial-header">
                <div>
                    <span class="editorial-kicker">Katalog pilihan</span>
                    <h2>KOLEKSI BUKU</h2>
                </div>
                <router-link to="/koleksi-buku" class="btn btn-primary btn-sm">Lihat Semua</router-link>
            </div>
            <div class="book-carousel-shell">
                <swiper
                    :modules="modules"
                    :slides-per-view="6"
                    :space-between="0"
                    :breakpoints="swiperBreakpoints"
                    navigation
                    :scrollbar="{ draggable: true }"
                    @swiper="onSwiper"
                    @slideChange="onSlideChange"
                    :autoplay="{ delay: 3000 }"
                    class="swiper-container book-carousel"
                    :loop="buku.length > 12"
                >
                    <swiper-slide v-for="(item, index) in buku" :key="index" class="book-carousel-slide">
                        <router-link :to="{ name: 'detail-buku', params: { idb: item.isbn } }" class="catalog-book-link">
                            <article class="catalog-book-card">
                                <div class="catalog-book-art">
                                    <img :src="item.image.replace('&amp;', '&')" :alt="item.title">
                                    <span class="catalog-book-action">Lihat buku <i class="bi bi-arrow-up-right"></i></span>
                                </div>
                                <div class="catalog-book-copy">
                                    <span class="catalog-book-writer" :title="item.writer">{{ item.writer }}</span>
                                    <h3 :title="item.title">{{ item.title }}</h3>
                                </div>
                            </article>
                        </router-link>
                    </swiper-slide>
                </swiper>
            </div>
        </section>

        <!-- <section class="row mt-4 collection-section">
            <div class="col-12 col-lg-12">
                <div class="section-header editorial-header">
                    <div>
                        <span class="editorial-kicker">Audio</span>
                        <h2>KOLEKSI AUDIO BUKU</h2>
                    </div>
                    <router-link to="/koleksi-buku-audio" class='menu-link'>
                        <button class="btn btn-primary btn-sm">Lihat Semua</button>
                    </router-link>
                </div>
                <div class="card hover-shadow section-panel">
                    <div class="row">
                        <div class="col-12">
                            <swiper
                                :modules="modules"
                                :slides-per-view="6"
                                :space-between="0"
                                :breakpoints="swiperBreakpoints"
                                navigation
                                :scrollbar="{ draggable: true }"
                                @swiper="onSwiper"
                                @slideChange="onSlideChange"
                                :autoplay="{ delay: 3000 }"
                                class="swiper-container"
                                :loop="audiobuku.length > 12"
                            >
                                <swiper-slide v-for="(item, index) in audiobuku" :key="index" class="col-md-3 col-6">
                                    <router-link :to="{ name: 'detail-buku-audio', params: { idb: item.isbn } }">
                                        <div class="card book-card-item">
                                            <div class="product-image">
                                                <img :src="item.image.replace('&amp;', '&')" class="img-fluid" :alt="item.title" style="width: 150px; height: 220px;">
                                            </div>
                                            <div class="card-body py-2">
                                                <p class="card-title mb-0">
                                                    <span data-bs-toggle="tooltip" data-bs-placement="bottom" style="display: -webkit-box; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;" :title="item.writer">{{ item.writer }}</span>
                                                </p>
                                                <h6 class="card-title" data-bs-toggle="tooltip" data-bs-placement="bottom" style="display: -webkit-box; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;" :title="item.title">
                                                    {{ item.title }}
                                                </h6>
                                            </div>
                                        </div>
                                    </router-link>
                                </swiper-slide>
                            </swiper>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="row mt-4 collection-section">
            <div class="col-12 col-lg-12">
                <div class="section-header editorial-header">
                    <div>
                        <span class="editorial-kicker">Video</span>
                        <h2>KOLEKSI VIDEO BUKU</h2>
                    </div>
                    <router-link to="/koleksi-buku-video" class='menu-link'>
                        <button class="btn btn-primary btn-sm">Lihat Semua</button>
                    </router-link>
                </div>
                <div class="card hover-shadow section-panel">
                    <div class="row">
                        <div class="col-12">
                            <swiper
                                :modules="modules"
                                :slides-per-view="6"
                                :space-between="0"
                                :breakpoints="swiperBreakpoints"
                                navigation
                                :scrollbar="{ draggable: true }"
                                @swiper="onSwiper"
                                @slideChange="onSlideChange"
                                :autoplay="{ delay: 3000 }"
                                class="swiper-container"
                                :loop="videobuku.length > 12"
                            >
                                <swiper-slide v-for="(item, index) in videobuku" :key="index" class="col-md-3 col-6">
                                    <router-link :to="{ name: 'detail-buku-video', params: { idb: item.isbn } }">
                                        <div class="card book-card-item">
                                            <div class="product-image">
                                                <img :src="item.image.replace('&amp;', '&')" class="img-fluid" :alt="item.title" style="width: 150px; height: 220px;">
                                            </div>
                                            <div class="card-body py-2">
                                                <p class="card-title mb-0">
                                                    <span data-bs-toggle="tooltip" data-bs-placement="bottom" style="display: -webkit-box; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;" :title="item.writer">{{ item.writer }}</span>
                                                </p>
                                                <h6 class="card-title" data-bs-toggle="tooltip" data-bs-placement="bottom" style="display: -webkit-box; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;" :title="item.title">
                                                    {{ item.title }}
                                                </h6>
                                            </div>
                                        </div>
                                    </router-link>
                                </swiper-slide>
                            </swiper>
                        </div>
                    </div>
                </div>
            </div>
        </section> -->

		<section v-if="quiz.length>0" class="row">
            <div class="col-12 col-lg-12">
                <div class="testimonial-slider hover-shadow">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="testimonial-title">
                                <i class="bi bi-quote display-2"></i>
                                <h2 class="fw-bold display-6 text-white">Quiz</h2>
                            </div>
                            <div class="d-flex justify-content-center mt-2 mb-3">
                                <button class="swiper-button-prev-quiz btn rounded-pill me-2"><i class="bi bi-caret-left-fill" style="font-size: 2rem; color: white;"></i></button>
                                <button class="swiper-button-next-quiz btn rounded-pill"><i class="bi bi-caret-right-fill" style="font-size: 2rem; color: white;"></i></button>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <swiper
                                ref="swiperQuiz"
                                :modules="modules"
                                :slides-per-view="2"
                                :space-between="10"
                                :breakpoints="swiperBreakpointsfrasa"
                                :navigation="navigationQuiz"
                                :scrollbar="{ draggable: true }"
                                @swiper="onSwiper"
                                @slideChange="onSlideChange"
                                :autoplay= "{ delay: 4000 }"
                                class="swiper-frasa col-md-10"
                                :loop="quiz.length > 4"
                            >
                                <swiper-slide v-for="(value, index) in quiz" :key="index">
									<div class="card" style="height: 320px;">
										<div class="d-flex justify-content-center align-items-center flex-column mt-3 mb-0 mx-4 mb-0 py-1" style="height: 125px; max-height: 125px;" :title="value.title">
                                            <h6 class="mb-0">{{ value.title }}</h6>
                                        </div>
										<hr>
										<div class="card-body-quiz h-100">
											<div class="card-description-quiz mx-4 mb-0" style="display: -webkit-box; line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;" :title="value.description">
												{{ value.description }}
											</div>
										</div>
										<div class="row mx-2">
											<div class="col-12 text-center mb-2">
												<small class="text-muted"><i class="bi bi-calendar-check"></i> Tenggat Waktu : {{ value.start_date }} s/d {{ value.start_date }}</small>
											</div>
										</div>
										<div class="card-footer-quiz mb-1">
											<div class="row mx-2">
												<div class="card-date-quiz mb-1">
													<button :class="value.finished ? 'btn btn-success btn-block' : 'btn btn-primary btn-block'" @click="goToQuiz(value.id, value.finished)">{{ value.finished ? 'Lihat Nilai' : 'Lihat Quiz' }}</button>
												</div>
												<div class="card-author-quiz">
													<small class="text-muted"><i class="bi bi-person-circle"></i> <strong>{{ value.name }}</strong></small>
												</div>
											</div>
										</div>
									</div>
                                    
                                </swiper-slide>
                            </swiper>
                        </div>
                    </div>
                </div>
            </div>
        </section>
		<section v-if="show_game" class="row">
            <div class="col-12 mt-4">
				<div class="row">
					<swiper
                        :modules="modules"
                        :slides-per-view="3"
                        :space-between="5"
                        :breakpoints="swiperBreakpointsgame"
                        navigation
                        :scrollbar="{ draggable: true }"
                        @swiper="onSwiper"
                        @slideChange="onSlideChange"
                        :autoplay= "{ delay: 4000 }"
                        class="swiper-container px-2"
                    >
                        <swiper-slide>
							<div class="card hover-shadow" @click="goToGame('game-math')">
								<div class="card-content d-flex flex-column">
									<div class="mb-0 pb-0">
										<img src="game/math/math.png" class="img-fluid" style="border-radius: 5px 5px 0 0 !important;">
									</div>
									<div class="card-body">
										<div class="card-title">
											<a href="#">
												<h5> Game Perhitungan </h5>
											</a>
										</div>
									</div>
								</div>
							</div>
						</swiper-slide>
						<swiper-slide>
							<div class="card hover-shadow" @click="goToGame('word-scramble')">
								<div class="card-content d-flex flex-column">
									<div class="mb-0 pb-0">
										<img src="game/scramble/scramble.png" class="img-fluid" style="border-radius: 5px 5px 0 0 !important;">
									</div>
									<div class="card-body">
										<div class="card-title">
											<a href="#">
												<h5> Game Susun Kata </h5>
											</a>
										</div>
									</div>
								</div>
							</div>
						</swiper-slide>
						<swiper-slide>
							<div class="card hover-shadow" @click="goToGame('flying-bird')">
								<div class="card-content d-flex flex-column">
									<div class="mb-0 pb-0">
										<img src="game/flyingbird/flyingbird.png" class="img-fluid" style="border-radius: 5px 5px 0 0 !important;">
									</div>
									<div class="card-body">
										<div class="card-title">
											<a href="#">
												<h5> Game Flying Bird </h5>
											</a>
										</div>
									</div>
								</div>
							</div>
                        </swiper-slide>
                    </swiper>
				</div>
			</div>
        </section>
		<section v-if="video.length>0" class="video-showcase-section">
			<div class="video-showcase-heading">
				<div>
					<span class="editorial-kicker">Tonton dan jelajahi</span>
					<h2>VIDEO PILIHAN</h2>
				</div>
				<span class="video-showcase-count">{{ video.length }} video</span>
			</div>
			<div id="videoCarousel" class="carousel slide video-showcase" data-bs-ride="carousel">
				<div class="carousel-inner">
					<div v-for="(item, index) in video" :key="index" :class="['carousel-item', { 'active': index === 0 }]">
						<article class="video-feature">
							<div class="video-feature-player">
								<div class="video-player-frame">
									<iframe
										class="d-block w-100"
										:src="item.file"
										frameborder="0"
										allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
										allowfullscreen
									></iframe>
									<span class="video-play-indicator" aria-hidden="true"><i class="bi bi-play-fill"></i></span>
								</div>
							</div>
							<div class="video-feature-copy">
								<span class="video-feature-label">Sedang diputar</span>
								<h3>{{ item.title }}</h3>
								<div class="video-description-desktop">{{ item.description }}</div>
								<div class="video-description-mobile">
									<p v-if="isLongDescription(item.description)">
										{{ isFullDescription(index) ? item.description : truncatedDescription(item.description) }}
										<a href="#" @click.prevent="toggleDescription(index)">
											{{ isFullDescription(index) ? 'Ringkas Deskripsi' : 'Baca Selengkapnya' }}
										</a>
									</p>
									<p v-else>{{ item.description }}</p>
								</div>
								<div class="video-carousel-controls">
									<span>Jelajahi video lainnya</span>
									<div>
										<button class="video-carousel-control" type="button" data-bs-target="#videoCarousel" data-bs-slide="prev" aria-label="Video sebelumnya"><i class="bi bi-arrow-left"></i></button>
										<button class="video-carousel-control" type="button" data-bs-target="#videoCarousel" data-bs-slide="next" aria-label="Video berikutnya"><i class="bi bi-arrow-right"></i></button>
									</div>
								</div>
							</div>
						</article>
					</div>
				</div>
			</div>
		</section>
        <section v-if="news.length>0" class="headline-section">
            <div class="headline-section-heading">
                <div>
                    <span class="editorial-kicker">Pilihan redaksi</span>
                    <h2>TAJUK UTAMA</h2>
                </div>
                <router-link :to="{ name: 'artikel', params: { idart: 'TU' } }" class="btn btn-primary btn-sm">Lihat Semua</router-link>
            </div>
            <div class="headline-layout">
                <router-link v-if="mainNews" :to="{ name: 'detail-artikel', params: { idart: 'TU', detail: mainNews.id } }" class="headline-feature">
                    <div class="headline-feature-image">
                        <img :src="mainNews.image" :alt="mainNews.title">
                        <span class="headline-image-label">Sorotan</span>
                    </div>
                    <div class="headline-feature-copy">
                        <span class="headline-byline">Oleh <strong>{{ mainNews.author }}</strong> <span aria-hidden="true">·</span> {{ mainNews.published_at }}</span>
                        <h3 :title="mainNews.title">{{ mainNews.title }}</h3>
                        <p><span v-html="mainNews.content"></span></p>
                        <span class="headline-read-more">Lanjutkan membaca <i class="bi bi-arrow-right"></i></span>
                    </div>
                </router-link>
                <div class="headline-supporting">
                    <div class="headline-supporting-heading">
                        <span>Berita lainnya</span>
                        <i class="bi bi-plus-lg" aria-hidden="true"></i>
                    </div>
                    <router-link v-for="newsItem in otherNews" :key="newsItem.id" :to="{ name: 'detail-artikel', params: { idart: 'TU', detail: newsItem.id } }" class="headline-supporting-item">
                        <div class="headline-supporting-image">
                            <img :src="newsItem.image" :alt="newsItem.title">
                        </div>
                        <h3 :title="newsItem.title">{{ newsItem.title }}</h3>
                        <i class="bi bi-arrow-up-right" aria-hidden="true"></i>
                    </router-link>
                </div>
            </div>
        </section>
        <section v-if="frasa.length>0" class="quote-showcase-section">
            <div class="quote-showcase-heading">
                <div>
                    <span class="quote-index">01 / INSPIRASI</span>
                    <h2>Frasa pilihan</h2>
                </div>
                <div class="quote-navigation">
                    <button class="swiper-button-prev-frasa quote-nav-button" type="button" aria-label="Frasa sebelumnya"><i class="bi bi-arrow-left"></i></button>
                    <button class="swiper-button-next-frasa quote-nav-button" type="button" aria-label="Frasa berikutnya"><i class="bi bi-arrow-right"></i></button>
                </div>
            </div>
            <div class="quote-showcase-body">
                <div class="quote-mark" aria-hidden="true">“</div>
                <swiper
                    ref="swiperFrasa"
                    :modules="modules"
                    :slides-per-view="2"
                    :space-between="10"
                    :breakpoints="swiperBreakpointsfrasa"
                    :navigation="navigationFrasa"
                    :scrollbar="{ draggable: true }"
                    @swiper="onSwiper"
                    @slideChange="onSlideChange"
                    :autoplay= "{ delay: 4000 }"
                    class="swiper-frasa"
                    :loop="frasa.length > 4"
                >
                    <swiper-slide v-for="(value, index) in frasa" :key="index">
                        <article class="quote-slide">
                            <blockquote>{{ value.kata }}</blockquote>
                            <div class="quote-attribution">
                                <img :src="value.image" :alt="value.by">
                                <cite>{{ value.by }}</cite>
                            </div>
                        </article>
                    </swiper-slide>
                </swiper>
            </div>
        </section>
        <section v-if="wawasan.length>0" class="insight-section">
            <div class="editorial-section-heading">
                <div>
                    <span class="editorial-kicker">Baca lebih jauh</span>
                    <h2>WAWASAN</h2>
                </div>
                <router-link :to="{ name: 'artikel', params: { idart: 'WA' } }" class="btn btn-primary btn-sm">Lihat Semua</router-link>
            </div>
            <swiper
                :modules="modules"
                :slides-per-view="2"
                :space-between="20"
                :breakpoints="swiperBreakpointswawasan"
                navigation
                :scrollbar="{ draggable: true }"
                @swiper="onSwiper"
                @slideChange="onSlideChange"
                :autoplay= "{ delay: 4000 }"
                class="swiper-container insight-carousel"
                :loop="wawasan.length > 4"
            >
                <swiper-slide v-for="(value, index) in wawasan" :key="index">
                    <router-link :to="{ name: 'detail-artikel', params: { idart: 'WA', detail: value.id } }" :class="['insight-card', { 'insight-featured': index === 0 }]">
                        <div class="insight-image">
                            <img :src="value.image" :alt="value.title">
                            <span class="insight-number">{{ String(index + 1).padStart(2, '0') }}</span>
                        </div>
                        <div class="insight-copy">
                            <span class="insight-label">{{ index === 0 ? 'Pilihan wawasan' : 'Artikel pilihan' }}</span>
                            <h3 :title="value.title">{{ value.title }}</h3>
                            <p><span v-html="value.content"></span></p>
                            <span class="insight-read-more">Lanjutkan membaca <i class="bi bi-arrow-right"></i></span>
                        </div>
                    </router-link>
                </swiper-slide>
            </swiper>
        </section>
        <section v-if="review_buku.length>0" class="book-review-section">
            <div class="review-section-heading">
                <div>
                    <span class="editorial-kicker">Catatan pembaca</span>
                    <h2>REVIEW BUKU</h2>
                </div>
                <router-link :to="{ name: 'artikel', params: { idart: 'RB' } }" class="btn btn-primary btn-sm">Lihat Semua</router-link>
            </div>
            <swiper
                :modules="modules"
                :slides-per-view="2"
                :space-between="20"
                :breakpoints="swiperBreakpointswawasan"
                navigation
                :scrollbar="{ draggable: true }"
                @swiper="onSwiper"
                @slideChange="onSlideChange"
                :autoplay= "{ delay: 4000 }"
                class="swiper-container review-carousel"
                :loop="review_buku.length > 4"
            >
                <swiper-slide v-for="(value, index) in review_buku" :key="index">
                    <router-link :to="{ name: 'detail-artikel', params: { idart: 'RB', detail: value.id } }" class="book-review-card">
                        <div class="review-book-cover">
                            <img :src="value.image" :alt="value.title">
                        </div>
                        <div class="review-copy">
                            <span class="review-eyebrow">Review buku</span>
                            <h3 :title="value.title">{{ value.title }}</h3>
                            <p class="review-excerpt"><span v-html="value.content"></span></p>
                            <span v-if="value.author" class="reviewer-byline">Oleh <strong>{{ value.author }}</strong></span>
                            <span class="review-read-more">Baca review <i class="bi bi-arrow-up-right"></i></span>
                        </div>
                    </router-link>
                </swiper-slide>
            </swiper>
        </section>
        <section v-if="layar_penulis.length>0" class="author-spotlight-section">
            <div class="author-section-heading">
                <div>
                    <span class="editorial-kicker">Kenali para penulis</span>
                    <h2>LAYAR PENULIS</h2>
                </div>
                <router-link :to="{ name: 'artikel', params: { idart: 'LP' } }" class="btn btn-primary btn-sm">Lihat Semua</router-link>
            </div>
            <swiper
                :modules="modules"
                :slides-per-view="3"
                :space-between="10"
                :breakpoints="swiperBreakpointlayarpenulis"
                navigation
                :scrollbar="{ draggable: true }"
                @swiper="onSwiper"
                @slideChange="onSlideChange"
                :autoplay= "{ delay: 4000 }"
                class="swiper-container author-carousel"
                :loop="layar_penulis.length > 6"
            >
                <swiper-slide v-for="(value, index) in layar_penulis" :key="index">
                    <router-link :to="{ name: 'detail-artikel', params: { idart: 'LP', detail: value.id } }" :class="['author-spotlight-card', { 'author-featured': index === 0 }]">
                        <div class="author-portrait">
                            <img :src="value.image" :alt="value.title">
                            <span class="author-portrait-mark" aria-hidden="true"><i class="bi bi-pen"></i></span>
                        </div>
                        <div class="author-spotlight-copy">
                            <span class="author-feature-label">{{ index === 0 ? 'Sorotan penulis' : 'Layar penulis' }}</span>
                            <h3 :title="value.title">{{ value.title }}</h3>
                            <p><span v-html="value.content"></span></p>
                            <span class="author-read-more">Kenali karyanya <i class="bi bi-arrow-right"></i></span>
                        </div>
                    </router-link>
                </swiper-slide>
            </swiper>
        </section>
        <section v-if="titik_fokus.length>0" class="focus-story-section">
            <div class="focus-story-heading">
                <span class="focus-story-index">FOKUS / 01</span>
                <h2>TITIK FOKUS</h2>
                <router-link :to="{ name: 'artikel', params: { idart: 'TF' } }" class="focus-story-all">Jelajahi semua <i class="bi bi-arrow-up-right"></i></router-link>
            </div>
            <swiper
                :modules="modules"
                :slides-per-view="2"
                :space-between="20"
                :breakpoints="swiperBreakpointswawasan"
                navigation
                :scrollbar="{ draggable: true }"
                @swiper="onSwiper"
                @slideChange="onSlideChange"
                :autoplay= "{ delay: 4000 }"
                class="swiper-container focus-story-carousel"
                :loop="titik_fokus.length > 4"
            >
                <swiper-slide v-for="(value, index) in titik_fokus" :key="index">
                    <router-link :to="{ name: 'detail-artikel', params: { idart: 'TF', detail: value.id } }" class="focus-story-card">
                        <img :src="value.image" :alt="value.title">
                        <span class="focus-story-shade"></span>
                        <span class="focus-story-copy">
                            <span class="focus-story-label">{{ String(index + 1).padStart(2, '0') }} / BACAAN UTAMA</span>
                            <strong>{{ value.title }}</strong>
                            <span class="focus-story-description"><span v-html="value.content"></span></span>
                            <span class="focus-story-read">Baca selengkapnya <i class="bi bi-arrow-right"></i></span>
                        </span>
                    </router-link>
                </swiper-slide>
            </swiper>
        </section>
        <section v-if="humoria.length>0" class="humoria-section">
            <div class="humoria-heading">
                <div>
                    <span class="humoria-eyebrow">Selingan yang menyegarkan</span>
                    <h2>HUMORIA</h2>
                </div>
                <router-link :to="{ name: 'artikel', params: { idart: 'HU' } }" class="humoria-all">Semua cerita <i class="bi bi-arrow-up-right"></i></router-link>
            </div>
            <swiper
                :modules="modules"
                :slides-per-view="2"
                :space-between="20"
                :breakpoints="swiperBreakpointswawasan"
                navigation
                :scrollbar="{ draggable: true }"
                @swiper="onSwiper"
                @slideChange="onSlideChange"
                :autoplay= "{ delay: 4000 }"
                class="swiper-container humoria-carousel"
                :loop="humoria.length > 4"
            >
                <swiper-slide v-for="(value, index) in humoria" :key="index">
                    <router-link :to="{ name: 'detail-artikel', params: { idart: 'HU', detail: value.id } }" :class="['humoria-card', { 'humoria-featured': index === 0 }]">
                        <span class="humoria-card-number">{{ String(index + 1).padStart(2, '0') }}</span>
                        <div class="humoria-image">
                            <img :src="value.image" :alt="value.title">
                            <span class="humoria-image-sticker" aria-hidden="true"><i class="bi bi-emoji-smile"></i></span>
                        </div>
                        <div class="humoria-copy">
                            <h3 :title="value.title">{{ value.title }}</h3>
                            <p><span v-html="value.content"></span></p>
                            <span class="humoria-read-more">Lanjutkan membaca <i class="bi bi-arrow-right"></i></span>
                        </div>
                    </router-link>
                </swiper-slide>
            </swiper>
        </section>
    </div>
</template>

<script>
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Navigation, Pagination, Scrollbar, A11y, Autoplay } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/scrollbar';

export default {
    components: {
        Swiper,
        SwiperSlide
    },
    props: {
        isAuthenticated: {
            type: Boolean,
            required: true
        },
		additional_features: {
            type: Number,
            required: true
        }
    },

    setup() {
        const onSwiper = (swiper) => {
            // console.log(swiper);
        };
        const onSlideChange = () => {
            // console.log('slide change');
        };
        return {
            onSwiper,
            onSlideChange,
            modules: [Navigation, Pagination, Autoplay, Scrollbar, A11y],
        };
    },

    data() {
        return {
            banner: [],
            buku_populer: [],
            buku: [],
            audiobuku: [],
            videobuku: [],
            news: [],
            frasa: [],
            wawasan: [],
            review_buku: [],
            layar_penulis: [],
            titik_fokus: [],
            humoria: [],
            quiz: [],
            video: [],
            swiperBreakpoints: {
                320: {
                    slidesPerView: 2,
                    spaceBetween: 10
                },
                640: {
                    slidesPerView: 2,
                    spaceBetween: 10
                },
                768: {
                    slidesPerView: 4,
                    spaceBetween: 10
                },
                1024: {
                    slidesPerView: 6,
                    spaceBetween: 10
                }
            },
            swiperBreakpointsfrasa: {
                320: {
                    slidesPerView: 1,
                    spaceBetween: 10
                },
                640: {
                    slidesPerView: 1,
                    spaceBetween: 10
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 10
                },
                1024: {
                    slidesPerView: 2,
                    spaceBetween: 10
                }
            },
            navigationFrasa: {
                nextEl: '.swiper-button-next-frasa',
                prevEl: '.swiper-button-prev-frasa',
            },
            navigationQuiz: {
                nextEl: '.swiper-button-next-quiz',
                prevEl: '.swiper-button-prev-quiz',
            },
            swiperBreakpointswawasan: {
                320: {
                    slidesPerView: 1,
                    spaceBetween: 20
                },
                640: {
                    slidesPerView: 1,
                    spaceBetween: 20
                },
                768: {
                    slidesPerView: 1,
                    spaceBetween: 20
                },
                1024: {
                    slidesPerView: 2,
                    spaceBetween: 20
                }
            },
            swiperBreakpointsgame: {
                320: {
                    slidesPerView: 1,
                    spaceBetween: 5
                },
                640: {
                    slidesPerView: 1,
                    spaceBetween: 5
                },
                768: {
                    slidesPerView: 1,
                    spaceBetween: 5
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 5
                }
            },
            swiperBreakpointlayarpenulis: {
                320: {
                    slidesPerView: 1,
                    spaceBetween: 10
                },
                640: {
                    slidesPerView: 1,
                    spaceBetween: 10
                },
                768: {
                    slidesPerView: 1,
                    spaceBetween: 10
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 10
                }
            },
			fullDescriptionIndexes: [],
			show_game: false
        };
    },

    created(){
        this.getBukuPopuler();
        this.getNewCollection();
        this.getBanner();
        this.getAllArticle();
        this.getVideo();
        this.getNewCollectionAudio()
        this.getNewCollectionVideo()
    },

    mounted() {
        this.initializeSubMenu();
		this.initFeatures();
    },

    methods: {
		initFeatures(){
			if(this.additional_features==2 || this.additional_features==3){
				this.getQuiz()
			}

			if(this.additional_features==1 || this.additional_features==3){
				this.show_game = true
			}
		},

		loadYouTubeAPI() {
			// Create script element for YouTube IFrame API
			const tag = document.createElement('script');
			tag.src = "https://www.youtube.com/iframe_api";
			const firstScriptTag = document.getElementsByTagName('script')[0];
			firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

			// Define global function for when the API is ready
			window.onYouTubeIframeAPIReady = this.initYouTubePlayers;
		},

		initYouTubePlayers() {
			const carouselElement = document.querySelector('#videoCarousel');
			const carousel = new bootstrap.Carousel(carouselElement, {
				ride: 'carousel',
				interval: 5000,
			});

			const iframes = document.querySelectorAll('.carousel-item iframe');
			const players = [];

			iframes.forEach((iframe, index) => {
				const player = new YT.Player(iframe, {
					events: {
						'onStateChange': (event) => {
							if (event.data === YT.PlayerState.PLAYING) {
								carousel.pause();
							} else if (event.data === YT.PlayerState.PAUSED || event.data === YT.PlayerState.ENDED) {
								carousel.cycle();
							}
						}
					}
				});
				players.push(player);
			});
		},

        swiperPrev() {
            this.$refs.swiperFrasa.swiper.slidePrev();
        },

        swiperNext() {
            this.$refs.swiperFrasa.swiper.slideNext();
        },

        getBukuPopuler() {
            this.buku_populer = [];

            let loader = this.$loading.show();
            window.axios.get('/getBukuPopuler')
            .then((response) => {
                this.buku_populer = response.data;
                loader.hide();
            })
            .catch((e) => {
                console.error(e)
            });
        },

        getNewCollection() {
            this.buku = [];

            window.axios
            .get('/getNewCollection')
            .then((response) => {
                this.buku = response.data;
            })
            .catch((e) => {
                console.error(e)
            });
        },

        getNewCollectionAudio() {
            this.audiobuku = [];

            window.axios
            .get('/getAudioBook')
            .then((response) => {
                this.audiobuku = response.data;
            })
            .catch((e) => {
                console.error(e)
            });
        },

        getNewCollectionVideo() {
            this.videobuku = [];

            window.axios
            .get('/getVideoBook')
            .then((response) => {
                this.videobuku = response.data;
            })
            .catch((e) => {
                console.error(e)
            });
        },

        getBanner() {
            this.banner = [];

            window.axios
            .get('/getBanner')
            .then((response) => {
                this.banner = response.data;
            })
            .catch((e) => {
                console.error(e)
            });
        },
        
        getAllArticle() {
            this.frasa          = [];
            this.news           = [];
            this.wawasan        = [];
            this.review_buku    = [];
            this.layar_penulis  = [];
            this.titik_fokus    = [];
            this.humoria        = [];

            window.axios
            .get('/getAllArticle')
            .then((response) => {
                this.frasa          = response.data.FR;
                this.news           = response.data.TU;
                this.wawasan        = response.data.WA;
                this.review_buku    = response.data.RB;
                this.layar_penulis  = response.data.LP;
                this.titik_fokus    = response.data.TF;
                this.humoria        = response.data.HU;
            })
            .catch((e) => {
                console.error(e)
            });
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

		getQuiz() {
            this.quiz = [];

            window.axios
            .get('/getDtQuiz')
            .then((response) => {
                this.quiz = response.data;
            })
            .catch((e) => {
                console.error(e)
            });
        },

		getVideo() {
            this.video = [];

            window.axios
            .get('/getVideo')
            .then((response) => {
                this.video = response.data;
				this.loadYouTubeAPI();
            })
            .catch((e) => {
                console.error(e)
            });
        },

		goToQuiz(id, finished) {
			if(this.isAuthenticated){
				if(finished){
					this.$router.push({ name: 'quiz-test', params: { ids: id } });
				}else{
					this.$swal({
						icon: 'question',
						text: 'Apakah anda yakin ingin memulai quiz ini?',
						showCancelButton: true,
						allowOutsideClick: false,
						allowEscapeKey: false,
						confirmButtonText: '<i class="bi bi-check-circle-fill"></i> Ya',
						cancelButtonText: '<i class="bi bi-x-square-fill"></i> Tidak',
						buttonsStyling: false,
						customClass: {
							confirmButton: 'btn btn-sm btn-primary me-2',
							cancelButton: 'btn btn-sm btn-secondary',
						},
					}).then((result) => {
						if (result.value) {
							this.$router.push({ name: 'quiz-test', params: { ids: id } });
						}
					})
				}
			}else{
				this.$router.push('/mlogin');
			}
        },
		
		goToGame(game) {
			if(this.isAuthenticated){
				this.$router.push({ name: game });
			}else{
				this.$router.push('/mlogin');
			}
        },

		isLongDescription(description) {
			return description && description.length > 110;
		},
		truncatedDescription(description) {
			return description ? description.slice(0, 110) + '...' : '';
		},
		toggleDescription(index) {
			if (this.fullDescriptionIndexes.includes(index)) {
				this.fullDescriptionIndexes = this.fullDescriptionIndexes.filter(i => i !== index);
			} else {
				this.fullDescriptionIndexes.push(index);
			}
		},
		isFullDescription(index) {
			return this.fullDescriptionIndexes.includes(index);
		}
    },

    computed: {
        mainNews() {
            return this.news.length ? this.news[0] : null;
        },
        otherNews() {
            return this.news.slice(1);
        },
    },

	watch: {
		// Watch for changes in the 'additional_features' prop
		additional_features(newValue) {
			if (newValue === 2 || newValue === 3) {
				this.initFeatures()
			}
		}
	}
};
</script>

<style scoped>
	.card-header-quiz {
		font-weight: bold;
		text-align: center;
	}

	.card-body-quiz {
		text-align: center;
	}

	.card-title-quiz {
		font-size: 18px;
		font-weight: bold;
		margin-bottom: 10px;
	}

	.card-description-quiz {
		text-align: left;
		margin-bottom: 10px;
	}

	.card-description-quiz p {
		margin: 0;
	}

	.button-quiz {
		display: inline-block;
		padding: 10px 20px;
		background-color: #4CAF50;
		color: white;
		text-align: center;
		text-decoration: none;  
		display: inline-block;
		font-size: 16px;
		margin: 4px 2px;
		cursor: pointer;
		border-radius: 4px;
	}

	.card-footer-quiz {
		text-align: center;
		font-size: 15px;
	}

	.card-author-quiz {
		font-weight: bold;
		margin-bottom: 5px;
	}

    :deep(.swiper-button-next),
    :deep(.swiper-button-prev) {
        width: 30px !important;
        height: 30px !important;
        background-color: rgba(255, 255, 255, 0.656);
        border-radius: 70%;
    }

    :deep(.swiper-button-next::after),
    :deep(.swiper-button-prev::after) {
        font-size: 20px !important;
        color: rgb(0, 0, 0) !important;
    }

    :deep(.swiper-button-next) {
        right: 10px;
    }

    :deep(.swiper-button-prev) {
        left: 10px;
    }

    .testimonial-slider {
        background-color: #fab040;
        padding: 2em 2em 0em;
        border-radius: 10px;
    }
    .testimonial-title {
        color: #fff;
    }
    .testimonial-title h2 {
        padding-left: 0.2em;
    }

    .swiper-frasa .swiper-button-next,
    .swiper-frasa .swiper-button-prev {
        width: 25px;
        height: 25px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .swiper-frasa .swiper-button-next::after,
    .swiper-frasa .swiper-button-prev::after {
        font-size: 16px;
    }

    .blog-container a:not(.btn) {
        color: #999;
        transition: all .2s linear;
        -webkit-transition: all .2s linear;
        -moz-transition: all .2s linear;
        -ms-transition: all .2s linear;
        -o-transition: all .2s linear;
    }

    .post-like {
        float: right;
        cursor: pointer;
    }

    .headline {
        margin: 20px 0;
        padding: 5px 0 10px;
        border-bottom: 1px solid #eee;
        font-weight: 500;
    }
    .media, .media-body {
        overflow: hidden;
        zoom: 1;
    }
    .swiper-scrollbar {
        display: none;
    }

    .swiper-container:hover .swiper-scrollbar {
        display: block;
        opacity: 0;
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
        max-height: 250px;
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
    
    .gambar-kotak {
        height: 300px;
    }

    @media (max-width: 1200px) {
		iframe {
			height: 250px;
		}

        :deep(.swiper-button-next),
        :deep(.swiper-button-prev) {
            display: none;
        }

        .product-image {
            height: auto;
            padding: 10px;
        }

        .product-image img {
            width: 70%;
        }

        .card-title {
            font-size: 14px;
        }
        
        .buku-desc {
            font-size: 12px;
        }

        .gambar-kotak {
            height: auto;
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

    .member-home {
        padding-top: .4rem;
    }

    .member-home .carousel {
        overflow: hidden;
        border: 1px solid var(--member-line, #e4e8e2);
        border-radius: 14px;
        background: var(--member-surface, #fff);
    }

    .member-home .carousel img {
        display: block;
        width: 100%;
        max-height: min(500px, 42vw) !important;
        object-fit: contain;
    }

    .member-home-content > section {
        margin-top: clamp(1.6rem, 4vw, 3rem) !important;
    }

    .member-home-content > section:first-child {
        margin-top: .75rem !important;
    }

    .editorial-spotlight {
        display: grid;
        grid-template-columns: minmax(0, 1.55fr) minmax(260px, .8fr);
        gap: clamp(1.25rem, 3vw, 2.5rem);
        margin: 1.25rem 0 2.75rem;
        padding: clamp(1.25rem, 3vw, 2.2rem);
        background: var(--member-soft, #edf4ef);
        border-top: 3px solid var(--member-accent, #236b58);
    }

    .spotlight-header {
        margin-bottom: 1.25rem;
    }

    .editorial-kicker {
        display: block;
        color: var(--member-accent-dark, #174b3d);
        font-size: .68rem;
        font-weight: 800;
        letter-spacing: .13em;
        line-height: 1.4;
        text-transform: uppercase;
    }

    .spotlight-header h2 {
        margin: .3rem 0 0;
        font-size: clamp(1.45rem, 2.6vw, 2.25rem);
        line-height: 1.15;
    }

    .lead-book-card {
        min-height: 268px;
        background: var(--member-surface, #fff);
    }

    .lead-book-link {
        display: grid;
        grid-template-columns: minmax(165px, 240px) minmax(0, 1fr);
        align-items: center;
        min-height: inherit;
        gap: clamp(1.1rem, 3vw, 2.5rem);
        color: inherit;
        text-decoration: none;
    }

    .lead-book-cover {
        display: grid;
        min-height: 268px;
        padding: 1.1rem;
        place-items: center;
        background: var(--member-paper, #f7f7f3);
    }

    .lead-book-cover img {
        width: auto;
        max-width: 100%;
        height: 220px;
        object-fit: contain;
        box-shadow: 8px 9px 0 rgba(35, 52, 43, .09);
    }

    .lead-book-copy {
        padding: 1.25rem 1.25rem 1.25rem 0;
    }

    .lead-book-copy h3 {
        margin: .65rem 0 .45rem;
        font-size: clamp(1.25rem, 2.5vw, 2rem);
        line-height: 1.2;
    }

    .lead-book-copy p {
        margin: 0;
        color: var(--member-muted, #64716c);
        font-size: .95rem;
    }

    .spotlight-stack {
        padding-left: clamp(.25rem, 1vw, 1rem);
    }

    .stack-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 40px;
        gap: .75rem;
        margin-bottom: .85rem;
        border-bottom: 1px solid rgba(37, 54, 45, .2);
    }

    .text-link {
        color: var(--member-accent, #236b58);
        font-size: .83rem;
        font-weight: 800;
        text-decoration: none;
        white-space: nowrap;
    }

    .mini-book-list {
        display: grid;
    }

    .mini-book-item {
        display: grid;
        grid-template-columns: 54px minmax(0, 1fr);
        align-items: center;
        min-height: 84px;
        gap: .85rem;
        padding: .65rem 0;
        color: inherit;
        border-bottom: 1px solid rgba(37, 54, 45, .14);
        text-decoration: none;
    }

    .mini-book-cover {
        display: grid;
        width: 54px;
        height: 68px;
        place-items: center;
        background: var(--member-paper, #f7f7f3);
    }

    .mini-book-cover img {
        max-width: 100%;
        max-height: 64px;
        object-fit: contain;
    }

    .mini-book-copy {
        display: grid;
        gap: .25rem;
        min-width: 0;
    }

    .mini-book-copy strong {
        display: -webkit-box;
        overflow: hidden;
        color: var(--member-ink, #202a27);
        font-size: .88rem;
        line-height: 1.35;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }

    .mini-book-copy span {
        overflow: hidden;
        color: var(--member-muted, #64716c);
        font-size: .78rem;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .mini-book-item:hover strong,
    .lead-book-link:hover h3 {
        color: var(--member-accent, #236b58);
    }

    .editorial-header {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 1rem;
        margin: 0 0 .8rem;
        padding-bottom: .8rem;
        border-bottom: 1px solid var(--member-line, #e4e8e2);
    }

    .editorial-header h2 {
        margin: .22rem 0 0;
        font-size: clamp(1.1rem, 2vw, 1.4rem);
        letter-spacing: .035em;
    }

    .section-panel {
        padding: .65rem 0;
    }

    .book-card-item {
        margin: .4rem;
        border-radius: 4px !important;
        box-shadow: none !important;
    }

    .book-card-item .product-image {
        background: #f0f1eb;
    }

    .book-card-item .card-body {
        padding-inline: .8rem;
    }

    .member-home-content .divider-left-center {
        width: 100%;
        margin-bottom: .8rem;
        text-align: left;
    }

    .member-home-content .divider-left-center h2 {
        font-size: clamp(1.05rem, 2vw, 1.35rem);
        letter-spacing: .07em;
    }

    .member-home-content .row-cols-2 > .col .card,
    .member-home-content .swiper-slide > a > .card {
        border-radius: 9px;
        transition: transform .18s ease, border-color .18s ease;
    }

    .member-home-content .row-cols-2 > .col .card:hover,
    .member-home-content .swiper-slide > a > .card:hover {
        transform: translateY(-3px);
        border-color: var(--member-accent, #236b58);
    }

    .member-home-content .card-body a:not(.btn) {
        color: var(--member-ink, #202a27);
    }

    .member-home-content .card-body h5,
    .member-home-content .card-body h6 {
        line-height: 1.45;
    }

    .member-home-content .testimonial-slider {
        padding: clamp(1rem, 3vw, 2rem);
    }

    .member-home-content .testimonial-slider .swiper-slide {
        padding-bottom: 1rem;
    }

    .member-home-content .testimonial-title h2 {
        padding: 0;
        color: var(--member-accent-dark, #174b3d) !important;
        font-size: clamp(1.4rem, 3vw, 2rem);
    }

    .member-home-content .testimonial-title .bi-quote {
        color: var(--member-accent, #236b58);
        font-size: 2.4rem;
    }

    .member-home-content .blog-container .panel-body {
        padding: clamp(.5rem, 2vw, 1.25rem);
    }

    .member-home-content .blog-container .panel-body > div:first-child img {
        width: 100%;
        max-height: 360px;
        object-fit: cover;
    }

    .member-home-content .popular-post {
        display: flex;
        align-items: center;
        gap: 1rem;
        height: auto !important;
        max-height: none !important;
        margin: .5rem 0 !important;
        padding: .7rem;
        border-bottom: 1px solid var(--member-line, #e4e8e2);
    }

    .member-home-content .popular-post img {
        width: 96px;
        height: 96px;
        object-fit: cover;
    }

    .member-home-content .card:has(iframe) {
        overflow: hidden;
    }

    .member-home-content iframe {
        aspect-ratio: 16 / 9;
        height: auto;
        min-height: 220px;
    }

    .member-home-content .swiper-button-next,
    .member-home-content .swiper-button-prev {
        width: 34px;
        height: 34px;
        color: var(--member-accent, #236b58);
        background: var(--member-surface, #fff);
        border: 1px solid var(--member-line, #e4e8e2);
        border-radius: 50%;
    }

    .member-home-content .swiper-button-next:after,
    .member-home-content .swiper-button-prev:after {
        font-size: 13px;
        font-weight: 800;
    }

    @media (max-width: 767px) {
        .editorial-spotlight {
            grid-template-columns: minmax(0, 1fr);
            gap: 1.5rem;
            margin-top: .75rem;
            padding: 1rem;
        }

        .lead-book-link {
            grid-template-columns: minmax(105px, 34%) minmax(0, 1fr);
            gap: .9rem;
        }

        .lead-book-cover {
            min-height: 220px;
            padding: .75rem;
        }

        .lead-book-cover img {
            height: 185px;
        }

        .lead-book-copy {
            padding: .8rem .8rem .8rem 0;
        }

        .spotlight-stack {
            padding-left: 0;
        }

        .editorial-header {
            align-items: flex-start;
        }

        .editorial-header .btn {
            white-space: nowrap;
        }

        .member-home .carousel img {
            max-height: 250px !important;
        }

        .member-home-content .popular-post img {
            width: 72px;
            height: 72px;
        }

        .member-home-content .blog-container .panel-body {
            padding: .4rem;
        }

        .member-home-content iframe {
            min-height: 190px;
        }
    }

    .member-home-content {
        --editorial-ink: var(--member-ink, #202a27);
        --editorial-muted: var(--member-muted, #68756e);
        --editorial-line: var(--member-line, #dfe6df);
        --editorial-paper: var(--member-paper, #f5f6f1);
        --editorial-surface: var(--member-surface, #fff);
        --editorial-green: var(--member-accent, #236b58);
        --editorial-green-dark: var(--member-accent-dark, #174b3d);
        --editorial-panel: var(--member-panel, #174b3d);
        --editorial-orange: var(--member-editorial-orange, #e7a348);
        --editorial-quote-bg: var(--member-quote-bg, #e5eee6);
        --editorial-quote-card: var(--member-quote-card, rgba(255, 255, 255, .64));
        --editorial-review-bg: var(--member-review-bg, #f2f4ef);
        --editorial-review-line: var(--member-review-line, #d4ddd5);
        --editorial-cover-bg: var(--member-cover-bg, #e4e9e1);
        --editorial-humoria-bg: var(--member-humoria-bg, #fff4e8);
        --editorial-humoria-line: var(--member-humoria-line, #ecd8c0);
        --editorial-humoria-card-line: var(--member-humoria-card-line, #f1dfca);
        --editorial-humoria-ink: var(--member-humoria-ink, #372b20);
        --editorial-humoria-muted: var(--member-humoria-muted, #756453);
        --editorial-humoria-link: var(--member-humoria-link, #81501f);
        --editorial-humoria-number: var(--member-humoria-number, #cf7730);
        --editorial-humoria-image: var(--member-humoria-image, #f6e2ca);
        --editorial-humoria-sticker: var(--member-humoria-sticker, #ffe09b);
        --editorial-humoria-sticker-ink: var(--member-humoria-sticker-ink, #8a4a19);
        --editorial-humoria-sticker-line: var(--member-humoria-sticker-line, #fff);
        min-width: 0;
    }

    .member-home-content > section {
        min-width: 0;
    }

    .book-collection-section,
    .video-showcase-section,
    .headline-section,
    .quote-showcase-section,
    .insight-section,
    .book-review-section,
    .author-spotlight-section,
    .focus-story-section,
    .humoria-section {
        margin-block: clamp(2.5rem, 5vw, 4.5rem);
    }

    .editorial-header,
    .headline-section-heading,
    .editorial-section-heading,
    .review-section-heading,
    .author-section-heading,
    .video-showcase-heading,
    .quote-showcase-heading,
    .humoria-heading {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.15rem;
        padding-bottom: .85rem;
        border-bottom: 1px solid var(--editorial-line);
    }

    .editorial-header h2,
    .headline-section-heading h2,
    .editorial-section-heading h2,
    .review-section-heading h2,
    .author-section-heading h2,
    .video-showcase-heading h2,
    .quote-showcase-heading h2,
    .humoria-heading h2 {
        margin: .3rem 0 0;
        color: var(--editorial-ink);
        font-size: clamp(1.2rem, 2.25vw, 1.8rem);
        font-weight: 800;
        letter-spacing: .035em;
        line-height: 1.15;
    }

    .editorial-header .btn,
    .headline-section-heading .btn,
    .editorial-section-heading .btn,
    .review-section-heading .btn,
    .author-section-heading .btn {
        flex: 0 0 auto;
        border-radius: 999px;
        padding-inline: 1rem;
        font-weight: 700;
    }

    .book-carousel-shell {
        overflow: hidden;
        padding: .8rem .1rem 1.4rem;
    }

    .book-carousel-slide {
        height: auto;
        padding: 0 .55rem;
    }

    .catalog-book-link {
        display: block;
        height: 100%;
        color: inherit;
        text-decoration: none;
    }

    .catalog-book-card {
        display: flex;
        height: 100%;
        min-height: 330px;
        flex-direction: column;
        overflow: hidden;
        background: var(--editorial-surface);
        border: 1px solid var(--editorial-line);
        border-radius: 12px;
        transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
    }

    .catalog-book-art {
        position: relative;
        display: grid;
        aspect-ratio: 4 / 5;
        min-height: 190px;
        overflow: hidden;
        padding: 1.15rem;
        place-items: center;
        background: var(--editorial-paper);
    }

    .catalog-book-art img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        filter: drop-shadow(0 9px 9px rgba(29, 42, 34, .16));
        transition: transform .3s ease;
    }

    .catalog-book-action {
        position: absolute;
        right: .7rem;
        bottom: .7rem;
        padding: .35rem .55rem;
        color: var(--editorial-green-dark);
        background: rgba(255, 255, 255, .94);
        border-radius: 999px;
        font-size: .68rem;
        font-weight: 800;
        opacity: 0;
        transform: translateY(4px);
        transition: opacity .2s ease, transform .2s ease;
    }

    .catalog-book-copy {
        display: flex;
        flex: 1;
        flex-direction: column;
        gap: .35rem;
        padding: .85rem .9rem 1rem;
    }

    .catalog-book-writer {
        overflow: hidden;
        color: var(--editorial-muted);
        font-size: .72rem;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .catalog-book-copy h3 {
        display: -webkit-box;
        overflow: hidden;
        margin: 0;
        color: var(--editorial-ink);
        font-size: .9rem;
        font-weight: 800;
        line-height: 1.4;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }

    .catalog-book-link:hover .catalog-book-card,
    .catalog-book-link:focus-visible .catalog-book-card {
        border-color: var(--editorial-green);
        box-shadow: 0 12px 25px rgba(27, 62, 48, .12);
        transform: translateY(-4px);
    }

    .catalog-book-link:hover .catalog-book-art img,
    .catalog-book-link:focus-visible .catalog-book-art img {
        transform: scale(1.04);
    }

    .catalog-book-link:hover .catalog-book-action,
    .catalog-book-link:focus-visible .catalog-book-action {
        opacity: 1;
        transform: translateY(0);
    }

    .video-showcase-section {
        padding: clamp(1rem, 3vw, 2rem);
        color: #fff;
        background: #182e27;
    }

    .video-showcase-heading {
        align-items: center;
        border-color: rgba(255, 255, 255, .18);
    }

    .video-showcase-heading h2,
    .video-showcase-heading .editorial-kicker {
        color: #fff;
    }

    .video-showcase-count {
        color: rgba(255, 255, 255, .72);
        font-size: .83rem;
        font-weight: 700;
    }

    .video-showcase {
        overflow: hidden;
        border-radius: 10px;
    }

    .video-feature {
        display: grid;
        grid-template-columns: minmax(0, 1.45fr) minmax(230px, .72fr);
        gap: clamp(1.25rem, 3vw, 2.5rem);
        align-items: center;
    }

    .video-player-frame {
        position: relative;
        overflow: hidden;
        aspect-ratio: 16 / 9;
        background: #0a1410;
        border-radius: 9px;
    }

    .video-player-frame iframe {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        min-height: 0;
        border: 0;
        border-radius: inherit;
    }

    .video-play-indicator {
        position: absolute;
        top: 50%;
        left: 50%;
        display: grid;
        width: clamp(46px, 6vw, 68px);
        aspect-ratio: 1;
        color: #fff;
        background: rgba(15, 33, 26, .76);
        border: 1px solid rgba(255, 255, 255, .72);
        border-radius: 50%;
        font-size: 2rem;
        pointer-events: none;
        place-items: center;
        transform: translate(-50%, -50%);
    }

    .video-feature-copy {
        min-width: 0;
        padding-block: .5rem;
    }

    .video-feature-label,
    .insight-label,
    .review-eyebrow,
    .author-feature-label {
        color: var(--editorial-orange);
        font-size: .68rem;
        font-weight: 800;
        letter-spacing: .12em;
        text-transform: uppercase;
    }

    .video-feature-copy h3 {
        margin: .7rem 0 .8rem;
        color: #fff;
        font-size: clamp(1.35rem, 2.7vw, 2.3rem);
        font-weight: 800;
        line-height: 1.15;
    }

    .video-description-desktop,
    .video-description-mobile {
        color: rgba(255, 255, 255, .78);
        font-size: .94rem;
        line-height: 1.7;
    }

    .video-description-mobile {
        display: none;
    }

    .video-carousel-controls {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .75rem;
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(255, 255, 255, .18);
        color: rgba(255, 255, 255, .7);
        font-size: .75rem;
        font-weight: 700;
    }

    .video-carousel-controls > div {
        display: flex;
        gap: .5rem;
    }

    .video-carousel-control {
        display: grid;
        width: 36px;
        height: 36px;
        color: #fff;
        background: transparent;
        border: 1px solid rgba(255, 255, 255, .4);
        border-radius: 50%;
        place-items: center;
    }

    .video-carousel-control:hover {
        color: var(--editorial-green-dark);
        background: #fff;
    }

    .headline-section-heading {
        align-items: end;
    }

    .headline-layout {
        display: grid;
        grid-template-columns: minmax(0, 1.55fr) minmax(260px, .8fr);
        gap: clamp(1.25rem, 3vw, 2.5rem);
    }

    .headline-feature {
        display: flex;
        min-width: 0;
        flex-direction: column;
        color: inherit;
        text-decoration: none;
    }

    .headline-feature-image {
        position: relative;
        overflow: hidden;
        aspect-ratio: 16 / 9;
        background: var(--editorial-paper);
    }

    .headline-feature-image img,
    .headline-supporting-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .headline-image-label {
        position: absolute;
        bottom: 1rem;
        left: 1rem;
        padding: .35rem .65rem;
        color: #fff;
        background: var(--editorial-panel);
        font-size: .68rem;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .headline-feature-copy {
        padding: 1.15rem .25rem .25rem;
    }

    .headline-byline {
        color: var(--editorial-muted);
        font-size: .75rem;
    }

    .headline-feature-copy h3 {
        max-width: 720px;
        margin: .6rem 0;
        color: var(--editorial-ink);
        font-size: clamp(1.45rem, 3vw, 2.45rem);
        font-weight: 800;
        line-height: 1.14;
    }

    .headline-feature-copy p,
    .headline-supporting-item h3 {
        display: -webkit-box;
        overflow: hidden;
        margin: 0;
        color: var(--editorial-muted);
        line-height: 1.6;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 3;
    }

    .headline-read-more,
    .insight-read-more,
    .review-read-more,
    .author-read-more {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        margin-top: .85rem;
        color: var(--editorial-green);
        font-size: .82rem;
        font-weight: 800;
    }

    .headline-supporting {
        padding-left: 1.15rem;
        border-left: 1px solid var(--editorial-line);
    }

    .headline-supporting-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: .25rem 0 .75rem;
        color: var(--editorial-ink);
        font-size: .78rem;
        font-weight: 800;
        letter-spacing: .09em;
        text-transform: uppercase;
    }

    .headline-supporting-item {
        display: grid;
        grid-template-columns: minmax(112px, 36%) minmax(0, 1fr) 16px;
        align-items: center;
        gap: .85rem;
        min-height: 124px;
        padding: .85rem 0;
        color: inherit;
        border-top: 1px solid var(--editorial-line);
        text-decoration: none;
    }

    .headline-supporting-image {
        overflow: hidden;
        aspect-ratio: 4 / 3;
        background: var(--editorial-paper);
    }

    .headline-supporting-item h3 {
        color: var(--editorial-ink);
        font-size: .9rem;
        font-weight: 800;
        line-height: 1.4;
        -webkit-line-clamp: 3;
    }

    .headline-supporting-item > i {
        color: var(--editorial-green);
    }

    .quote-showcase-section {
        position: relative;
        overflow: hidden;
        padding: clamp(1.2rem, 4vw, 3rem);
        color: #fff;
        background: var(--editorial-quote-bg);
        border-radius: 2px 56px 2px 56px;
    }

    .quote-showcase-heading {
        align-items: center;
        margin-bottom: .6rem;
        border: 0;
    }

    .quote-index {
        color: var(--editorial-green);
        font-size: .7rem;
        font-weight: 900;
        letter-spacing: .16em;
    }

    .quote-showcase-heading h2 {
        color: var(--editorial-green-dark);
        font-family: Georgia, 'Times New Roman', serif;
        font-size: clamp(1.7rem, 4vw, 3rem);
        font-weight: 500;
    }

    .quote-navigation {
        display: flex;
        gap: .5rem;
    }

    .quote-nav-button {
        display: grid;
        width: 38px;
        height: 38px;
        color: var(--editorial-green-dark);
        background: var(--editorial-quote-card);
        border: 0;
        border-radius: 50%;
        place-items: center;
    }

    .quote-showcase-body {
        position: relative;
        display: grid;
        grid-template-columns: 90px minmax(0, 1fr);
        align-items: center;
        gap: .5rem;
    }

    .quote-showcase-body .swiper-frasa {
        min-width: 0;
        width: 100%;
    }

    .quote-mark {
        align-self: start;
        color: #a5c2af;
        font-family: Georgia, 'Times New Roman', serif;
        font-size: clamp(6rem, 13vw, 11rem);
        line-height: .85;
    }

    .quote-slide {
        display: flex;
        min-height: 245px;
        flex-direction: column;
        justify-content: center;
        padding: 1.4rem clamp(1rem, 3vw, 2.5rem);
        color: var(--editorial-green-dark);
        background: var(--editorial-quote-card);
        border-radius: 2px 30px 2px 30px;
    }

    .quote-slide blockquote {
        display: -webkit-box;
        overflow: hidden;
        margin: 0;
        font-family: Georgia, 'Times New Roman', serif;
        font-size: clamp(1.15rem, 2vw, 1.75rem);
        font-weight: 600;
        line-height: 1.45;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 4;
    }

    .quote-attribution {
        display: flex;
        align-items: center;
        gap: .7rem;
        margin-top: 1.2rem;
    }

    .quote-attribution img {
        width: 38px;
        height: 38px;
        border-radius: 4px;
        object-fit: contain;
        background: var(--editorial-quote-card);
    }

    .quote-attribution cite {
        color: var(--editorial-muted);
        font-size: .82rem;
        font-style: normal;
        font-weight: 800;
    }

    .insight-section {
        padding-block: .25rem;
    }

    .insight-carousel,
    .review-carousel,
    .author-carousel,
    .humoria-carousel,
    .focus-story-carousel {
        min-width: 0;
        padding: .25rem .1rem 1.4rem;
    }

    .insight-carousel .swiper-slide,
    .review-carousel .swiper-slide,
    .author-carousel .swiper-slide,
    .humoria-carousel .swiper-slide,
    .focus-story-carousel .swiper-slide {
        height: auto;
    }

    .insight-card {
        display: flex;
        height: 100%;
        min-height: 0;
        flex-direction: column;
        overflow: hidden;
        color: inherit;
        background: var(--editorial-surface);
        border: 1px solid var(--editorial-line);
        text-decoration: none;
    }

    .insight-image {
        position: relative;
        flex: initial;
        min-height: 0;
        overflow: hidden;
        aspect-ratio: 2 / 1;
        background: var(--editorial-paper);
    }

    .insight-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .insight-number {
        position: absolute;
        right: .65rem;
        bottom: .55rem;
        color: #fff;
        font-size: 1.3rem;
        font-weight: 900;
        text-shadow: 0 1px 8px rgba(0, 0, 0, .55);
    }

    .insight-copy {
        display: flex;
        min-width: 0;
        flex: 1;
        flex-direction: column;
        align-items: flex-start;
        padding: clamp(1rem, 2vw, 1.6rem);
    }

    .insight-copy h3 {
        display: -webkit-box;
        overflow: hidden;
        margin: .55rem 0;
        color: var(--editorial-ink);
        font-size: clamp(1.05rem, 1.6vw, 1.45rem);
        font-weight: 800;
        line-height: 1.25;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 3;
    }

    .insight-copy p,
    .review-excerpt,
    .author-spotlight-copy p,
    .humoria-copy p {
        display: -webkit-box;
        overflow: hidden;
        margin: 0;
        color: var(--editorial-muted);
        font-size: .86rem;
        line-height: 1.6;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 3;
    }

    .insight-featured {
        min-height: 0;
    }

    .insight-featured .insight-image {
        aspect-ratio: 2 / 1;
    }

    .insight-featured .insight-copy {
        padding: 1rem 1.25rem 1.15rem;
    }

    .insight-featured .insight-copy h3 {
        font-size: clamp(1.2rem, 1.8vw, 1.6rem);
    }

    .book-review-section {
        padding: clamp(1rem, 3vw, 2rem);
        background: var(--editorial-review-bg);
        border-left: 4px solid var(--editorial-green);
    }

    .review-section-heading {
        border-color: var(--editorial-review-line);
    }

    .book-review-card {
        display: flex;
        height: 100%;
        min-height: 0;
        flex-direction: column;
        overflow: hidden;
        color: inherit;
        background: var(--editorial-surface);
        border: 1px solid var(--editorial-line);
        text-decoration: none;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .review-book-cover {
        display: grid;
        min-height: 0;
        overflow: hidden;
        place-items: center;
        aspect-ratio: 2 / 1;
        background: var(--editorial-cover-bg);
    }

    .review-book-cover img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        filter: drop-shadow(0 8px 8px rgba(20, 34, 25, .18));
    }

    .review-copy {
        display: flex;
        min-width: 0;
        flex-direction: column;
        align-items: flex-start;
        padding: clamp(.9rem, 2vw, 1.45rem);
    }

    .review-copy h3 {
        display: -webkit-box;
        overflow: hidden;
        margin: .45rem 0 .65rem;
        color: var(--editorial-ink);
        font-size: clamp(1.05rem, 1.7vw, 1.5rem);
        font-weight: 800;
        line-height: 1.25;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }

    .review-excerpt {
        -webkit-line-clamp: 4;
    }

    .reviewer-byline {
        margin-top: auto;
        padding-top: .8rem;
        color: var(--editorial-muted);
        font-size: .76rem;
    }

    .reviewer-byline strong {
        color: var(--editorial-ink);
    }

    .review-read-more {
        margin-top: .45rem;
    }

    .book-review-card:hover {
        box-shadow: 0 10px 24px rgba(28, 49, 35, .1);
        transform: translateY(-3px);
    }

    .author-spotlight-section {
        padding: clamp(1rem, 3vw, 2rem);
        color: #fff;
        background: var(--editorial-panel);
    }

    .author-section-heading {
        border-color: rgba(255, 255, 255, .22);
    }

    .author-section-heading h2,
    .author-section-heading .editorial-kicker {
        color: #fff;
    }

    .author-section-heading .editorial-kicker {
        color: #b9d6c3;
    }

    .author-spotlight-card {
        display: flex;
        height: 100%;
        min-height: 230px;
        flex-direction: column;
        overflow: hidden;
        color: inherit;
        background: rgba(255, 255, 255, .08);
        border: 1px solid rgba(255, 255, 255, .16);
        text-decoration: none;
        transition: background .2s ease;
    }

    .author-portrait {
        position: relative;
        height: 140px;
        overflow: hidden;
        background: rgba(255, 255, 255, .08);
    }

    .author-portrait img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        background: rgba(255, 255, 255, .08);
    }

    .author-portrait-mark {
        position: absolute;
        right: .7rem;
        bottom: .7rem;
        display: grid;
        width: 32px;
        height: 32px;
        color: var(--editorial-green-dark);
        background: #fff;
        border-radius: 50%;
        place-items: center;
    }

    .author-spotlight-copy {
        display: flex;
        flex: 1;
        flex-direction: column;
        align-items: flex-start;
        padding: 1rem;
    }

    .author-feature-label {
        color: #b9d6c3;
    }

    .author-spotlight-copy h3 {
        display: -webkit-box;
        overflow: hidden;
        margin: .45rem 0;
        color: #fff;
        font-size: 1.05rem;
        font-weight: 800;
        line-height: 1.3;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }

    .author-spotlight-copy p {
        color: rgba(255, 255, 255, .78);
        -webkit-line-clamp: 3;
    }

    .author-read-more {
        margin-top: auto;
        padding-top: .8rem;
        color: #d3e7d9;
    }

    .author-featured {
        min-height: 330px;
        flex-direction: row;
        background: rgba(255, 255, 255, .13);
    }

    .author-featured .author-portrait {
        height: auto;
        flex: 0 0 44%;
    }

    .author-featured .author-spotlight-copy {
        justify-content: center;
        padding: clamp(1rem, 2vw, 1.6rem);
    }

    .author-featured .author-spotlight-copy h3 {
        font-size: clamp(1.2rem, 2vw, 1.7rem);
    }

    .author-spotlight-card:hover {
        background: rgba(255, 255, 255, .17);
    }

    .focus-story-section {
        padding: clamp(1rem, 3vw, 2rem);
        color: #fff;
        background: #202723;
    }

    .focus-story-heading {
        display: grid;
        grid-template-columns: 1fr auto;
        align-items: end;
        gap: .3rem 1rem;
        margin-bottom: 1rem;
    }

    .focus-story-index {
        grid-column: 1 / -1;
        color: #b9d6c3;
        font-size: .67rem;
        font-weight: 900;
        letter-spacing: .15em;
    }

    .focus-story-heading h2 {
        margin: 0;
        color: #fff !important;
        font-size: clamp(1.35rem, 2.6vw, 2rem);
        font-weight: 800;
    }

    .focus-story-all {
        color: #d4e4d8;
        font-size: .78rem;
        font-weight: 800;
        text-decoration: none;
    }

    .focus-story-card {
        position: relative;
        display: block;
        min-height: clamp(300px, 39vw, 510px);
        overflow: hidden;
        color: #fff;
        background: #39453f;
        text-decoration: none;
    }

    .focus-story-card > img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .focus-story-shade {
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, rgba(13, 24, 19, .87) 0%, rgba(13, 24, 19, .52) 52%, rgba(13, 24, 19, .08) 100%);
    }

    .focus-story-copy {
        position: absolute;
        inset: auto 0 0;
        display: flex;
        max-width: 620px;
        flex-direction: column;
        align-items: flex-start;
        padding: clamp(1.1rem, 3vw, 2.3rem);
    }

    .focus-story-label {
        color: #d6e3d9;
        font-size: .68rem;
        font-weight: 900;
        letter-spacing: .13em;
    }

    .focus-story-copy strong {
        display: -webkit-box;
        overflow: hidden;
        margin: .6rem 0;
        font-size: clamp(1.4rem, 3.1vw, 2.6rem);
        font-weight: 800;
        line-height: 1.1;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 3;
    }

    .focus-story-description {
        display: -webkit-box;
        overflow: hidden;
        max-width: 540px;
        color: rgba(255, 255, 255, .82);
        line-height: 1.55;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 3;
    }

    .focus-story-read {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        margin-top: 1rem;
        font-size: .8rem;
        font-weight: 800;
    }

    .humoria-section {
        padding: clamp(1rem, 3vw, 2rem);
        background: var(--editorial-humoria-bg);
        border-radius: 28px 4px 28px 4px;
    }

    .humoria-heading {
        border-color: var(--editorial-humoria-line);
    }

    .humoria-eyebrow {
        color: var(--editorial-humoria-link);
        font-size: .68rem;
        font-weight: 900;
        letter-spacing: .12em;
        text-transform: uppercase;
    }

    .humoria-all {
        color: var(--editorial-humoria-link);
        font-size: .8rem;
        font-weight: 800;
        text-decoration: none;
    }

    .humoria-card {
        position: relative;
        display: flex;
        height: 100%;
        min-height: 0;
        flex-direction: column;
        overflow: hidden;
        color: inherit;
        background: var(--editorial-surface);
        border: 1px solid var(--editorial-humoria-card-line);
        border-radius: 18px 4px 18px 4px;
        text-decoration: none;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .humoria-card-number {
        position: absolute;
        z-index: 1;
        top: .65rem;
        left: .7rem;
        display: grid;
        width: 34px;
        height: 34px;
        color: #fff;
        background: var(--editorial-humoria-number);
        border-radius: 50%;
        font-size: .73rem;
        font-weight: 900;
        place-items: center;
    }

    .humoria-image {
        position: relative;
        flex: initial;
        min-height: 0;
        overflow: hidden;
        aspect-ratio: 2 / 1;
        background: var(--editorial-humoria-image);
    }

    .humoria-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .humoria-image-sticker {
        position: absolute;
        right: .7rem;
        bottom: .7rem;
        display: grid;
        width: 38px;
        height: 38px;
        color: var(--editorial-humoria-sticker-ink);
        background: var(--editorial-humoria-sticker);
        border: 2px solid var(--editorial-humoria-sticker-line);
        border-radius: 50%;
        font-size: 1.15rem;
        place-items: center;
        transform: rotate(10deg);
    }

    .humoria-copy {
        display: flex;
        min-width: 0;
        flex: 1;
        flex-direction: column;
        align-items: flex-start;
        padding: 1.25rem;
    }

    .humoria-copy h3 {
        display: -webkit-box;
        overflow: hidden;
        margin: .2rem 0 .6rem;
        color: var(--editorial-humoria-ink);
        font-size: clamp(1.05rem, 1.7vw, 1.45rem);
        font-weight: 900;
        line-height: 1.25;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 3;
    }

    .humoria-copy p {
        color: var(--editorial-humoria-muted);
        -webkit-line-clamp: 4;
    }

    .humoria-read-more {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        margin-top: auto;
        padding-top: .85rem;
        color: var(--editorial-humoria-link);
        font-size: .8rem;
        font-weight: 900;
    }

    .humoria-featured {
        min-height: 0;
        border-radius: 4px 24px 4px 24px;
    }

    .humoria-featured .humoria-image {
        aspect-ratio: 2 / 1;
    }

    .humoria-card:hover {
        box-shadow: 0 12px 24px rgba(111, 73, 35, .12);
        transform: translateY(-4px) rotate(-.3deg);
    }

    @media (max-width: 991px) {
        .headline-layout {
            grid-template-columns: minmax(0, 1.25fr) minmax(220px, .9fr);
            gap: 1.25rem;
        }

        .headline-supporting {
            padding-left: .85rem;
        }

        .headline-supporting-item {
            grid-template-columns: 104px minmax(0, 1fr) 14px;
            gap: .6rem;
        }

        .insight-featured {
            min-height: 320px;
        }
    }

    @media (max-width: 767px) {
        .book-collection-section,
        .video-showcase-section,
        .headline-section,
        .quote-showcase-section,
        .insight-section,
        .book-review-section,
        .author-spotlight-section,
        .focus-story-section,
        .humoria-section {
            margin-block: 2.25rem;
        }

        .editorial-header,
        .headline-section-heading,
        .editorial-section-heading,
        .review-section-heading,
        .author-section-heading,
        .video-showcase-heading,
        .quote-showcase-heading,
        .humoria-heading {
            align-items: flex-start;
        }

        .catalog-book-card {
            min-height: 285px;
        }

        .catalog-book-art {
            min-height: 165px;
            padding: .75rem;
        }

        .catalog-book-action {
            display: none;
        }

        .video-showcase-section {
            padding: 1rem;
        }

        .video-feature {
            grid-template-columns: minmax(0, 1fr);
            gap: .9rem;
        }

        .video-feature-copy h3 {
            margin: .45rem 0;
            font-size: 1.35rem;
        }

        .video-description-desktop {
            display: none;
        }

        .video-description-mobile {
            display: block;
        }

        .video-description-mobile p {
            margin: 0;
        }

        .headline-layout {
            grid-template-columns: minmax(0, 1fr);
            gap: 1.25rem;
        }

        .headline-feature-image {
            aspect-ratio: 4 / 3;
        }

        .headline-supporting {
            padding-left: 0;
            border-left: 0;
        }

        .headline-supporting-item {
            grid-template-columns: 108px minmax(0, 1fr) 16px;
            min-height: 116px;
        }

        .headline-supporting-image {
            aspect-ratio: 4 / 3;
        }

        .quote-showcase-section {
            border-radius: 2px 30px 2px 30px;
        }

        .quote-showcase-body {
            grid-template-columns: 36px minmax(0, 1fr);
            gap: 0;
        }

        .quote-mark {
            position: relative;
            z-index: 1;
            font-size: 5rem;
        }

        .quote-slide {
            min-height: 240px;
            padding: 1rem;
        }

        .insight-card,
        .insight-featured {
            min-height: 0;
            flex-direction: column;
        }

        .insight-image,
        .insight-featured .insight-image {
            flex: initial;
            min-height: 0;
            max-height: none;
            aspect-ratio: 2 / 1;
        }

        .insight-copy {
            padding: 1rem;
        }

        .book-review-section {
            padding: 1rem;
        }

        .review-copy {
            padding: .85rem;
        }

        .review-copy h3 {
            font-size: 1.05rem;
        }

        .review-excerpt {
            -webkit-line-clamp: 3;
        }

        .author-spotlight-section {
            padding: 1rem;
        }

        .author-spotlight-card,
        .author-featured {
            min-height: 260px;
        }

        .author-featured .author-portrait {
            flex-basis: 40%;
        }

        .focus-story-section {
            padding: 1rem;
        }

        .focus-story-card {
            min-height: 370px;
        }

        .focus-story-shade {
            background: linear-gradient(0deg, rgba(13, 24, 19, .9) 0%, rgba(13, 24, 19, .45) 64%, rgba(13, 24, 19, .06) 100%);
        }

        .focus-story-copy {
            padding: 1.1rem;
        }

        .humoria-section {
            padding: 1rem;
            border-radius: 20px 3px 20px 3px;
        }

        .humoria-card,
        .humoria-featured {
            min-height: 0;
            flex-direction: column;
        }

        .humoria-image,
        .humoria-featured .humoria-image {
            flex: initial;
            min-height: 0;
            max-height: none;
            aspect-ratio: 2 / 1;
        }

        .humoria-copy {
            padding: 1rem;
        }
    }

    @media (max-width: 420px) {
        .editorial-header,
        .headline-section-heading,
        .editorial-section-heading,
        .review-section-heading,
        .author-section-heading {
            flex-wrap: wrap;
        }

        .author-featured {
            flex-direction: column;
        }

        .author-featured .author-portrait {
            height: 145px;
            flex: initial;
        }

        .focus-story-heading {
            grid-template-columns: minmax(0, 1fr);
        }

        .focus-story-all {
            justify-self: start;
        }
    }
</style>