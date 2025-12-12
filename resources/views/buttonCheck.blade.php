<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>{{ $title ?? 'My App' }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="bg-[#fdfdfd] text-gray-900">
        <x-popups.action-successful-m />
        <x-popups.backup-download-successful-m />
        <x-popups.backup-successful-m />
        <x-popups.import-excel-file-m />
        <x-popups.export-file-m />
        <x-popups.logout-m />
        <x-popups.upload-thesis-m />
        <x-popups.first-time-user-login />
        <x-shared.new-sidebar />
        <x-popups.edit-acc />
        <x-popups.confirm-approval-m />
        <x-popups.login-successful-m />
        <x-popups.login-failed-m />
        <x-popups.email-alr-tkn-m />
        <x-popups.email-invalid-m />
        <x-popups.import-restore-file-m />
        <x-popups.account-creation-successful-m />
        <x-popups.add-admin-m />
        <x-popups.email-verified-m />
        <x-popups.scan-option-m />
        <x-popups.image-edit-m />
        <x-popups.user-add-submission-m />
        <x-popups.universal-ok-m />
        <x-popups.universal-x-m />
        <x-popups.admin-first-time-login-change-pass-m />

        <div class="mt-10 flex justify-center">

            <div class="grid grid-cols-5 gap-4">
                <button id="open1" class="rounded-lg bg-blue-600 px-6 py-3 text-white transition hover:bg-blue-700">
                    edit account // admin
                </button>

                <button id="open2" class="rounded-lg bg-blue-600 px-6 py-3 text-white transition hover:bg-blue-700">
                    backup download
                </button>

                <button id="open3" class="rounded-lg bg-blue-600 px-6 py-3 text-white transition hover:bg-blue-700">
                    backup successful
                </button>

                <button id="open4" class="rounded-lg bg-blue-600 px-6 py-3 text-white transition hover:bg-blue-700">
                    confirm delete account
                </button>

                <button id="open5" class="rounded-lg bg-blue-600 px-6 py-3 text-white transition hover:bg-blue-700">
                    confirm delete request
                </button>

                <button id="open6" class="rounded-lg bg-blue-600 px-6 py-3 text-white transition hover:bg-blue-700">
                    export file
                </button>

                <button id="open7" class="rounded-lg bg-blue-600 px-6 py-3 text-white transition hover:bg-blue-700">
                    import excell file
                </button>

                <button id="open8" class="rounded-lg bg-blue-600 px-6 py-3 text-white transition hover:bg-blue-700">
                    logout
                </button>

                <button id="open9" class="rounded-lg bg-blue-600 px-6 py-3 text-white transition hover:bg-blue-700">
                    upload tite
                </button>

                <button id="open10" class="rounded-lg bg-blue-600 px-6 py-3 text-white transition hover:bg-blue-700">
                    first time login //user
                </button>

                <button id="open11" class="rounded-lg bg-blue-600 px-6 py-3 text-white transition hover:bg-blue-700">
                    forgot password
                </button>

                <button id="open12" class="rounded-lg bg-blue-600 px-6 py-3 text-white transition hover:bg-blue-700">
                    confirm approval
                </button>

                <button id="open13" class="rounded-lg bg-blue-600 px-6 py-3 text-white transition hover:bg-blue-700">
                    login succ
                </button>

                <button id="open14" class="rounded-lg bg-blue-600 px-6 py-3 text-white transition hover:bg-blue-700">
                    login failed
                </button>

                <button id="open15" class="rounded-lg bg-blue-600 px-6 py-3 text-white transition hover:bg-blue-700">
                    email taken
                </button>

                <button id="open16" class="rounded-lg bg-blue-600 px-6 py-3 text-white transition hover:bg-blue-700">
                    email invalid
                </button>

                <button id="open17" class="rounded-lg bg-blue-600 px-6 py-3 text-white transition hover:bg-blue-700">
                    import restore file
                </button>

                <button id="open18" class="rounded-lg bg-blue-600 px-6 py-3 text-white transition hover:bg-blue-700">
                    acc creation succ
                </button>

                <button id="open19" class="rounded-lg bg-blue-600 px-6 py-3 text-white transition hover:bg-blue-700">
                    add admin
                </button>

                <button id="open20" class="rounded-lg bg-blue-600 px-6 py-3 text-white transition hover:bg-blue-700">
                    email verified
                </button>

                <button id="open21" class="rounded-lg bg-blue-600 px-6 py-3 text-white transition hover:bg-blue-700">
                    invalid code
                </button>

                <button id="open22" class="rounded-lg bg-blue-600 px-6 py-3 text-white transition hover:bg-blue-700">
                    scan pop-up
                </button>

                <button id="open23" class="rounded-lg bg-blue-600 px-6 py-3 text-white transition hover:bg-blue-700">
                    iamge scane
                </button>

                <button id="open24" class="rounded-lg bg-blue-600 px-6 py-3 text-white transition hover:bg-blue-700">
                    user-acc-eidt waaaaaa
                </button>

                <button id="open25" class="rounded-lg bg-blue-600 px-6 py-3 text-white transition hover:bg-blue-700">
                    user add submission
                </button>

                <button id="open26" class="rounded-lg bg-blue-600 px-6 py-3 text-white transition hover:bg-blue-700">
                    ok
                </button>

                <button id="open27" class="rounded-lg bg-blue-600 px-6 py-3 text-white transition hover:bg-blue-700">
                    X
                </button>

                 <button id="open28" class="rounded-lg bg-blue-600 px-6 py-3 text-white transition hover:bg-blue-700">
                    admin ftl
                </button>

            </div>
        </div>
    </body>

</html>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const openBtn1 = document.getElementById('open1');
        const popup1 = document.getElementById('edit-account-popup');

        const openBtn2 = document.getElementById('open2');
        const popup2 = document.getElementById('backup-download-popup');

        const openBtn3 = document.getElementById('open3');
        const popup3 = document.getElementById('backup-successful-popup');

        const openBtn4 = document.getElementById('open4');
        const popup4 = document.getElementById('confirm-delete-account-popup');

        const openBtn5 = document.getElementById('open5');
        const popup5 = document.getElementById('confirm-delete-request-popup');

        const openBtn6 = document.getElementById('open6');
        const popup6 = document.getElementById('export-file-popup');

        const openBtn7 = document.getElementById('open7');
        const popup7 = document.getElementById('import-excel-popup');

        const openBtn8 = document.getElementById('open8');
        const popup8 = document.getElementById('logout-popup');

        const openBtn9 = document.getElementById('open9');
        const popup9 = document.getElementById('upload-thesis-popup');

        const openBtn10 = document.getElementById('open10');
        const popup10 = document.getElementById('first-time-user-login-popup');

        const openBtn11 = document.getElementById('open11');
        const popup11 = document.getElementById('forgot-pass-popup');

        const openBtn12 = document.getElementById('open12');
        const popup12 = document.getElementById('confirm-approval-popup');

        const openBtn13 = document.getElementById('open13');
        const popup13 = document.getElementById('login-succ-popup');

        const openBtn14 = document.getElementById('open14');
        const popup14 = document.getElementById('login-failed-popup');

        const openBtn15 = document.getElementById('open15');
        const popup15 = document.getElementById('email-taken-popup');

        const openBtn16 = document.getElementById('open16');
        const popup16 = document.getElementById('email-invalid-popup');

        const openBtn17 = document.getElementById('open17');
        const popup17 = document.getElementById('import-restore-popup');

        const openBtn18 = document.getElementById('open18');
        const popup18 = document.getElementById('account-creation-succ-popup');

        const openBtn19 = document.getElementById('open19');
        const popup19 = document.getElementById('add-admin-popup');

        const openBtn20 = document.getElementById('open20');
        const popup20 = document.getElementById('email-verified-popup');

        const openBtn21 = document.getElementById('open21');
        const popup21 = document.getElementById('error-code-popup');

        const openBtn22 = document.getElementById('open22');
        const popup22 = document.getElementById('scan-option-popup');

        const openBtn23 = document.getElementById('open23');
        const popup23 = document.getElementById('image-edit-popup');

        const openBtn24 = document.getElementById('open24');
        const popup24 = document.getElementById('user-edit-account-popup');

        const openBtn25 = document.getElementById('open25');
        const popup25 = document.getElementById('user-add-submission-popup');

        const openBtn26 = document.getElementById('open26');
        const popup26 = document.getElementById('universal-ok-popup');

        const openBtn27 = document.getElementById('open27');
        const popup27 = document.getElementById('universal-x-popup');

        const openBtn28 = document.getElementById('open28');
        const popup28 = document.getElementById('admin-ftl-changepass');



        openBtn1.addEventListener('click', () => {
            const step1 = document.getElementById('ea-step1');
            const step2 = document.getElementById('ea-step2');

            step1.classList.remove('hidden');
            step2.classList.add('hidden');
            popup1.style.display = 'flex';
        });

        openBtn2.addEventListener('click', () => {
            popup2.style.display = 'flex';
        });

        openBtn3.addEventListener('click', () => {
            popup3.style.display = 'flex';
        });

        openBtn4.addEventListener('click', () => {
            const step1 = document.getElementById('cda-step1');
            const step2 = document.getElementById('cda-step2');

            step1.classList.remove('hidden');
            step2.classList.add('hidden');
            popup4.style.display = 'flex';
        });

        openBtn5.addEventListener('click', () => {
            const step1 = document.getElementById('cdr-step1');
            const step2 = document.getElementById('cdr-step2');

            step1.classList.remove('hidden');
            step2.classList.add('hidden');
            popup5.style.display = 'flex';
        });

        openBtn6.addEventListener('click', () => {
            popup6.style.display = 'flex';
        });

        openBtn7.addEventListener('click', () => {
            const step1 = document.getElementById('ie-step-1');
            const step2 = document.getElementById('ie-step-2');

            step1.classList.remove('hidden');
            step2.classList.add('hidden');
            popup7.style.display = 'flex';
        });

        openBtn8.addEventListener('click', () => {
            popup8.style.display = 'flex';
        });

        openBtn9.addEventListener('click', () => {
            const step1 = document.getElementById('pt-step-1');
            const step2 = document.getElementById('pt-step-2');

            step1.classList.remove('hidden');
            step2.classList.add('hidden');
            popup9.style.display = 'flex';
        });

        openBtn10.addEventListener('click', () => {
            popup10.style.display = 'flex';
        });

        openBtn11.addEventListener('click', () => {
            const step1 = document.getElementById('fp-step1');
            const step2 = document.getElementById('fp-step2');

            step1.classList.remove('hidden');
            step2.classList.add('hidden');
            popup11.style.display = 'flex';
        });

        openBtn12.addEventListener('click', () => {
            popup12.style.display = 'flex';
        });

        openBtn13.addEventListener('click', () => {
            popup13.style.display = 'flex';
        });

        openBtn14.addEventListener('click', () => {
            popup14.style.display = 'flex';
        });

        openBtn15.addEventListener('click', () => {
            popup15.style.display = 'flex';
        });

        openBtn16.addEventListener('click', () => {
            popup16.style.display = 'flex';
        });

        openBtn17.addEventListener('click', () => {
            const step1 = document.getElementById('ir-step-1');
            const step2 = document.getElementById('ir-step-2');

            step1.classList.remove('hidden');
            step2.classList.add('hidden');
            popup17.style.display = 'flex';
        });

        openBtn18.addEventListener('click', () => {
            popup18.style.display = 'flex';
        });

        openBtn19.addEventListener('click', () => {
            popup19.style.display = 'flex';
        });

        openBtn20.addEventListener('click', () => {
            popup20.style.display = 'flex';
        });

        openBtn21.addEventListener('click', () => {
            popup21.style.display = 'flex';
        });

        openBtn22.addEventListener('click', () => {
            popup22.style.display = 'flex';
        });

        openBtn23.addEventListener('click', () => {
            popup23.style.display = 'flex';
        });

        openBtn24.addEventListener('click', () => {
            popup24.style.display = 'flex';
        });

        openBtn25.addEventListener('click', () => {
            popup25.style.display = 'flex';
        });

        openBtn26.addEventListener('click', () => {
            popup26.style.display = 'flex';
        });

        openBtn27.addEventListener('click', () => {
            popup27.style.display = 'flex';
        });

        openBtn28.addEventListener('click', () => {
            popup28.style.display = 'flex';
        });

    });
</script>
