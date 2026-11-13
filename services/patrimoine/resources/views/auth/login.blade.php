<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion administrateur - GPE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-800">
    <main class="mx-auto flex min-h-screen max-w-6xl items-center justify-center px-5 py-12">
        <section class="grid w-full max-w-4xl overflow-hidden rounded-3xl bg-white shadow-xl md:grid-cols-2">
            <div class="flex flex-col justify-between bg-slate-900 p-9 text-white md:p-12">
                <a href="/" class="flex items-center gap-3 text-xl font-bold"><span class="grid h-11 w-11 place-items-center rounded-xl bg-blue-600">G</span> GPE.</a>
                <div class="py-12">
                    <p class="mb-3 text-sm font-semibold uppercase tracking-[.2em] text-blue-300">Gestion du patrimoine de l’État</p>
                    <h1 class="text-3xl font-bold leading-tight">Un accès sécurisé à votre espace.</h1>
                    <p class="mt-4 leading-7 text-slate-300">Connectez-vous avec un compte administrateur pour accéder à la gestion des utilisateurs.</p>
                </div>
                <p class="text-sm text-slate-400">Jeton d’accès sécurisé · expiration après 8 heures</p>
            </div>

            <div class="p-8 md:p-12">
                <h2 class="text-2xl font-bold">Connexion</h2>
                <p class="mt-2 text-sm text-slate-500">Saisissez les identifiants de votre compte administrateur.</p>

                @if ($errors->any())
                    <div role="alert" class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-5">
                    @csrf
                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold">Adresse email</label>
                        <input id="email" name="email" type="email" autocomplete="username" required autofocus value="{{ old('email') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                    </div>
                    <div>
                        <label for="password" class="mb-2 block text-sm font-semibold">Mot de passe</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                    </div>
                    <button type="submit" class="w-full rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">Se connecter</button>
                </form>
                <a href="/" class="mt-6 inline-flex text-sm font-medium text-slate-500 hover:text-blue-700">← Retour au tableau de bord</a>
            </div>
        </section>
    </main>
</body>
</html>
