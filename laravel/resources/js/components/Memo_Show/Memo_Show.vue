<script setup>
import { ref , onMounted } from "vue"
import DocumentSvg from "./svgs/DocumentSvg.vue"
import TrashSvg from "./svgs/TrashSvg.vue"

const memos = ref([])
const err = ref("")
const fmt = (iso) =>
    new Intl.DateTimeFormat("ja-JP", {
        year: "numeric", month: "2-digit", day: "2-digit",
        hour: "2-digit", minute: "2-digit", hour12: false,
    }).format(new Date(iso))

const fetchMemos = async () => {
    err.value = ""
    try {
        const res = await fetch("/api/memos")
        if (!res.ok) throw new Error("一覧の取得に失敗しました。")
        const data = await res.json()
        memos.value = (data.memos ?? [])
    }catch(e){
        err.value = e.message ?? "不明なエラー"
    }
}

const handleDelete = async(id) => {
    memos.value = memos.value.filter(m => m.id !== id)
    try{
        const res = await fetch(`/api/memos/${id}`,{method: "DELETE"})
        if(!res.ok) throw new Error("削除に失敗しました")
    } catch (e) {
        alert(e.message || "削除に失敗しました")
    }
}
defineExpose({fetchMemos})
onMounted(fetchMemos)
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

        <div class = "space-y-3">
            <article
                v-for = "memo in memos"
                :key = "memo.id"
                class = "group relative bg-white p-4 rounded-lg ring-1 ring-black/5 hover:shadow-md transition cursor-pointer">

                <p class = "text-gray-800 font-medium">{{memo.content}}</p>
                <p class = "text-gray-500 text-sm mt-1">{{ fmt(memo.created_at)}}</p>

                <button type = "button"
                        class = " absolute right-3 top-3 text-red-600 hover:text-red-700 opacity-0 group-hover:opacity-100 transition"
                        @click.stop = "handleDelete(memo.id)"
                        >
                    <TrashSvg/>
                </button>

            </article>

        </div>
    </section>
</template>
