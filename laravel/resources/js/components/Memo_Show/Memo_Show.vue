<script setup>
import { ref , onMounted , watch } from "vue"
import {useToast} from "@/composables/useToast.js";
import DocumentSvg from "../svgs/DocumentSvg.vue"
import Memo_Card from "./Memo_Card.vue"

const memos = ref([])
const q = ref("")
let timer = null
const toast = useToast()
const fetchMemos = async () => {
    try{
        const params = new URLSearchParams()
        if(q.value.trim()) params.set("q",q.value.trim())
        const res = await fetch (`/api/memos?${params.toString()}`)
        if(!res.ok) toast.error("一覧の取得に失敗しました。")
        const data = await res.json()
        memos.value = data.memos ?? []
    } catch (e) {
        toast.error(e.message || "不明なエラー")
    }
}

const doSearch = async() => {
    clearTimeout(timer)
    await fetchMemos()
}

const inputEl = ref(null)
const clearSearch = async () => {
    q.value = ""
    await fetchMemos()
    inputEl.value?.focus()
}

defineExpose({ fetchMemos})
onMounted(fetchMemos)

watch(q, () =>{
    clearTimeout(timer)
    timer = setTimeout(fetchMemos, 300)
})
</script>
<template>
    <section class = "w-full max-w-2xl mx-auto mt-2 bg-orange-50 rounded-xl p-6">
        <div class = "flex items-center gap-2 mb-4">
            <DocumentSvg class = "w-6 h-6 text-orange-500"/>
            <h2 class = "text-lg font-bold">保存されたメモ</h2>
            <span class = "ml-auto inline-flex items-center rounded-full bg-orange-100 text-orange-700 px-3 py-1 text-sm font-semibold">
                {{memos.length}}件
            </span>
        </div>
        <div class = "flex gap-2 mb-4">
            <input
                v-model = "q"
                type = "search"
                placeholder = "メモの内容を検索 (Enterで検索）"
                class = "w-full rounded-lg border border-gray-300 px-3 py-2"
                @keydown.enter.prevent = "doSearch"
            >
            <button
                class = "px-4 py-2 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 disabled:opacity-50"
                :disabled = "!q.trim()"
                @click = "doSearch"
                title = "検索">
                検索
            </button>
        </div>
        <hr class="border-t border-gray-300 my-4" />
        <div class = "space-y-3" v-if = "memos.length">
            <Memo_Card
                v-for = "memo in memos"
                :key = "memo.id"
                :memo = "memo"
                @updated = "fetchMemos"
                @deleted = "fetchMemos"
                />
        </div>
        <p v-else class = "text-gray-500">メモがありません</p>
    </section>
</template>
