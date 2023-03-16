<template>
    <span v-if="status === 'up'"
          class="text-success">

            正常
    </span>

    <span v-else-if="status === 'down'"
          class="text-danger">
            离线
    </span>

    <span v-else-if="status === 'maintenance'"
          class="text-warning">
            维护

    </span>

</template>

<script setup>


defineProps({
    status: {
        type: String,
        required: true
    }
})


</script>
