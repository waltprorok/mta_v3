<template>
    <div>
        <div class="card">
            <div class="card-body">
                <form action="#" @submit.prevent="createBlog()">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group" :class="error_title && classError">
                                <label for="title">Title</label>
                                <input id="title" v-model.trim="blog.title" type="text" class="form-control">
                                <small>{{ error_title }}</small>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group" :class="error_slug && classError">
                                <label for="slug">Slug</label>
                                <input id="slug" v-model.trim="slug" type="text" class="form-control" disabled>
                                <small>{{ error_slug }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group" :class="error_released_on && classError">
                                <label for="released_on">Publish Date</label>
                                <v-date-picker v-model="blog.released_on" mode="date">
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
                        <div class="col-sm-3">
                            <div class="form-group" :class="error_release_time && classError">
                                <label for="start_time" class="control-label">Publish Time</label>
                                <select class="form-control" id="release_time" v-model="blog.release_time">
                                    <option value="09:00:00">9:00 am</option>
                                    <option value="12:00:00">12:00 pm</option>
                                    <option value="15:00:00">3:00 pm</option>
                                </select>
                                <small>{{ error_release_time }}</small>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group" :class="error_image && classError">
                                <label for="image" class="control-label">Image</label>
                                <input type="file" class="form-control" ref="image" @change="onImageChange">
                                <small>{{ error_image }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="body" :class="error_body && classError">Body</label>
                            <wysiwyg v-model="blog.body"/>
                            <small :class="classError">{{ error_body }}</small>
                        </div>
                    </div>
                    <hr/>
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-outline-secondary" @click="cancelForm">Cancel</button>
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
                title: '',
                slug: null,
                body: null,
                image: null,
                released_on: null,
                release_time: null,
            },
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

        createBlog: function () {
            const config = {
                headers: {'content-type': 'multipart/form-data'}
            }
            let self = this;
            this.blog.slug = this.slug;
            let params = Object.assign({}, self.blog);
            console.log(params);
            axios.post('/web/blog', params, config)
                .then(() => {
                    self.cancelForm()
                })
                .then(() => {
                    this.$notify({
                        type: 'success',
                        title: 'Success',
                        text: 'The blog post was created.',
                        duration: 10000,
                    })
                })
                .catch((error) => {
                    self.getErrorMessage(error);
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
            this.blog.image = e.target.files[0];
        },

        removeImage: function () {
            this.blog.image = '';
            this.$refs.image.value = '';
        },

        sanitizeTitle: function (title) {
            let slug = "";
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
