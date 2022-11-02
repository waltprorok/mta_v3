<template>
    <div>
        <div class="text-center pb-4" v-show="!hasListData">No Data</div>
        <div class="pull-left" v-show="hasListData">
            Total: {{ list.length }} entries
        </div>
    </div>
</template>

<script>
export default {
    name: 'TotalEntries',
    props: {
        list: Array,
    },

    computed: {
        hasListData() {
            return this.list ? this.list.length > 0 : false;
        }
    },

}

</script>
