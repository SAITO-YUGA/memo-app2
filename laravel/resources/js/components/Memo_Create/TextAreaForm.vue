<script setup>
const props = defineProps({
    modelValue: { type : String , required : true}
})
const emit = defineEmits(['update:modelValue'])
</script>
<template>
    <textarea :value = "props.modelValue"
              @input = "emit('update:modelValue',$event.target.value)"
              placeholder = "ここにメモを入力" rows="10"
              class = "w-full p-3 rounded-lg border border-gray-300 text-base leading-relaxed">

    </textarea>
</template>
