<template>
    <div class="card" @keydown.esc="closeModals">
        <!-- Complete Past Lessons Modal -->
        <div v-if="showModal" class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-dialog modal-md" role="dialog" aria-modal="true">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Complete all past lessons?</h5>
                            <button type="button" class="close" aria-label="Close" @click="showModal = false">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            Complete all past lessons from
                            <strong>{{ formatDate(dateStart.fromDate) }}</strong>
                            to
                            <strong>{{ formatDate(dateEnd.toDate) }}</strong>?
                            <hr>
                            <div class="text-right">
                                <button class="btn btn-outline-secondary mr-2" @click="showModal = false">
                                    Cancel
                                </button>
                                <button class="btn btn-primary" :disabled="bulkSaving" @click="completePast">
                                    {{ bulkSaving ? 'Updating...' : 'Confirm' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Update Modal -->
        <div v-if="showBulkModal" class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-dialog modal-md" role="dialog" aria-modal="true">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirm Bulk Update</h5>
                            <button type="button" class="close" aria-label="Close" @click="showBulkModal = false">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>
                                Are you sure you want to mark
                                <strong>{{ selectedLessons.length }}</strong>
                                selected lessons as
                                <strong>{{ bulkAction === 'complete' ? 'Completed' : 'Incomplete' }}</strong>?
                            </p>

                            <div class="text-right">
                                <button class="btn btn-outline-secondary mr-2" :disabled="bulkSaving" @click="showBulkModal = false">
                                    Cancel
                                </button>

                                <button class="btn btn-primary" :disabled="bulkSaving" @click="bulkUpdate">
                                    {{ bulkSaving ? 'Updating...' : 'Confirm Update' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lessons Table -->
        <div class="card-body">
            <!-- Toolbar -->
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-3" style="gap: 10px;">
                <div class="d-flex flex-wrap align-items-center">
                    <!-- Per Page -->
                    <div class="form-group mr-3 mb-2">
                        <select v-model.number="per_page" class="form-control" aria-label="Lessons per page">
                            <option v-for="size in pages" :key="size" :value="size">
                                {{ size }}
                            </option>
                        </select>
                    </div>
                    <!-- From Date -->
                    <div class="form-group d-flex align-items-center mr-3 mb-2">
                        <label class="mb-0 mr-2">From</label>
                        <v-date-picker v-model="dateStart.fromDate"
                                       mode="date"
                                       :popover="{ placement: 'bottom-start', autoHide: false, visibility: 'focus' }"
                        >
                            <template v-slot="{ inputValue, inputEvents }">
                                <input
                                    class="form-control"
                                    style="width: 130px;"
                                    :value="inputValue"
                                    v-on="inputEvents"
                                    aria-label="From date"
                                >
                            </template>
                        </v-date-picker>
                    </div>

                    <!-- To Date -->
                    <div class="form-group d-flex align-items-center mr-3 mb-2">
                        <label class="mb-0 mr-2">To</label>
                        <v-date-picker v-model="dateEnd.toDate" mode="date"
                                       :popover="{ placement: 'bottom-start', autoHide: false, visibility: 'focus' }"
                        >
                            <template v-slot="{ inputValue, inputEvents }">
                                <input class="form-control"
                                       style="width: 130px;"
                                       :value="inputValue"
                                       v-on="inputEvents"
                                       aria-label="To date"
                                >
                            </template>
                        </v-date-picker>
                    </div>
                    <!-- Reset -->
                    <div class="form-group mb-2">
                        <button class="btn btn-outline-primary" @click="resetDates">
                            Reset
                        </button>
                    </div>
                </div>

                <div class="d-flex flex-wrap align-items-center">
                    <!-- Search -->
                    <input
                        v-model="filter"
                        type="text"
                        class="form-control mr-2 mb-2"
                        style="width: 200px; flex: none;"
                        placeholder="Search"
                        aria-label="Search lessons"
                        @keydown="$event.stopImmediatePropagation()"
                    >
                    <!-- Bulk Action -->
                    <select
                        v-model="bulkAction"
                        class="form-control mr-2 mb-2"
                        style="width: 160px; flex: none;"
                        :disabled="selectedLessons.length === 0 || bulkSaving"
                        aria-label="Bulk action"
                    >
                        <option value="complete">Mark Completed</option>
                        <option value="incomplete">Mark Incomplete</option>
                    </select>

                    <!-- Bulk Update -->
                    <button
                        class="btn btn-primary mr-2 mb-2"
                        :disabled="selectedLessons.length === 0 || bulkSaving"
                        @click="showBulkModal = true"
                    >
                        Bulk Update ({{ selectedLessons.length }})
                    </button>

                    <!-- Selected Count -->
                    <span
                        v-if="selectedLessons.length"
                        class="badge badge-primary mb-2"
                    >
                        {{ selectedLessons.length }} Selected
                    </span>
                </div>
            </div>
            <!-- Datatable -->
            <datatable
                class="table table-responsive-md table-condensed"
                :columns="columns"
                :data="list"
                :filter="filter"
                :per-page="per_page"
            >
                <template v-slot="{ row }">
                    <tr>
                        <!-- Individual Checkbox -->
                        <td>
                            <input
                                v-model="selectedLessons"
                                type="checkbox"
                                :value="row.id"
                                :disabled="isCancelled(row) || bulkSaving"
                                :aria-label="'Select lesson ' + row.title"
                            >
                        </td>

                        <!-- Completed -->
                        <td>
                            <button
                                v-if="!row.complete"
                                class="btn btn-sm btn-rounded btn-outline-secondary"
                                :disabled="isCancelled(row) || bulkSaving"
                                @click="updateLesson(row.id, row.complete)"
                            >
                                Click to Complete
                            </button>

                            <button
                                v-else
                                class="btn btn-sm btn-rounded btn-primary"
                                :disabled="isCancelled(row) || bulkSaving"
                                @click="updateLesson(row.id, row.complete)"
                            >
                                Completed
                            </button>
                        </td>
                        <!-- Status -->
                        <td>{{ row.status }}</td>
                        <!-- State -->
                        <td>
                            <span v-if="lessonDayStatusPast(row.end_date)" class="badge badge-pill badge-danger">
                                Past
                            </span>
                            <span v-else-if="lessonDayStatusToday(row.end_date)" class="badge badge-pill badge-primary">
                                Today
                            </span>
                            <span v-else class="badge badge-pill badge-warning">
                                Upcoming
                            </span>
                        </td>
                        <!-- Name -->
                        <td>{{ row.title }}</td>
                        <!-- Appointment -->
                        <td>
                            {{ formatDate(row.start_date) }} |
                            {{ row.start_date | dateParse('YYYY-MM-DD HH:mm:ss') | dateFormat('h:mm') }}
                            -
                            {{ row.end_date | dateParse('YYYY-MM-DD HH:mm:ss') | dateFormat('h:mm a') }}
                        </td>
                        <!-- Duration -->
                        <td>{{ row.interval }}</td>
                    </tr>
                </template>
            </datatable>
            <!-- Footer -->
            <total-entries :list="list"></total-entries>

            <div class="pull-right">
                <bootstrap-3-datatable-pager v-model="page" class="pagination" type="abbreviated" :per-page="per_page">
                </bootstrap-3-datatable-pager>
            </div>
        </div>
        <notifications position="bottom right"/>
    </div>
</template>

<script>
import Vue from 'vue';
import TotalEntries from '../TotalEntries';
import {dateParse} from '@vuejs-community/vue-filter-date-parse';
import {dateFormat} from 'vue-filter-date-format';

function getMonthDates() {
    const today = new Date();

    return {
        first: new Date(today.getFullYear(), today.getMonth(), 1),
        last: new Date(today.getFullYear(), today.getMonth() + 1, 0),
    };
}

export default {
    name: 'Lessons',

    components: {
        TotalEntries,
    },

    data() {
        const dates = getMonthDates();
        // Shared reactive state for the datatable's custom header.
        const selection = Vue.observable({
            selected: [],
            eligible: [],
            saving: false,
        });

        const SelectAllHeader = Vue.extend({
            name: 'SelectAllHeader',
            render(h) {
                const eligibleIds = selection.eligible;

                const allSelected = eligibleIds.length > 0 &&
                    eligibleIds.every(id => selection.selected.includes(id));

                return h('input', {
                    attrs: {
                        type: 'checkbox',
                        title: 'Select All',
                        'aria-label': 'Select all lessons',
                    },

                    domProps: {
                        checked: allSelected,
                        disabled: eligibleIds.length === 0 || selection.saving,
                    },

                    on: {
                        change(event) {
                            selection.selected = event.target.checked
                                ? [...eligibleIds]
                                : [];
                        },
                    },
                });
            },
        });

        return {
            dateStart: {
                fromDate: dates.first,
            },
            dateEnd: {
                toDate: dates.last,
            },
            filter: '',
            columns: [
                {label: '', field: 'id', sortable: false, headerComponent: SelectAllHeader,},
                {label: 'Completed', field: 'complete', sortable: false},
                {label: 'Status', field: 'status', sortable: false},
                {label: 'State', field: 'end_date', sortable: false},
                {label: 'Name', field: 'title', sortable: false},
                {label: 'Appointment', field: 'start_date', sortable: false},
                {label: 'Duration', field: 'interval', sortable: false},
            ],
            list: [],
            page: 1,
            per_page: 25,
            pages: [10, 25, 50, 100],
            lesson: {
                id: null,
                complete: null,
                title: null,
                start_date: null,
                end_date: null,
                interval: null,
            },
            showModal: false,
            showBulkModal: false,
            bulkAction: 'complete',
            bulkSaving: false,
            selection,
            todayGetTime: new Date().getTime(),
        };
    },

    computed: {
        selectedLessons: {
            get() {
                return this.selection.selected;
            },
            set(value) {
                this.selection.selected = value;
            },
        },

        selectableLessons() {
            return this.list.filter(row => ! this.isCancelled(row));
        },

        allSelected() {
            return this.selectableLessons.length > 0 &&
                this.selectableLessons.every(row =>
                    this.selectedLessons.includes(row.id)
                );
        },
    },

    watch: {
        dateStart: {
            handler() {
                this.fetchLessonList();
            },
            deep: true,
        },

        dateEnd: {
            handler() {
                this.fetchLessonList();
            },
            deep: true,
        },

        list: {
            handler() {
                this.selection.eligible = this.selectableLessons.map(row => row.id);
            },
            immediate: true,
        },

        bulkSaving(value) {
            this.selection.saving = value;
        },
    },

    mounted() {
        this.fetchLessonList();
    },

    methods: {
        dateFormat,
        dateParse,

        formatDate(value) {
            return value ? new Date(value).toDateString() : '';
        },

        closeModals() {
            if (! this.bulkSaving) {
                this.showModal = false;
                this.showBulkModal = false;
            }
        },

        isCancelled(row) {
            return row.status === 'Cancelled';
        },

        lessonDayStatusPast(endDate) {
            const lessonDate = new Date(endDate);

            return lessonDate.toDateString() !== new Date().toDateString() &&
                lessonDate.getTime() < this.todayGetTime;
        },

        lessonDayStatusToday(endDate) {
            return new Date(endDate).toDateString() === new Date().toDateString();
        },

        lessonDayStatusUpcoming(endDate) {
            return new Date(endDate).getTime() > this.todayGetTime;
        },

        resetDates() {
            const dates = getMonthDates();

            this.dateStart.fromDate = dates.first;
            this.dateEnd.toDate = dates.last;
        },

        showCompleteModal() {
            this.showModal = true;
        },

        async fetchLessonList() {
            this.selectedLessons = [];

            const from = this.formatDate(this.dateStart.fromDate);
            const to = this.formatDate(this.dateEnd.toDate);

            try {
                const response = await axios.get(
                    '/lessons/list/' + encodeURIComponent(from) + '/' + encodeURIComponent(to)
                );

                this.list = response.data.data;

            } catch (error) {
                console.error(error);

                this.$notify({
                    type: 'error',
                    title: 'Error',
                    text: 'Could not load lessons list.',
                    duration: 6000,
                });
            }
        },

        async updateLesson(id, complete) {
            try {
                await axios.patch('/lessons/update/' + id, {
                    complete: ! complete,
                });

                await this.fetchLessonList();

                this.$notify({
                    type: 'success',
                    title: 'Success',
                    text: 'The lesson was updated.',
                    duration: 6000,
                });

            } catch (error) {
                console.error(error);

                this.$notify({
                    type: 'error',
                    title: 'Error',
                    text: 'The lesson failed to update.',
                    duration: 6000,
                });
            }
        },

        async bulkUpdate() {
            if (! this.selectedLessons.length || this.bulkSaving) {
                return;
            }

            this.bulkSaving = true;

            try {
                const response = await axios.patch('/lessons/bulk-update', {
                    ids: this.selectedLessons,
                    complete: this.bulkAction === 'complete',
                });

                this.selectedLessons = [];
                this.showBulkModal = false;

                await this.fetchLessonList();

                this.$notify({
                    type: 'success',
                    title: 'Success',
                    text: response.data.message,
                    duration: 6000,
                });

            } catch (error) {
                console.error(error);

                this.$notify({
                    type: 'error',
                    title: 'Error',
                    text: 'Unable to update the selected lessons.',
                    duration: 6000,
                });

            } finally {
                this.bulkSaving = false;
            }
        },

        async completePast() {
            if (this.bulkSaving) {
                return;
            }

            this.bulkSaving = true;

            try {
                await axios.put('/lessons/update/past', this.list);

                this.showModal = false;

                await this.fetchLessonList();

                this.$notify({
                    type: 'success',
                    title: 'Success',
                    text: 'The past lessons were completed successfully.',
                    duration: 6000,
                });

            } catch (error) {
                console.error(error);

                this.$notify({
                    type: 'error',
                    title: 'Error',
                    text: 'The past lessons failed to complete.',
                    duration: 6000,
                });

            } finally {
                this.bulkSaving = false;
            }
        },
    },
};
</script>
