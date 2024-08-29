<template src="./blog-template.html"></template>

<script>
import TotalEntries from "../../TotalEntries";
import {dateParse} from "@vuejs-community/vue-filter-date-parse";
import {dateFormat} from "vue-filter-date-format";

export default {
    data: function () {
        return {
            classError: '',
            filter: '',
            columns: [
                {label: 'ID', field: 'id',},
                {label: 'Image', field: 'image',},
                {label: 'Title', field: 'title',},
                {label: 'Slug', field: 'slug',},
                {label: 'Author', field: 'author',},
                {label: 'Published', field: 'released_on',},
                {label: 'Created', field: 'created_at',},
                {label: 'Actions', filterable: false,}
            ],
            showModal: false,
            list: [],
            page: 1,
            per_page: 10,
            pages: [10, 25, 50, 100],
            blog: {
                id: null,
                title: null,
                slug: null,
                released_on: null,
                release_time: null,
                updated_at: null,
                image: null,
                body: null,
            },
            error_name: '',
            error_email: '',
            error_subject: '',
            error_message: '',
        }
    },

    mounted: function () {
        this.fetchBlogList();
    },

    components: {
        TotalEntries
    },

    methods: {
        dateFormat,
        dateParse,
        showModalDelete: function (id) {
            let self = this;
            self.showModal = true;
            self.id = id;
        },

        fetchBlogList: function () {
            axios.get('/web/blogs')
                .then((response) => {
                    this.list = response.data;
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not load blog list.',
                        duration: 10000,
                    });
                });
        },

        deleteBlog: function (id) {
            let self = this;
            let params = Object.assign({}, self.contact);
            axios.delete('/web/blog/' + id, params)
                .then(() => {
                    self.showModal = false;
                    self.fetchBlogList();
                    this.$notify({
                        type: 'warn',
                        title: 'Deleted',
                        text: 'The blog was deleted.',
                        duration: 10000,
                    })
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not delete blog entry.',
                        duration: 10000,
                    });
                });
        },
    },
}
</script>
