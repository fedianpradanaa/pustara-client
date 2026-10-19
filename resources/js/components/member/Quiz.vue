<template>
    <div class="page-content quiz-list-page">
		<header class="quiz-library-heading">
			<span class="quiz-eyebrow">AKTIVITAS BELAJAR</span>
			<h1>Kuis</h1>
		</header>
		<section v-if="quizs <= 0" class="quiz-empty-state">
				<div class="card">
					<div class="card-content d-flex flex-column text-center">
						<h1>Tidak Ada Quiz</h1>
					</div>
				</div>
		</section>
		<section v-else class="row quiz-grid" aria-label="Daftar kuis">
				<article v-for="(data, i) in quizs" :key="i" class="col-12 col-lg-4 mb-2 mt-2">
					<div class="card hover-shadow quiz-card">
						<div class="card-content d-flex flex-column">
							<header class="d-flex justify-content-center align-items-center flex-column mt-3 mb-0 mx-4 py-1 quiz-card-heading" :title="data.title">
								<h2 class="mb-0">{{ data.title }}</h2>
							</header>
							<hr>
							<div class="card-body-quiz" style="height: 125px;">
								<p class="card-description-quiz mx-4 mb-0 h-100" style="display: -webkit-box; line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;" :title="data.description">
									{{ data.description }}
								</p>
							</div>
							<div class="quiz-date mx-2">
								<small class="text-muted"><i class="bi bi-calendar-check"></i> Tenggat Waktu : {{ data.start_date }} s/d {{ data.start_date }}</small>
							</div>
							<footer class="card-footer-quiz mb-1">
								<div class="row mx-2">
									<div class="card-date-quiz mb-1">
										<button :class="data.finished ? 'btn btn-success btn-block' : 'btn btn-primary btn-block'" @click="goToQuiz(data.id, data.finished)">{{ data.finished ? 'Lihat Nilai' : 'Lihat Quiz' }}</button>
									</div>
									<div class="card-author-quiz">
										<small class="text-muted"><i class="bi bi-person-circle"></i> <strong>{{ data.name }}</strong></small>
									</div>
								</div>
							</footer>
						</div>
					</div>
				</article>
		</section>
    </div>
</template>

<script>

export default {
    data() {
        return {
            quizs: [],
			param: {
				additional_features: 0
            },
        };
    },

    mounted() {
		this.getParam();
        this.getQuiz();
    },

    methods: {
		getParam() {
            this.param.additional_features = '';

            axios.get('/getParam')
            .then((response) => {
                this.param.additional_features = response.data.additional_features;

				if (this.param.additional_features!=2 && this.param.additional_features!=3) {
					this.$swal({
						title: "Access Denied",
						icon: 'error',
						allowOutsideClick: false,
						allowEscapeKey: false,
						showCloseButton: false,
						showCancelButton: false
					}).then((result) => {
						window.location.href = '/';
					});
				}
            })
            .catch((e) => {
                console.error(e)
            });
        },

		getQuiz() {
			let loader = this.$loading.show();
            this.quizs = [];

            axios.get('/getQuiz')
            .then((response) => {
				loader.hide();
                this.quizs = response.data
            })
            .catch((e) => {
				loader.hide();
                if(e.response.status === 403){
						this.$swal({
							title: e.response.data.message,
							text: "Silahkan verifikasi email, jika klik Kirim Email jika ingin kirim ulang email verifikasi",
							icon: 'warning',
							showCancelButton: true,
							confirmButtonText: 'Kirim Email',
							cancelButtonText: 'Tutup'
						})
						.then(async (result) => {
							
							if (result.isConfirmed) {
								try {
									// Send a request to resend the verification email
									const response = await window.axios.post('/email/resend');
									this.$swal({
										title: 'Email Terkirim!',
										text: response.data.message,
										icon: 'success'
									});
								} catch (error) {
									// Handle error if the resend fails
									this.$swal({
										title: 'Gagal!',
										text: error.response.data.message || 'Terjadi kesalahan saat mengirim email.',
										icon: 'error'
									});
								}
							} else {
								window.location = '/'
							}
						});
					} else {
						console.error(e.message)
					}
            });
        },

		goToQuiz(id, finished) {
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
        }
    },

    computed: {

    },
};
</script>

<style scoped>
	.quiz-list-page {
		padding-top: .5rem;
	}

	.quiz-library-heading {
		margin-bottom: 1.5rem;
		padding-bottom: 1rem;
		border-bottom: 1px solid var(--member-line, #e4e8e2);
	}

	.quiz-library-heading h1 {
		margin: .25rem 0 0;
		font-size: clamp(1.8rem, 4vw, 2.8rem);
	}

	.quiz-eyebrow {
		color: var(--member-accent, #236b58);
		font-size: .72rem;
		font-weight: 800;
		letter-spacing: .12em;
	}

	.quiz-grid {
		--bs-gutter-x: 1.25rem;
		--bs-gutter-y: 1.25rem;
	}

	.quiz-card {
		height: 100%;
		overflow: hidden;
		border-top: 3px solid var(--member-accent, #236b58);
	}

	.quiz-card .card-content {
		height: 100%;
	}

	.quiz-card .card-content > .d-flex:first-child {
		min-height: 84px !important;
		height: auto !important;
		max-height: none !important;
		padding: 1.1rem 1rem .75rem !important;
		align-items: flex-start !important;
	}

	.quiz-card-heading h2 {
		font-size: 1.15rem;
		font-weight: 750;
	}

	.quiz-date {
		margin-bottom: .75rem;
		text-align: center;
	}

	.quiz-card .card-content > .d-flex:first-child h6 {
		font-size: 1.15rem;
		line-height: 1.35;
	}

	.quiz-card hr {
		margin: 0 1.25rem;
		border-color: var(--member-line, #e4e8e2);
	}

	.quiz-card .card-body-quiz {
		height: auto !important;
		min-height: 100px;
		padding: 1rem 1.25rem;
		text-align: left;
	}

	.quiz-card .card-description-quiz {
		margin: 0 !important;
		line-height: 1.6;
	}

	.quiz-card .card-footer-quiz {
		margin-top: auto;
		padding: .9rem 1rem .5rem;
		background: var(--member-soft, #edf4ef);
	}

	.quiz-card .card-footer-quiz .row {
		margin: 0 !important;
	}

	.quiz-card .card-author-quiz {
		margin: .55rem 0 .25rem;
	}

	.quiz-empty-state .card {
		display: grid;
		min-height: 300px;
		place-items: center;
		background: var(--member-soft, #edf4ef);
		border-style: dashed;
		box-shadow: none;
	}

	@media (max-width: 575px) {
		.quiz-card .card-body-quiz {
			min-height: 80px;
		}
	}

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