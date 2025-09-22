<script setup>
import { ref } from "vue"
import Header from "./components/Header.vue"
import Memo_Create from "./components/Memo_Create/Memo_Create.vue"
import Memo_Show from "./components/Memo_Show.vue"
const memoShowRef = ref(null)
const handleSaved = () => {
    memoShowRef.value?.fetchMemos()
}
</script>
<template>
    <Header/>
    <Memo_Create @saved = "handleSaved"/>
    <Memo_Show ref = "memoShowRef"/>
</template>
