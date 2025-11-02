// resources/js/services/notificationService.js

export default {
    notifyWarning(vm, message) {
        vm.$notify({
            type: 'warn',
            title: 'Warning',
            text: message,
            duration: 6000,
        });
    },

    notifySuccess(vm, message) {
        vm.$notify({
            type: 'success',
            title: 'Success',
            text: message,
            duration: 6000,
        });
    },

    notifyError(vm, message) {
        vm.$notify({
            type: 'error',
            title: 'Error',
            text: message,
            duration: 6000,
        });
    },
};
