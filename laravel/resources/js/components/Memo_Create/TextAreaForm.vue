<script setup>
const props = defineProps({
    modelValue: { type : String , required : true}
})
const emit = defineEmits(['update:modelValue', 'enterSave'])
const onInput = (e) => emit('update:modelValue',e.target.value)
const onKeydown = (e) => {
    if (e.key === 'Enter' && !e.shiftKey){
        e.preventDefault()
        emit('enterSave')
    }
}
</script>
<template>
    <textarea :value = "props.modelValue"
              @input = "onInput"
              @keydown = "onKeydown"
              placeholder = "ここにメモを入力
(Enterで保存、Shift+Enterで改行)"
              rows="10"
              class = "w-full p-3 rounded-lg border border-gray-300 text-base leading-relaxed">

    </textarea>
</template>
