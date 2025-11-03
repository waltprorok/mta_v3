<template src="./settings-template.html"></template>

<script>
export default {
    name: 'Settings',
    data: function () {
        return {
            settings: {
                'calendar': 'dayGridMonth',
                'calendar_min_time': '08:00:00',
                'calendar_max_time': '22:00:00',
                'auto_schedule_new_active_students': false,
            },
            calendar: [
                {value: 'dayGridMonth', name: 'Month'}, // 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                {value: 'listWeek', name: 'List Week'},
                {value: 'timeGridWeek', name: 'Agenda Week'},
                {value: 'timeGridDay', name: 'Agenda Day'},
            ],
            calendarTimes: [
                {value: '06:00:00', name: '6:00 am'},
                {value: '07:00:00', name: '7:00 am'},
                {value: '08:00:00', name: '8:00 am'},
                {value: '09:00:00', name: '9:00 am'},
                {value: '10:00:00', name: '10:00 am'},
                {value: '11:00:00', name: '11:00 am'},
                {value: '12:00:00', name: '12:00 pm'},
                {value: '13:00:00', name: '1:00 pm'},
                {value: '14:00:00', name: '2:00 pm'},
                {value: '15:00:00', name: '3:00 pm'},
                {value: '16:00:00', name: '4:00 pm'},
                {value: '17:00:00', name: '5:00 pm'},
                {value: '18:00:00', name: '6:00 pm'},
                {value: '19:00:00', name: '7:00 pm'},
                {value: '20:00:00', name: '8:00 pm'},
                {value: '21:00:00', name: '9:00 pm'},
                {value: '22:00:00', name: '10:00 pm'},
            ],
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
                    this.settings = Object.keys(response.data).length === 0 ? this.settings : response.data;
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not load teacher settings.',
                        duration: 6000,
                    });
                });
        },

        hasSettings: function (id) {
            return id !== undefined;
        },

        saveTeacherSettings: function () {
            let self = this;
            if (self.settings.calendar_min_time > self.settings.calendar_max_time) {
                return this.$notify({
                    type: 'warn',
                    title: 'Warning',
                    text: 'Min time can not be greater than max time.',
                    duration: 6000,
                });
            }
            let params = Object.assign({}, self.settings);
            axios.post('/web/teacher-settings', params)
                .then(() => {
                    self.fetchTeacherSettingsList();
                    this.$notify({
                        type: 'success',
                        title: 'Success',
                        text: 'Saved setting(s).',
                        duration: 6000,
                    });
                })
                .catch((error) => {
                    self.getErrorMessage(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not create teacher setting.',
                        duration: 6000,
                    });
                });
        },

        updateTeacherSettings: function (id) {
            let self = this;
            if (self.settings.calendar_min_time > self.settings.calendar_max_time) {
                return this.$notify({
                    type: 'warn',
                    title: 'Warning',
                    text: 'Min time can not be greater than max time.',
                    duration: 6000,
                });
            }
            let params = Object.assign({}, self.settings);
            axios.patch('/web/teacher-settings/' + id, params)
                .then(() => {
                    self.fetchTeacherSettingsList();
                    this.$notify({
                        type: 'success',
                        title: 'Success',
                        text: 'Updated setting.',
                        duration: 6000,
                    });
                })
                .catch((error) => {
                    self.getErrorMessage(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not update teacher settings.',
                        duration: 6000,
                    });
                });
        },

        updatedSetting: function (id) {
            return this.hasSettings(id) ? this.updateTeacherSettings(id) : this.saveTeacherSettings();
        }
    }
}
</script>
