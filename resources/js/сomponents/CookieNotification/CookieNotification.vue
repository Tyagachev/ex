<template>
    <div class="content">
        <h1>Ваш сайт</h1>
        <p>Основное содержимое страницы...</p>
        <p>Пользователь может свободно перемещаться по сайту, а окно будет следовать за ним.</p>
    </div>

    <!-- Окно с уведомлением -->
    <div class="cookie-popup" v-if="showCookiePopup">
        <p>Мы используем файлы cookie для улучшения работы сайта.
            Оставаясь на нашем сайте, вы соглашаетесь с условиями использования файлов cookie.</p>
        <p>Подробнее читайте в нашем
            <span class="cookie-link" @click="openAgreement">Пользовательском соглашении</span></p>
        <button class="accept-cookie" @click="acceptCookies">Хорошо</button>
    </div>
</template>

<script>
export default {
    name: "CookieNotification",
    data() {
        return {
            showCookiePopup: false
        }
    },
    mounted() {
        this.checkCookies()
    },
    methods: {
        setCookie(name, value, days) {
            let expires = "";
            if (days) {
                const date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "") + expires + "; path=/";
        },
        getCookie(name) {
            const nameEQ = name + "=";
            const ca = document.cookie.split(';');
            for(let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) === 0) {
                    const value = c.substring(nameEQ.length, c.length);
                    return value;
                }
            }
            return null;
        },

        checkCookies() {
            const cookiesAccepted = this.getCookie('cookiesAccepted');

            if (cookiesAccepted !== 'true') {
                // Задержка для лучшего UX
                setTimeout(() => {
                    this.showCookiePopup = true;
                }, 1000);
            } else {
                this.showCookiePopup = false;
            }
        },

        // Обработчик кнопки "Хорошо"
        acceptCookies() {
            // Сохраняем согласие в cookie на 365 дней
            this.setCookie('cookiesAccepted', 'true', 365);

            // Проверяем, что cookie установился
            setTimeout(() => {
                const checkCookie = this.getCookie('cookiesAccepted');
            }, 100);

            this.showCookiePopup = false;
        },

        openAgreement() {
            // Сохраняем согласие при переходе по ссылке
            this.setCookie('cookiesAccepted', 'true', 365);
            this.showCookiePopup = false;

            // Открываем соглашение (замените на ваш URL)
            alert('Открывается пользовательское соглашение');
            // window.open('/agreement.html', '_blank');
            // или
            // window.location.href = '/agreement.html';
        }
    }
}
</script>

<style scoped>
.cookie-popup {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    width: 90%;
    max-width: 600px;
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.3);
    z-index: 1000;
    border: 2px solid #4CAF50;
}

.cookie-popup p {
    margin: 0 0 15px 0;
    line-height: 1.5;
    font-size: 14px;
}

.accept-cookie {
    background: #4CAF50;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    margin-right: 10px;
    font-size: 14px;
}

.cookie-link {
    color: #0066cc;
    text-decoration: underline;
    cursor: pointer;
}

.cookie-link:hover {
    color: #004499;
}
</style>
