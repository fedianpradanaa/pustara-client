<template>
    <nav class="navbar navbar-expand navbar-light navbar-top">
        <div class="container-fluid">
            <a href="javascript:void(0)" class="burger-btn d-block">
                <i class="bi bi-justify fs-3"></i>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-lg-0"></ul>
                <div class="dropdown">
                    <button id="admin-account-menu" type="button" class="admin-account" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Buka menu akun">
                        <span class="admin-account-name text-end">
                            <span class="admin-account-title">{{ user.name }}</span>
                            <span class="admin-account-email">{{ user.email }}</span>
                        </span>
                        <span class="admin-account-avatar">
                            <img v-if="smallLogoUrl" :src="smallLogoUrl" :alt="appName || 'Logo perpustakaan'" />
                            <i v-else class="bi bi-person-fill" aria-hidden="true"></i>
                        </span>
                        <i class="bi bi-chevron-down admin-account-chevron" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="admin-account-menu" style="min-width: 11rem">
                        <li>
                            <h6 class="dropdown-header">{{ user.name }}</h6>
                        </li>
                        <li>
							<template v-if="user.role=='teacher'">
								<router-link :to="{ name: 'teacher_account' }" class="dropdown-item"><i class="icon-mid bi bi-person me-2"></i> Profil</router-link>
							</template>
							<template v-else>
								<router-link :to="{ name: 'my_account' }" class="dropdown-item"><i class="icon-mid bi bi-person me-2"></i> Profil Perpustakaan</router-link>
							</template>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);" @click.prevent="logout"><i class="icon-mid bi bi-box-arrow-left me-2"></i> Keluar</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</template>

<script>
export default {
    data() {
        return {
            appName: '',
            smallLogoUrl: '',
        }
    },

	props: {
        user: {
            type: Object,
            required: true
        }
    },

    name: 'headerItems',

    mounted() {
        window.axios
            .get('/getAppInfo')
            .then((response) => {
                this.appName = response.data.app_name
                this.smallLogoUrl = response.data.small_logo_url
            })
            .catch((e) => {
                console.error(e)
            })
    },

    methods: {
        // userinfo() {
        //     this.user = {}

        //     window.axios
        //         .get('/userinfo')
        //         .then((response) => {
        //             this.user = response.data
        //         })
        //         .catch((e) => {
        //             console.error(e)
        //         })
        // },

        logout() {
			let loader = this.$loading.show();
            window.axios.post('/logout').then((e) => {
				loader.hide();
                window.location = '/admin'
            })
        },
    },

    computed: {},
}
</script>
