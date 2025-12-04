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
    emits: ["update:modelValue", "char-count-update"],
    mounted() {
        this.quill = new Quill(this.$refs.editor, {
            theme: "snow",
            placeholder: "Введите текст...",
            modules: {
                toolbar: [
                    //[{'color': []}, {'background': []}],
                    //["bold", "italic", "underline"],
                    //['blockquote'],
                    //[{list: "ordered"}, {list: "bullet"}],
                    ["link"],
                ],
            },
        });

        if (this.modelValue) {
            this.quill.clipboard.dangerouslyPasteHTML(this.modelValue);
        }

        this.quill.on("text-change", () => {
            const html = this.quill.root.innerHTML;
            this.$emit("update:modelValue", html);

            const text = this.quill.getText().trim();
            const charCount = text.replace(/\s/g, '').length;
            this.$emit("char-count-update", charCount);
        });

        // Перехватываем вставку и полностью заменяем стандартное поведение
        this.quill.root.addEventListener('paste', (e) => {
            e.preventDefault();
            e.stopImmediatePropagation();

            const clipboardData = e.clipboardData || window.clipboardData;
            if (!clipboardData) return;

            const range = this.quill.getSelection();
            if (!range) return;

            // Получаем HTML
            let html = clipboardData.getData('text/html');
            const text = clipboardData.getData('text/plain');

            // Удаляем выделенный текст
            if (range.length > 0) {
                this.quill.deleteText(range.index, range.length, 'user');
            }

            let cleanText = '';

            if (html) {
                // Извлекаем чистый текст из HTML с сохранением абзацев
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;

                // Удаляем все теги кроме тех, что обозначают абзацы
                const walker = document.createTreeWalker(
                    tempDiv,
                    NodeFilter.SHOW_ELEMENT,
                    null,
                    false
                );

                let node;
                while (node = walker.nextNode()) {
                    // Удаляем все атрибуты
                    while (node.attributes.length > 0) {
                        node.removeAttribute(node.attributes[0].name);
                    }

                    // Заменяем теги на текстовые маркеры
                    if (node.tagName === 'P' || node.tagName === 'DIV' ||
                        node.tagName === 'BR' || node.tagName === 'LI') {
                        const textNode = document.createTextNode('\n');
                        node.parentNode.insertBefore(textNode, node);
                    }
                }

                // Получаем текст
                cleanText = tempDiv.textContent || tempDiv.innerText || '';
            } else if (text) {
                cleanText = text;
            }

            if (!cleanText) return;

            // Вставляем текст как отдельные абзацы
            const paragraphs = cleanText.split(/\n\s*\n/);
            let currentIndex = range.index;

            paragraphs.forEach((paragraph, i) => {
                const trimmed = paragraph.trim();
                if (trimmed) {
                    // Вставляем текст
                    this.quill.insertText(currentIndex, trimmed, 'user');

                    // Форматируем как абзац
                    this.quill.formatLine(currentIndex, trimmed.length, {
                        'align': '',
                        'direction': '',
                        'blockquote': false,
                        'header': false,
                        'indent': 0,
                        'list': false
                    });

                    currentIndex += trimmed.length;

                    // Добавляем перенос между абзацами
                    if (i < paragraphs.length - 1) {
                        this.quill.insertText(currentIndex, '\n', 'user');
                        currentIndex += 1;
                    }
                }
            });

            // Устанавливаем курсор
            setTimeout(() => {
                this.quill.setSelection(currentIndex, 0, 'silent');
            }, 0);
        }, true); // Используем capture phase
    },
};
</script>

<style>
.ql-editor {
    min-height: 120px;
}

.ql-toolbar {
    background: #1f2937;
    border: 1px solid #374151;
    border-radius: 0.5rem 0.5rem 0 0;
}

.ql-toolbar button {
    color: #d1d5db;
}

.ql-toolbar button:hover,
.ql-toolbar button.ql-active {
    color: #3b82f6;
}

.ql-container {
    background: #111827;
    color: #f9fafb;
    border: 1px solid #374151;
    border-top: none;
    border-radius: 0 0 0.5rem 0.5rem;
    min-height: 400px;
}

.ql-editor {
    font-size: 1rem;
    line-height: 1.5rem;
    color: #f9fafb;
}

/* Отступы для абзацев */
.ql-editor p {
    margin-top: 15px !important;
}

.ql-editor p:first-child {
    margin-top: 0 !important;
}

.ql-editor p:last-child {
    margin-bottom: 0 !important;
}

.ql-editor a {
    color: #60a5fa;
}

.ql-editor.ql-blank::before {
    color: #6b7280;
}
</style>
