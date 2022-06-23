<template>
    <div>
        <div v-if="alert" class="alert alert-success alert-dismissible">
            <a href="#" class="close" data-dismiss="alert" @click="alert=false" aria-label="close">&times;</a>
            {{ toast.success }}
        </div>
        <div class="card">
            <!-- vue js data table -->
            <div class="form-control">
                <div class="form-group pull-left">
                    <div class="form-group">
                        <select id="single-select" v-model="per_page" class="form-control">
                            <option v-for="page in pages" :value="page">{{ page }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group pull-right">
                    <input type="text" class="form-control" v-model="filter" placeholder="Search" @keydown="$event.stopImmediatePropagation()">
                </div>
                <div class="form-group pull-right pr-2">
                    <div class="form-group">
                        <a :href="'/admin/blog/create'" class="btn btn-primary float-right"><i class="fa fa-plus"></i> Create Post</a>
                    </div>
                </div>
                <datatable class="table table-responsive-md" :columns="columns" :data="list" :filter="filter" :per-page="per_page">
                    <template v-slot="{ columns, row }">
                        <tr>
                            <td>
                                <img style="width:30px;" :src="'/storage/blog/' + row.image" @error="$event.target.src='/webapp/imgs/teacher-avatar.png'" :alt="row.image"/>
                            </td>
                            <td v-text="row.title"></td>
                            <td v-text="row.slug"></td>
                            <td>{{ row.released_on | dateParse('YYYY-MM-DD HH:mm:ss') | dateFormat('MM-DD-YYYY hh:mm a') }}</td>
                            <td>{{ row.created_at | dateParse('YYYY-MM-DD HH:mm:ss') | dateFormat('MM-DD-YYYY hh:mm a') }}</td>
                            <td>{{ row.updated_at | dateParse('YYYY-MM-DD HH:mm:ss') | dateFormat('MM-DD-YYYY hh:mm a') }}</td>
                            <td class="text-nowrap">
                            <span class="align-baseline">
                                <a :href="`/admin/blog/${row.id}/edit`" class="btn btn-outline-primary" role="button" title="edit"><i class="fa fa-edit"></i></a>
                                <a :href="`/blog/${row.slug}`" target="_blank" class="btn btn-outline-dark" role="button" title="view"><i class="fa fa-chrome" aria-hidden="true"></i></a>
                                <button @click="showModalDelete(row.id)" class="btn btn-outline-danger" title="click to delete"><i class="fa fa-trash" aria-hidden="true"></i></button>
                            </span>
                            </td>
                        </tr>
                    </template>
                </datatable>
                <div class="pull-left">
                    Total: {{ list.length }} entries
                </div>
                <div class="pull-right">
                    <bootstrap-3-datatable-pager class="pagination" v-model="page" type="abbreviated" :per-page="per_page"></bootstrap-3-datatable-pager>
                </div>
            </div>
            <!-- end of vue js data table -->

            <div v-if="showModal">
                <transition name="modal">
                    <div class="modal-mask">
                        <div class="modal-wrapper">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Delete Blog Record</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true" @click="showModal=false">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Do you want to delete this blog record?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" @click="showModal=false">Cancel</button>
                                        <button type="button" @click="deleteBlog(id)" class="btn btn-danger">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </transition>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: function () {
        return {
            toast: '',
            alert: false,
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

        // createContact: function () {
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

        // showContact: function (id, read) {
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

        // updateContact: function (id) {
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
                .then(function (success) {
                    self.alert = true;
                    self.showModal = false;
                    self.toast = success.data;
                    self.fetchBlogList();
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
    },
}
</script>

<style>
@import '/webapp/css/stylesheet.css';
</style>
