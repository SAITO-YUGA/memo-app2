<script setup>
import { ref } from "vue"
import { useToast } from "@/composables/useToast.js"
import TrashSvg from "../svgs/TrashSvg.vue"
import EditSvg from "../svgs/EditSvg.vue"

const props = defineProps({
    memo: {type: Object, required: true }
})
const emit = defineEmits(["updated","deleted"])
const isEditing = ref(false)
const editContent = ref(props.memo.content)
const toast = useToast()

const fmt = (iso) =>
    new Intl.DateTimeFormat("ja-JP", {
        year: "numeric", month: "2-digit", day: "2-digit",
        hour: "2-digit", minute: "2-digit", hour12: false,
    }).format(new Date(iso))

const saveEdit = async () => {
    try{
        const res = await fetch(`/api/memos/${props.memo.id}`,{
            method: "PATCH",
            headers: {"Content-Type" : "application/json"},
            body: JSON.stringify({ content : editContent.value})
        })
        if(!res.ok) return toast.error("更新に失敗しました")

        isEditing.value = false
        emit("updated")
        toast.success("更新しました")
    } catch(e){
        toast.error(e.message || "更新に失敗しました")
    }
}

const toggleFavorite = async() => {
    try {
        const res = await fetch(`/api/memos/${props.memo.id}/favorite`, {
            method: "PATCH",
            headers: {"Content-Type": "application/json"},
        })
        if (!res.ok) throw new Error("お気に入りの更新に失敗しました")
        emit("updated")
    } catch (e) {
        toast.error(e.message || "お気に入りの更新に失敗しました")
    }
}
const handleDelete = async() => {
    try {
        const res = await fetch(`/api/memos/${props.memo.id}`, {method: "DELETE"})
        if (!res.ok) return toast.error("削除に失敗しました")
        emit("deleted")
        toast.info("削除しました。")
    } catch (e) {
        toast.error(e.message || "削除に失敗しました")
    }
}

</script>

<template>
    <article class = "group relative bg-white p-4 rounded-lg ring-1 ring-black/5 hover:shadow-md transition">

        <div v-if = "isEditing">
            <textarea
                v-model = "editContent"
                class = "w-full border rounded p-2"
                rows = "3"
            ></textarea>
            <div class = "flex gap-2 mt-2">
                <button
                    @click = "saveEdit"
                    class = "px-3 py-1 bg-blue-600 text-white rounded">
                    保存
                </button>
                <button
                    @click = "isEditing = false"
                    class = "px-3 py-1 bg-gray-300 rounded">
                    キャンセル
                </button>
            </div>
        </div>

        <div v-else>
            <div v-if="props.memo.is_favorite" class="text-yellow-500 text-sm font-semibold mb-1">
                ★
            </div>
            <p class = "text-gray-800 font-medium">{{props.memo.content}}</p>
            <p class = "text-gray-500 text-sm mt-1">{{ fmt(props.memo.created_at)}}</p>
        </div>
        <div
            v-if = "!isEditing"
            class = "absolute right-3 top-3 flex gap-2 opacity-0 group-hover:opacity-100 transition">
            <button
                @click.stop = "toggleFavorite"
                class = "hover:scale-105 transition"
                :title = "props.memo.is_favorite ? 'お気に入り解除' : 'お気に入りに追加'"
                aria-label = "お気に入り">
                ⭐️
            </button>
            <button
                @click.stop = "isEditing = true"
                class = "text-blue-600 hover:text-blue-800"
                title = "編集">
                <EditSvg/>
            </button>
            <button
                @click.stop = "handleDelete"
                class = "text-red-600 hover:text-red-800"
                title = "削除">
                <TrashSvg />
            </button>
        </div>
    </article>
</template>
