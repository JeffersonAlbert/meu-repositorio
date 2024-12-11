<template>
    <div>
        <h1>Demo Page for Reports/Dre</h1>
        <ul>
            <!-- Iterar sobre os dados passados pelo controlador -->
            <li v-for="item in data" :key="item.id">
                {{ item.nome }} {{ item.tipo }}
            </li>
        </ul>
    </div>
</template>

<script setup>
import { defineProps } from "vue";

const props = defineProps({
    data: Array,
});
</script>
