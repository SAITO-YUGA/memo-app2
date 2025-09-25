<script setup>
import { useToast } from "@/composables/useToast.js"

const { state, removeToast } = useToast()
</script>

<template>
    <div class = "fixed top-4 right-4 space-y-2 z-50">
        <div
            v-for = "toast in state.toasts"
            :key = "toast.id"
            class = "px-4 py-2 rounded shadow text-white cursor-pointer"
            :class = "{
                'bg-green-600' :toast.type === 'success',
                'bg-red-600' : toast.type === 'error',
                'bg-blue-600': toast.type === 'info',
            }"
            @click = "removeToast(toast.id)"
            >
            {{toast.message}}
        </div>
    </div>
</template>
