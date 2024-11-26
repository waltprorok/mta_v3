<template>
    <div class="card">
        <div class="card-body">
            <form class="form-horizontal" action="#">
                <h5>Application Settings</h5>
                <div class="row">
                    <div class="col-md-3 pt-4">
                        <p>Calendar</p>
                        <div class="form-group">
                            <label for="calendar-display">Default Calendar Display</label>
                            <select id="calendar-display" class="form-control" v-model="settings.calendar" v-on:keydown.enter.prevent
                                    @change="updatedSetting(settings.id)">
                                <option v-for="setting in calendar" :key="setting.id" :value="setting.value">
                                    {{ setting.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <p>Scheduling</p>
                        <div class="card">
                            <div class="card-header">
                                Auto Schedule On For New Active Students
                                <div class="toggle-switch pull-right" data-ts-color="primary">
                                    <input id="auto_schedule_new_active_students" type="checkbox" hidden="hidden" v-model="settings.auto_schedule_new_active_students"
                                           @change="updatedSetting(settings.id)">
                                    <label for="auto_schedule_new_active_students" class="ts-helper"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <notifications position="bottom right"/>
    </div>
</template>

<script>

export default {
    name: 'Settings',
    data: function () {
        return {
            // list: {},
            settings: {
                'calendar': 'month',
                'auto_schedule_new_active_students': false,
            },
            calendar: [
                {value: 'month', name: 'Month'},
                {value: 'listWeek', name: 'List Week'},
                {value: 'agendaWeek', name: 'Agenda Week'},
                {value: 'agendaDay', name: 'Agenda Day'},
            ]
        }
    },

    filters: {
        capitalising: function (data) {
            let capitalized = [];
            data.split(' ').forEach(word => {
                capitalized.push(
                    word.charAt(0).toUpperCase() +
                    word.slice(1).toLowerCase()
                )
            });
            return capitalized.join(' ');
        },
    },

    mounted: function () {
        this.fetchTeacherSettingsList();
    },

    methods: {
        fetchTeacherSettingsList: function () {
            axios.get('/web/teacher-settings')
                .then((response) => {
                    this.settings = response.data;
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not load teacher settings.',
                        duration: 10000,
                    });
                });
        },

        hasSettings: function (id) {
            return id !== undefined;
        },

        saveTeacherSettings: function () {
            let self = this;
            let params = Object.assign({}, self.settings);
            axios.post('/web/teacher-settings', params)
                .then(() => {
                    self.fetchTeacherSettingsList();
                    this.$notify({
                        type: 'success',
                        title: 'Success',
                        text: 'Saved setting.',
                        duration: 10000,
                    });
                })
                .catch((error) => {
                    self.getErrorMessage(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not create billing rate.',
                        duration: 10000,
                    });
                });
        },

        updateTeacherSettings: function (id) {
            let self = this;
            let params = Object.assign({}, self.settings);
            axios.patch('/web/teacher-settings/' + id, params)
                .then(() => {
                    self.fetchTeacherSettingsList();
                    this.$notify({
                        type: 'success',
                        title: 'Success',
                        text: 'Updated setting.',
                        duration: 10000,
                    });
                })
                .catch((error) => {
                    self.getErrorMessage(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not update teacher settings.',
                        duration: 10000,
                    });
                });
        },

        updatedSetting: function (id) {
            return this.hasSettings(id) ? this.updateTeacherSettings(id) : this.saveTeacherSettings();
        }
    }
}
</script>
