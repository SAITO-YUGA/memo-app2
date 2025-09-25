import { reactive } from "vue"

const state = reactive({
    toasts : []
})

let idCounter = 0

const addToast = (message, type = "info",duration = 3000 ) => {
    const id = ++idCounter
    state.toasts.push({id, message, type})

    setTimeout(() => {
        removeToast(id)
    }, duration)
}

const removeToast = (id) => {
    state.toasts = state.toasts.filter(t => t.id !== id)
}

export function useToast(){
    return {
        state,
        addToast,
        removeToast,
        success: (msg, duration) => addToast(msg, "success", duration ?? 2500),
        error: (msg, duration) => addToast(msg, "error", duration ?? 3000),
        info: (msg, duration) => addToast(msg, "info", duration ?? 2500)
    }
}
