<script setup>
import { ref } from "vue"
import Memo_Create_Header from "./Memo_Create_Header.vue"
import TextAreaForm from "./TextAreaForm.vue"
import Memo_Save from "./Memo_Save.vue"

const content = ref("")
const emit = defineEmits(["saved"])
const handleUpdate = (val) => {
    content.value = val
}
const handleSave = async () => {
    try {
        const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content
        const res = await fetch("/api/memo",{
            method : "POST",
            headers : {
                "Content-Type" : "application/json",
                "X-CSRF-TOKEN" : csrfToken,
            },
            body : JSON.stringify({
                content : content.value,
            }),

        })
        if(!res.ok) throw new Error("保存に失敗しました")

        content.value = ""
        alert("保存しました")
        emit("saved")
    }catch(err){
        console.error(err)
        alert("エラーが発生しました")
    }
}
</script>
<template>
 <section class = "bg-white rounded-xl w-full max-w-2xl mx-auto mt-w">
     <div class = "bg-white rounded-xl w-full max-w-2xl">
         <div class = "border-b px-6 py-2">
             <Memo_Create_Header/>
         </div>
         <div class = "px-6 py-4">
             <TextAreaForm v-model = "content"/>
         </div>
         <div class = "border-t px-6 py-4 flex justify-end">
             <Memo_Save :disabled = "!content" @save="handleSave"></Memo_Save>
         </div>
     </div>
 </section>
</template>
