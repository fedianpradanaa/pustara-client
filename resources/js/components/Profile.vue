<template>
    <action-bar :data="menu" :menu-label="menu.menu_label"></action-bar>

    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger fade show" role="alert" v-if="change_request =='Y'">
                    Informasi profil sedang menunggu persetujuan Platform. Logo dan favicon tetap dapat diperbarui tanpa persetujuan.
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center flex-column">
                            <div class="avatar avatar-2xl">
                                <img v-if="form.field.logo.small" :src="form.field.logo.small" :alt="form.field.info.application_name || 'Logo perpustakaan'" />
                            </div>

                            <h3 class="mt-3">{{ user.name }}</h3>
                            <p class="text-small">{{ user.email }}</p>

                            <profile-qr-codes :library-name="form.field.info.application_name" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Logo &amp; Favicon</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Logo dan favicon langsung aktif setelah disimpan, tanpa melalui persetujuan Platform.</p>
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <label for="logo-big" class="form-label">Logo Besar</label>
                                <br><img :src="form.field.logo.big" height="100px" v-if="form.field.logo.big" :alt="form.field.info.application_name || 'Logo perpustakaan'" />
                                <p id="logo-big-guidance" class="form-text">PNG atau WebP, maksimal 1500 × 510 px dan 5 MB.</p>
                                <div class="form-group mt-2">
                                    <input id="logo-big" type="file" class="logo-big" aria-describedby="logo-big-guidance" />
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="logo-small" class="form-label">Logo Kecil</label>
                                <br><img :src="form.field.logo.small" height="100px" v-if="form.field.logo.small" :alt="form.field.info.application_name || 'Logo perpustakaan'" />
                                <p id="logo-small-guidance" class="form-text">PNG atau WebP, maksimal 510 × 510 px dan 5 MB.</p>
                                <div class="form-group mt-2">
                                    <input id="logo-small" type="file" class="logo-small" aria-describedby="logo-small-guidance" />
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="logo-favicon" class="form-label">Favicon</label>
                                <br><img :src="form.field.logo.favicon" height="64px" v-if="form.field.logo.favicon" :alt="`Favicon ${form.field.info.application_name || 'perpustakaan'}`" />
                                <p id="favicon-guidance" class="form-text">PNG atau WebP, maksimal 256 × 256 px dan 2 MB; disarankan rasio 1:1.</p>
                                <div class="form-group mt-2">
                                    <input id="logo-favicon" type="file" class="logo-favicon" aria-describedby="favicon-guidance" />
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button
                                type="button"
                                class="btn btn-primary"
                                :disabled="!form.field.logo.newsmall && !form.field.logo.newbig && !form.field.logo.newfavicon"
                                @click="handleUpdateLogo"
                            >Simpan Logo &amp; Favicon</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Informasi Profil Perpustakaan</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info" role="note">
                            Perubahan nama dan informasi perpustakaan akan diajukan ke Platform dan baru berlaku setelah disetujui.
                        </div>
                        <form class="form" @submit.prevent="handleUpdateProfile">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label for="name" class="form-label">Nama Aplikasi</label>
                                        <input type="text" name="name" id="name" class="form-control" v-model="form.field.info.application_name" placeholder="Nama Aplikasi" />
                                    </div>
                                    <div class="form-group">
                                        <label for="name" class="form-label">Kabupaten/Kota</label>
                                        <input type="text" name="name" id="name" class="form-control" v-model="form.field.info.kabupaten_name" placeholder="Kabupaten/Kota" disabled/>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone" class="form-label">Nama Penandatangan MOU <small class="text-danger">*</small></label>
                                        <input type="text" name="phone" id="phone" class="form-control" v-model="form.field.info.mou_sign_name" placeholder="Nama Penandatangan MOU"/>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone" class="form-label">Nama Penanggung Jawab <small class="text-danger">*</small></label>
                                        <input type="text" name="phone" id="phone" class="form-control" v-model="form.field.info.pers_responsible" placeholder="Nama Penanggung Jawab"/>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone" class="form-label">Nama Pengelola (Admin) <small class="text-danger">*</small></label>
                                        <input type="text" name="phone" id="phone" class="form-control" v-model="form.field.info.administrator_name" placeholder="Nama Pengelola" />
                                    </div>
                                    <!-- <div class="form-group">
                                        <label for="email" class="form-label">E-Mail Pengelola (Admin) </label>
                                        <input type="text" name="email" id="email" class="form-control" v-model="user.email" placeholder="E-Mail Pengelola" readonly/>
                                    </div> -->
                                    <div class="form-group">
                                        <label for="password-column">Kata Sandi</label>
                                        <div class="form-group position-relative has-icon-left mb-4">
                                            <input type="password" class="form-control form-control-md" :class="{'is-invalid': errors.password}" placeholder="Kata Sandi" v-model="form.field.info.password"/>
                                            <div class="form-control-icon">
                                                <i class="bi bi-shield-lock"></i>
                                            </div>
                                            <p class="text-subtitle text-muted">Isi jika ingin ubah kata sandi</p>
                                            <div v-if="errors.password" class="invalid-feedback">{{ errors.password[0] }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label for="name" class="form-label">Alamat</label>
                                        <input type="text" name="name" id="name" class="form-control" v-model="form.field.info.address" placeholder="Alamat" />
                                    </div>
                                    <div class="form-group">
                                        <label for="phone" class="form-label">NPWP</label>
                                        <input type="text" name="phone" id="phone" class="form-control" v-model="form.field.info.npwp" placeholder="NPWP" />
                                    </div>
                                    <div class="form-group">
                                        <label for="phone" class="form-label">Jabatan Penandatangan MOU <small class="text-danger">*</small></label>
                                        <input type="text" name="phone" id="phone" class="form-control" v-model="form.field.info.pos_sign_name" placeholder="Jabatan Penandatangan MOU"/>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone" class="form-label">Jabatan Penanggung Jawab <small class="text-danger">*</small></label>
                                        <input type="text" name="phone" id="phone" class="form-control" v-model="form.field.info.pos_pers_responsible" placeholder="Jabatan Penanggung Jawab"/>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone" class="form-label">Nomor HP/WA (Admin) <small class="text-danger">*</small></label>
                                        <input type="text" name="phone" id="phone" class="form-control" v-model="form.field.info.administrator_phone" placeholder="Nomor HP/WA" />
                                    </div>
                                    <div class="form-group">
                                        <label for="password-confirm-column">Ulangi Kata Sandi</label>
                                        <div class="form-group position-relative has-icon-left mb-4">
                                            <input type="password" class="form-control form-control-md" :class="{'is-invalid': errors.passwordConfirm}" placeholder="Ulangi Kata Sandi" v-model="form.field.info.passwordConfirm"/>
                                            <div class="form-control-icon">
                                                <i class="bi bi-shield-lock"></i>
                                            </div>
                                            <div v-if="errors.passwordConfirm" class="invalid-feedback">{{ errors.passwordConfirm[0] }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <div class="form-group my-2 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary" :disabled="(change_request =='Y') ? true : false">Ajukan Perubahan Profil</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

</template>

<script>
import ProfileQrCodes from './ProfileQrCodes.vue'
import * as FilePond from 'filepond'
import 'filepond/dist/filepond.min.css'
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';
import FilePondPluginImageValidateSize from 'filepond-plugin-image-validate-size';

FilePond.registerPlugin(FilePondPluginFileValidateType);
FilePond.registerPlugin(FilePondPluginImagePreview);
FilePond.registerPlugin(FilePondPluginImageValidateSize);

export default {
    components: { ProfileQrCodes },
    data() {
        return {
            menu: {
                menu_label: 'Profil',
                menu_desc: 'Profil Perpustakaan',
                permission: {
                    create: false,
                    read: false,
                    update: false,
                    delete: false,
                    print: false,
                    approve: false,
                },
            },

            user: {},
            change_request: 'N',
            form:{
                field:{
                    logo:{
                        small: '',
                        big: '',
                        favicon: '',
                        newsmall: '',
                        newbig: '',
                        newfavicon: ''
                    },
                    info: {}
                }
            },
            errors: {}
        }
    },

    mounted() {
        // this.__MENU()
        this.userinfo()
        this.getProfile()
        this.getAppInfo()
        this.getChangeRequest()

        const pondBig = FilePond.create(document.querySelector('.logo-big'), {
            credits: null,
            allowImagePreview: true,
            allowMultiple: false,
            allowFileEncode: false,
            required: false,
            storeAsFile: true,
            acceptedFileTypes: ['image/png', 'image/webp'],
            allowImageValidateSize: true,
            imageValidateSizeMaxWidth: 1500,
            imageValidateSizeMaxHeight: 510,
            onerror: (error) => {
                pondBig.removeFiles();

                this.$swal({
                    icon: 'error',
                    title: 'Invalid Image',
                    text: 'The image exceeds the maximum dimensions of 1500x510 pixels.'
                });
            },
            onupdatefiles: (fileItems) => {
                this.form.field.logo.newbig = fileItems.length > 0 ? fileItems[0].file : '';
            }
        })

        const pondSmall = FilePond.create(document.querySelector('.logo-small'), {
            credits: null,
            allowImagePreview: true,
            allowMultiple: false,
            allowFileEncode: false,
            required: false,
            storeAsFile: true,
            acceptedFileTypes: ['image/png', 'image/webp'],
            allowImageValidateSize: true,
            imageValidateSizeMaxWidth: 510,
            imageValidateSizeMaxHeight: 510,
            onerror: (error) => {
                pondSmall.removeFiles();

                this.$swal({
                    icon: 'error',
                    title: 'Invalid Image',
                    text: 'The image exceeds the maximum dimensions of 510x510 pixels.'
                });
            },
            onupdatefiles: (fileItems) => {
                this.form.field.logo.newsmall = fileItems.length > 0 ? fileItems[0].file : '';
            }
        })

        const pondFavicon = FilePond.create(document.querySelector('.logo-favicon'), {
            credits: null,
            allowImagePreview: true,
            allowMultiple: false,
            allowFileEncode: false,
            required: false,
            storeAsFile: true,
            acceptedFileTypes: ['image/png', 'image/webp'],
            allowImageValidateSize: true,
            imageValidateSizeMaxWidth: 256,
            imageValidateSizeMaxHeight: 256,
            onerror: () => {
                pondFavicon.removeFiles();
                this.$swal({
                    icon: 'error',
                    title: 'Gambar favicon tidak valid',
                    text: 'Gunakan PNG atau WebP dengan ukuran maksimal 256 × 256 px.',
                });
            },
            onupdatefiles: (fileItems) => {
                this.form.field.logo.newfavicon = fileItems.length > 0 ? fileItems[0].file : '';
            },
        })
    },

    methods: {
        __MENU() {
            let loader = this.$loading.show()
            window.axios
                .get('/web-access-log/' + this.$route.name)
                .then((response) => {
                    loader.hide()

                    this.menu.menu_label = response.data.menu_label
                    this.menu.menu_desc = response.data.menu_desc
                    this.menu.permission = response.data.permission
                })
                .catch((e) => {
                    loader.hide()

                    console.error(e)
                })
        },

        userinfo() {
            this.user = {}

            window.axios
                .get('/userinfo')
                .then((response) => {
                    this.user = response.data
                })
                .catch((e) => {
                    console.error(e)
                })
        },

        getProfile() {
            let loader = this.$loading.show();
            window.axios
                .get('/setting/profile')
                .then((response) => {
                    loader.hide();
                    this.form.field.info = response.data
                })
                .catch((e) => {
                    loader.hide();
                    console.error(e)
                })
        },

        getAppInfo() {
            window.axios
                .get('/getAppInfo')
                .then((response) => {
                    this.form.field.logo.small = response.data.small_logo_url
                    this.form.field.logo.big = response.data.logo_url
                    this.form.field.logo.favicon = response.data.favicon_url
                })
                .catch((e) => {
                    console.error(e)
                })
        },

        async handleUpdateProfile() {
            if(this.form.field.info.password!=''){
                if (this.form.field.info.password !== this.form.field.info.passwordConfirm) {
                    this.errors.passwordConfirm = ['Kata sandi tidak sesuai!'];
                    return;
                }
            }

            let form_data = new FormData();
            Object.keys(this.form.field.info).forEach(value => {
                form_data.append(value, this.form.field.info[value]);
            });

            let loader = this.$loading.show();
            await axios.post('/setting/profile', form_data)
            .then((response) => {
                loader.hide();
                this.$swal({
                    title: "Update Profile",
                    text: response.data,
                    icon: response.status === 201 ? 'success' : 'error',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showCloseButton: false,
                    showCancelButton: false
                }).then((result) => {
                    location.reload(); 
                });
            })
            .catch(error => {
                loader.hide();
                if (error.response && error.response.status === 400) {
                    this.errors = error.response.data.errors;
                    let errorMessages = '';

                    for (let key in this.errors) {
                        if (this.errors.hasOwnProperty(key)) {
                            errorMessages += `${this.errors[key][0]}\n`;
                        }
                    }

                    this.$swal({
                        icon: 'error',
                        title: 'Kesalahan',
                        text: errorMessages,
                    });
                } else {
                    this.$swal({
                        icon: 'error',
                        title: 'Kesalahan',
                        text: 'Terjadi kesalahan, silakan coba lagi nanti.',
                    });
                }
            });
        },

        async handleUpdateLogo() {
            let form_data = new FormData();
            if (this.form.field.logo.newsmall) {
                form_data.append('logo_small', this.form.field.logo.newsmall);
            }
            if (this.form.field.logo.newbig) {
                form_data.append('logo', this.form.field.logo.newbig);
            }
            if (this.form.field.logo.newfavicon) {
                form_data.append('favicon', this.form.field.logo.newfavicon);
            }

            let loader = this.$loading.show();
            await axios.post('/setting/profile/logo', form_data)
                .then((response) => {
                    loader.hide();
                    this.$swal({
                        title: 'Update Logo',
                        text: response.data,
                        icon: 'success',
                    }).then(() => location.reload());
                })
                .catch((error) => {
                    loader.hide();
                    const message = error.response?.data?.message || error.response?.data || 'Gagal memperbarui logo.';
                    this.$swal({
                        icon: 'error',
                        title: 'Kesalahan',
                        text: typeof message === 'string' ? message : 'Periksa format dan ukuran file logo.',
                    });
                });
        },

        getChangeRequest() {
            this.change_request = 'N';
            let loader = this.$loading.show()
            window.axios.get('/setting/profile/x0y0z0', {
                params: {
                    param: 'change-request',
                }
            })
            .then((response) => {
                loader.hide()
                this.change_request = response.data.change_request
            })
            .catch((e) => {
                loader.hide()
                console.error(e)
            });
        },
    },

    computed: {
        cPermitted() {
            return this.menu.permission.create
        },

        rPermitted() {
            return this.menu.permission.read
        },

        uPermitted() {
            return this.menu.permission.update
        },

        dPermitted() {
            return this.menu.permission.delete
        },

        pPermitted() {
            return this.menu.permission.print
        },

        aPermitted() {
            return this.menu.permission.approve
        },
    },

    watch: {
        'form.field.info.password'(newValue, oldValue) {
            if (this.errors.password) {
                this.errors.password = null;
            }
            if (newValue !== this.form.field.info.passwordConfirm) {
                this.errors.passwordConfirm = ['Kata sandi tidak sesuai!'];
            } else {
                this.errors.passwordConfirm = null;
            }
        },
        'form.field.info.passwordConfirm'(newValue, oldValue) {
            if (this.errors.passwordConfirm) {
                this.errors.passwordConfirm = null;
            }
            if (newValue !== this.form.field.info.password) {
                this.errors.passwordConfirm = ['Kata sandi tidak sesuai!'];
            } else {
                this.errors.passwordConfirm = null;
            }
        }
    }
}
</script>
