<template>
    <div v-on:keydown.esc="closeModal()">
        <div v-if="imageModal">
            <!-- modal create read edit -->
            <transition name="modal">
                <div class="modal-mask">
                    <div class="modal-wrapper">
                        <div class="modal-dialog modal-md" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Update Blog Image</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true" @click="closeModal()">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="#" @submit.prevent="updateBlogImage(blog.id)">
                                        <div class="col-md-12">
                                            <div class="form-group" :class="error_image && classError">
                                                <label for="file" class="control-label">New Image</label>
                                                <input id="file" type="file" class="form-control" @change="onImageChange">
                                                <small>{{ error_image }}</small>
                                            </div>
                                        </div>
                                        <div class="form-group pull-right">
                                            <button type="button" @click="closeModal()" class="btn btn-default">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
            <!-- end of modal create read edit -->
        </div>

        <div class="card">
            <div class="card-body">
                <form action="#" @submit.prevent="updateBlog()">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" :class="error_title && classError">
                                <label for="title">Title</label>
                                <input id="title" v-model.trim="blog.title" type="text" class="form-control">
                                <small>{{ error_title }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" :class="error_slug && classError">
                                <label for="slug">Slug</label>
                                <input id="slug" v-model="slug" type="text" class="form-control" disabled>
                                <small>{{ error_slug }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="body" :class="error_body && classError">Body</label>
                            <wysiwyg id="body" v-model="blog.body"/>
                            <small :class="classError">{{ error_body }}</small>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="image" class="control-label">Featured Image</label>
                                <img id="image" v-if="blog.image" :src="'/storage/blog/' + blog.image" alt="Blog Image" loading="eager"/>
                                <p v-else>No Image</p>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-4">
                        <div class="col-md-3">
                            <div class="form-group" :class="error_released_on && classError">
                                <label for="released_on">Publish Date</label>
                                <v-date-picker id="released_on" v-model="blog.released_on" mode="date">
                                    <template v-slot="{ inputValue, inputEvents }">
                                        <input
                                            class="px-2 py-1 border rounded focus:outline-none focus:border-blue-300 form-control"
                                            :value="inputValue"
                                            v-on="inputEvents"
                                        />
                                    </template>
                                </v-date-picker>
                                <small>{{ error_released_on }}</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" :class="error_release_time && classError">
                                <label for="release_time" class="control-label">Publish Time</label>
                                <select class="form-control" id="release_time" v-model="release_time">
                                    <option v-for="option in options" v-bind:value="option.value">
                                        {{ option.text }}
                                    </option>
                                </select>
                                <small>{{ error_release_time }}</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="update_image" class="control-label">Update Image</label>
                                <button class="btn btn-outline-secondary form-control" type="button" @click="imageModal=true">Click to Choose Image</button>
                            </div>
                        </div>
                    </div>

                    <hr/>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a :href="`/admin/blog/`" class="btn btn-outline-secondary" role="button" title="edit">Cancel</a>
                </form>
            </div>
        </div>
        <notifications position="bottom right"/>
    </div>
</template>

<script>
export default {
    data: function () {
        return {
            blog: {
                id: null,
                author_id: null,
                title: '',
                slug: null,
                body: null,
                image: null,
                released_on: null,
                release_time: null,
                created_at: null,
                updated_at: null,
                deleted_at: null,
            },
            release_time: '09:00:00',
            options: [
                {text: '9:00 am', value: '09:00:00'},
                {text: '12:00 pm', value: '12:00:00'},
                {text: '3:00 pm', value: '15:00:00'}
            ],
            image: null,
            imageModal: false,
            classError: '',
            error_title: '',
            error_slug: '',
            error_body: '',
            error_image: '',
            error_released_on: '',
            error_release_time: '',
        }
    },

    computed: {
        slug: function () {
            return this.sanitizeTitle(this.blog.title);
        }
    },

    mounted: function () {
        this.fetchBlog();
    },

    methods: {
        cancelForm: function () {
            this.blog.title = '';
            this.blog.slug = '';
            this.blog.body = '';
            this.blog.released_on = '';
            this.blog.release_time = '';
            this.removeImage();
            this.clearErrorData();
        },

        clearErrorData: function () {
            let self = this;
            self.classError = '';
            self.error_title = '';
            self.error_slug = '';
            self.error_body = '';
            self.error_image = '';
            self.error_released_on = '';
            self.error_release_time = '';
        },

        closeModal: function () {
            this.imageModal = false;
            this.clearErrorData();
        },

        updateBlog: function () {
            let self = this;
            this.blog.slug = this.slug;
            this.blog.release_time = this.release_time;
            let params = Object.assign({}, self.blog);
            axios.put('/web/blog/' + self.blog.id, params)
                .then(() => {
                    self.clearErrorData();
                })
                .then(() => {
                    this.$notify({
                        type: 'success',
                        title: 'Success',
                        text: 'The blog post was updated.',
                        duration: 10000,
                    })
                })
                .catch((error) => {
                    self.getErrorMessage(error);
                });
        },

        updateBlogImage: function (id) {
            const config = {
                headers: {'content-type': 'multipart/form-data'}
            }
            let self = this;
            axios.post('/web/blog/image/' + id, {
                image: self.image,
                _method: "patch"
            }, config)
                .then((response) => {
                    this.imageModal = false;
                    self.clearErrorData();
                    this.blog.image = response.data.image;
                })
                .then(() => {
                    this.$notify({
                        type: 'success',
                        title: 'Success',
                        text: 'The blog image was updated.',
                        duration: 10000,
                    })
                })
                .catch((error) => {
                    self.getErrorMessage(error);
                });
        },

        fetchBlog() {
            let parameters = this.$route.fullPath;
            let id = parameters.split('/').slice(-2)[0];
            axios.get('/web/blog/' + id + '/edit')
                .then((response) => {
                    this.blog = response.data;
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not load blog post.',
                        duration: 10000,
                    });
                });
        },

        getErrorMessage: function (error) {
            let self = this;
            self.error_title = error.response.data.error.title;
            self.error_slug = error.response.data.error.slug;
            self.error_body = error.response.data.error.body;
            self.error_image = error.response.data.error.image;
            self.error_released_on = error.response.data.error.released_on;
            self.error_release_time = error.response.data.error.release_time;
            self.classError = 'has-error';
        },

        onImageChange: function (e) {
            this.image = e.target.files[0];
        },

        removeImage: function () {
            this.blog.image = '';
            this.$refs.image.value = '';
        },

        sanitizeTitle: function (title) {
            let slug = '';
            let titleLower = title.toLowerCase();
            slug = titleLower.replace(/\s*$/g, '');
            slug = slug.replace(/\s*$/g, '');
            slug = slug.replace(/\s+/g, '-');
            return slug;
        },
    }
}
</script>
<style>
@import "~vue-wysiwyg/dist/vueWysiwyg.css";
</style>
