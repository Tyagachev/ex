<template>
    <div>
        <div ref="editor" class="border rounded p-2 min-h-[100px]"></div>
    </div>
</template>

<script>
import Quill from "quill";
import "quill/dist/quill.snow.css";

export default {
    name: "QuillEditor",
    props: ["modelValue"],
    mounted() {
        this.quill = new Quill(this.$refs.editor, {
            theme: "snow",
            placeholder: "Введите текст...",
            modules: {
                toolbar: [
                    /*[{ 'color': [] }, { 'background': [] }],*/
                    ["bold", "italic", "underline"],
                    ['blockquote'],
                    [{ list: "ordered" }, { list: "bullet" }],
                    ["link"],
                ],
            },
        });

        if (this.modelValue) {
            this.quill.clipboard.dangerouslyPasteHTML(this.modelValue);
        }

        this.quill.on("text-change", () => {
            this.$emit("update:modelValue", this.quill.root.innerHTML);
        });

    },
};
</script>

<style>
.ql-editor {
    min-height: 120px;
}

.ql-toolbar {
    background: #1f2937; /* тёмно-серый */
    border: 1px solid #374151;
    border-radius: 0.5rem 0.5rem 0 0;
}

.ql-toolbar button {
    color: #d1d5db; /* светло-серый */
}

.ql-toolbar button:hover,
.ql-toolbar button.ql-active {
    color: #3b82f6; /* синий при hover/active */
}

/* Container */
.ql-container {
    background: #111827; /* почти чёрный */
    color: #f9fafb;
    border: 1px solid #374151;
    border-top: none;
    border-radius: 0 0 0.5rem 0.5rem;
    min-height: 400px;
}

/* Текст внутри */
.ql-editor {
    font-size: 1rem;
    line-height: 1.5rem;
    color: #f9fafb;
}

.ql-editor a {
    color: #60a5fa; /* голубые ссылки */
}

/* Placeholder */
.ql-editor.ql-blank::before {
    color: #6b7280; /* серый */
}
</style>
