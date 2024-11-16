<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="focus:border-indigo-[#458500] h-12 rounded-lg border-gray-300 shadow-sm focus:border-none focus:outline-none focus:ring-[#458500]"
        v-model="model"
        ref="input"
    />
</template>
