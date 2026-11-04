<template>
    <div class="card">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <span>Business Hours</span>

            <small v-if="saving" class="text-muted">
                Saving...
            </small>
        </div>

        <div class="card-body">
            <div v-if="loading" class="text-center py-4">
                Loading business hours...
            </div>

            <div v-else class="table-responsive">
                <table class="table align-middle">
                    <thead>
                    <tr>
                        <th>Day of the Week</th>
                        <th>Active</th>
                        <th>Open</th>
                        <th>Close</th>
                        <th>Total</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="row in rows" :key="row.day">
                        <!-- Day -->
                        <th scope="row">
                            {{ row.name }}
                        </th>
                        <!-- Active -->
                        <td>
                            <div class="toggle-switch" data-ts-color="primary">
                                <input :id="'ts' + row.day"
                                    type="checkbox"
                                    v-model="row.active"
                                    :disabled="row.saving"
                                    @change="saveHours(row)"
                                    hidden="hidden"
                                >

                                <label
                                    :for="'ts' + row.day"
                                    class="ts-helper"
                                ></label>
                            </div>
                        </td>
                        <!-- Open -->
                        <td>
                            <select
                                v-model="row.open_time"
                                class="form-control"
                                :disabled="!row.active || row.saving"
                                @change="updateOpenTime(row)"
                            >
                                <option v-for="time in selectHours"
                                    :key="time"
                                    :value="time"
                                >
                                    {{ formatTime(time) }}
                                </option>
                            </select>
                        </td>
                        <!-- Close -->
                        <td>
                            <select
                                v-model="row.close_time"
                                class="form-control"
                                :disabled="!row.active || row.saving"
                                @change="saveHours(row)"
                            >
                                <option
                                    v-for="time in getCloseHours(row)"
                                    :key="time"
                                    :value="time"
                                >
                                    {{ formatTime(time) }}
                                </option>
                            </select>
                        </td>
                        <!-- Total -->
                        <td>
                            <strong v-if="row.active">
                                {{ calculateHours(row) }}
                            </strong>

                            <span v-else class="text-muted">
                                0:00
                            </span>
                        </td>

                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="4" class="text-right">
                            Total Hours
                        </th>

                        <th>
                            {{ totalHours }}
                        </th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <notifications position="bottom right"/>
    </div>

</template>

<script>
export default {
    name: 'BusinessHours',

    data() {
        return {
            loading: true,
            saving: false,

            selectHours: [],

            days: [
                'Sunday',
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday'
            ],

            rows: []
        };
    },

    computed: {
        totalHours() {
            let totalMinutes = 0;
            this.rows.forEach(row => {
                if (! row.active) {
                    return;
                }

                totalMinutes += this.getMinutes(row);
            });

            return this.formatMinutes(totalMinutes);
        }
    },

    mounted() {
        this.getHours();
    },

    methods: {
        async getHours() {
            try {
                const response = await axios.get('/teacher/hours/data');
                const savedHours = response.data.hours;
                this.selectHours = response.data.selectHours;
                this.rows = this.days.map((name, day) => {
                    const saved = savedHours.find(
                        hour => Number(hour.day) === day
                    );

                    return {
                        day: day,
                        name: name,
                        active: saved ? Boolean(Number(saved.active)) : false,
                        open_time: saved
                            ? saved.open_time
                            : this.selectHours[0],
                        close_time: saved
                            ? saved.close_time
                            : this.selectHours[1],
                        saving: false
                    };
                });

            } catch (error) {
                this.showError('Unable to load business hours.');
            } finally {
                this.loading = false;
            }
        },

        async saveHours(row) {
            row.saving = true;
            this.saving = true;

            try {
                const response = await axios.put('/teacher/hours', {
                    day: row.day,
                    active: row.active,
                    open_time: row.open_time,
                    close_time: row.close_time
                });

                this.showSuccess(response.data.message);

            } catch (error) {
                const message = error.response?.data?.errors?.close_time?.[0]
                    || error.response?.data?.message
                    || 'Unable to save business hours.';

                this.showError(message);
            } finally {
                row.saving = false;
                this.saving = this.rows.some(item => item.saving);
            }
        },

        getMinutes(row) {
            if (! row.open_time || ! row.close_time) {
                return 0;
            }

            const open = row.open_time.split(':');
            const close = row.close_time.split(':');

            const openMinutes = Number(open[0]) * 60 + Number(open[1]);

            const closeMinutes = Number(close[0]) * 60 + Number(close[1]);

            return Math.max(0, closeMinutes - openMinutes);
        },

        calculateHours(row) {
            return this.formatMinutes(this.getMinutes(row));
        },

        formatMinutes(minutes) {

            const hours = Math.floor(minutes / 60);
            const remainingMinutes = minutes % 60;

            return `${hours}:${String(remainingMinutes).padStart(2, '0')}`;
        },

        formatTime(time) {
            if (! time) {
                return '';
            }

            const [hour, minute] = time.split(':');
            const date = new Date();

            date.setHours(Number(hour), Number(minute), 0);

            return date.toLocaleTimeString('en-US', {
                hour: 'numeric',
                minute: '2-digit'
            });
        },

        updateOpenTime(row) {
            const openMinutes = this.timeToMinutes(row.open_time);
            const closeMinutes = this.timeToMinutes(row.close_time);

            if (closeMinutes < openMinutes + 60) {
                const closeTime = this.selectHours.find(time =>
                    this.timeToMinutes(time) >= openMinutes + 60
                );

                if (closeTime) {
                    row.close_time = closeTime;
                } else {
                    this.showError('No valid closing time is available.');
                    return;
                }
            }

            this.saveHours(row);
        },

        getCloseHours(row) {
            const openMinutes = this.timeToMinutes(row.open_time);

            return this.selectHours.filter(time => {
                return this.timeToMinutes(time) >= openMinutes;
            });
        },

        timeToMinutes(time) {
            if (!time) {
                return 0;
            }

            const [hours, minutes] = time.split(':');

            return Number(hours) * 60 + Number(minutes);
        },

        showSuccess(message) {
            this.$notify({
                type: 'success',
                title: 'Success',
                text: message,
                duration: 6000,
            });
        },

        showError(message) {
            this.$notify({
                type: 'error',
                title: 'Error',
                text: message,
                duration: 6000,
            });
        }

    }
};
</script>
