<script setup>
import { ref , onMounted , watch } from "vue"
import {useToast} from "@/composables/useToast.js";
import DocumentSvg from "../svgs/DocumentSvg.vue"
import Memo_Card from "./Memo_Card.vue"

const memos = ref([])
const q = ref("")
const page = ref(1)
const favoriteOnly = ref(false)
const meta = ref({
    current_page: 1,
    last_page: 1,
    per_page: 10,
    total: 0,
    from: null,
    to: null,
})

let timer = null
const toast = useToast()
const fetchMemos = async () => {
    try{
        const params = new URLSearchParams()
        if(q.value.trim()) params.set("q",q.value.trim())
        params.set("page", String(page.value))
        if(favoriteOnly.value) params.set("favorite","1")
        const res = await fetch (`/api/memos?${params.toString()}`)
        if(!res.ok) toast.error("一覧の取得に失敗しました。")
        const data = await res.json()
        memos.value = data.memos ?? []
        meta.value = data.meta ?? meta.value
        if(data?.meta?.current_page) page.value = data.meta.current_page
    } catch (e) {
        toast.error(e.message || "不明なエラー")
    }
}

const doSearch = async() => {
    clearTimeout(timer)
    page.value = 1
    await fetchMemos()
}

const goPage = (n) => {
    if(n < 1 || n > meta.value.last_page || n === page.value) return
    page.value = n
    fetchMemos()
}

const paginationRange = (current, last) => {
    const delta = 2;
    let start = Math.max(1, current - delta);
    let end = Math.min(last, current + delta);

    while (end - start < 4 && (start > 1 || end < last)){
        if(start > 1) start--;
        else if (end < last) end++;
        else break;
    }
    const arr = [];
    for(let i = start; i <= end; i++) arr.push(i);
    return arr;
}
const nfmt = (n) => new Intl.NumberFormat("ja-JP").format(n ?? 0)
defineExpose({ fetchMemos})
onMounted(fetchMemos)

watch(q, () =>{
    clearTimeout(timer)
    page.value = 1
    timer = setTimeout(fetchMemos, 300)
})
watch(favoriteOnly, () => {
    page.value = 1
    fetchMemos()

})
</script>
<template>
    <section class = "w-full max-w-2xl mx-auto mt-2 bg-orange-50 rounded-xl p-6">
        <div class = "flex items-center gap-2 mb-4">
            <DocumentSvg class = "w-6 h-6 text-orange-500"/>
            <h2 class = "text-lg font-bold">保存されたメモ</h2>
            <span class = "ml-auto inline-flex items-center rounded-full bg-orange-100 text-orange-700 px-3 py-1 text-sm font-semibold">
                {{ nfmt(meta.total)}}件
            </span>
        </div>
        <div v-if = "meta.total > 0" class = "mb-4 text-right text-sm text-gray-600">
            {{ nfmt(meta.from)}} - {{nfmt(meta.to)}} / {{nfmt(meta.total)}} 件
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
        <div class = "flex items-center gap-3 mb-4">
            <label class = "inline-flex items-center gap-2 text-sm text-gray-700">
                <input type = "checkbox" v-model = "favoriteOnly" />
                お気に入りのみ
            </label>
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

        <div v-if = "meta.last_page > 1"
             class = "mt-6 flex items-center justify-center gap-2">
            <button
                class = "px-3 py-1 rounded border bg-white hover:bg-gray-50 disabled:opacity-50"
                :disabled = "page <= 1"
                @click = "goPage(page - 1)">
                前へ
            </button>

            <button
                v-for="n in paginationRange(meta.current_page, meta.last_page)"
                :key="n"
                class="px-3 py-1 rounded border"
                :class="n === page ? 'bg-blue-600 text-white border-blue-600' : 'bg-white hover:bg-gray-50'"
                @click="goPage(n)">
                {{ n }}
            </button>

            <button
                class = "px-3 py-1 rounded border bg-white hover:bg-gray-50 disabled:opacity-50"
                :disabled = "page >= meta.last_page"
                @click = "goPage(page + 1)">
                次へ
            </button>

        </div>
    </section>
</template>
