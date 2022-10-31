<template src="./blog-template.html"></template>

<!--<style>@import '/webapp/css/stylesheet.css';</style>-->

<script>
export default {
    data: function () {
        return {
            classError: '',
            filter: '',
            columns: [
                {label: 'Image', field: 'image',},
                {label: 'Title', field: 'title',},
                {label: 'Slug', field: 'slug',},
                {label: 'Released On', field: 'released_on',},
                {label: 'Created', field: 'created_at',},
                {label: 'Updated', field: 'updated_at',},
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

    // computed: {
    //     hasListData: function () {
    //         return this.list ? this.list.length > 0 : false;
    //     }
    // },

    methods: {
        showModalDelete: function (id) {
            let self = this;
            self.showModal = true;
            self.id = id;
        },

        // clearErrorData: function () {
        //     let self = this;
        //     self.classError = '';
        //     self.error_name = '';
        //     self.error_email = '';
        //     self.error_subject = '';
        //     self.error_message = '';
        // },
        //
        // clearContactData: function () {
        //     let self = this;
        //     self.contact.name = null;
        //     self.contact.email = null;
        //     self.contact.subject = null;
        //     self.contact.message = null;
        //     self.edit = false;
        //     self.showForm = false;
        // },

        // getErrorMessage: function (error) {
        //     let self = this;
        //     self.error_name = error.response.data.error.name;
        //     self.error_email = error.response.data.error.email;
        //     self.error_subject = error.response.data.error.subject;
        //     self.error_message = error.response.data.error.message;
        //     self.classError = 'has-error';
        // },

        fetchBlogList: function () {
            axios.get('/web/blog')
                .then((response) => {
                    this.list = response.data;
                }).catch((error) => {
                console.log(error);
            });
        },

        // createBlog: function () {
        //     let self = this;
        //     let params = Object.assign({}, self.contact);
        //     axios.post('/web/contact', params)
        //         .then(function (success) {
        //             self.alert = true;
        //             self.toast = success.data;
        //             self.clearContactData()
        //             self.clearErrorData();
        //             self.fetchContactList();
        //         })
        //         .catch(function (error) {
        //             self.getErrorMessage(error);
        //         });
        // },

        // showBlog: function (id, read) {
        //     let self = this;
        //     self.showForm = true;
        //     self.read = read;
        //     axios.get('/web/contact/' + id)
        //         .then(function (response) {
        //             self.contact.id = response.data.id;
        //             self.contact.name = response.data.name;
        //             self.contact.email = response.data.email;
        //             self.contact.subject = response.data.subject;
        //             self.contact.message = response.data.message;
        //         })
        //     self.edit = true;
        // },

        // updateBlog: function (id) {
        //     let self = this;
        //     let params = Object.assign({}, self.contact);
        //     axios.patch('/web/contact/' + id, params)
        //         .then(function (success) {
        //             self.alert = true;
        //             self.toast = success.data;
        //             self.clearContactData();
        //             self.clearErrorData();
        //             self.fetchContactList();
        //         })
        //         .catch(function (error) {
        //             self.getErrorMessage(error);
        //         });
        // },

        deleteBlog: function (id) {
            let self = this;
            let params = Object.assign({}, self.contact);
            axios.delete('/web/blog/' + id, params)
                .then(() => {
                    self.showModal = false;
                    self.fetchBlogList();
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
}
</script>
