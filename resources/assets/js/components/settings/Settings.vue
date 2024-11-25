<template>
    <div class="card">
        <div class="card-body">
            <form class="form-horizontal" action="#">
            <div class="col-md-3">
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

            <div class="col-md-3">
                <div class="toggle-switch" data-ts-color="primary">
                    <label for="ts2" class="ts-label">Auto Schedule New Active Students</label>
                    <input id="ts2" type="checkbox" hidden="hidden" v-model="settings.auto_schedule_new_active_students"
                    @change="updatedSetting(settings.id)">
                    <label for="ts2" class="ts-helper"></label>
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
                // 'month', 'listWeek', 'agendaWeek', 'agendaDay']
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
                        text: 'Settings created.',
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
                        text: 'Updated teacher settings.',
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
