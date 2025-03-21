<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
</head>
<body class="relative h-screen flex items-center justify-center bg-cover bg-center" 
    style="background-image: url('/gestion_utilisateurs/assets/images/fleurs.jpg');">

    <!-- Overlay pour améliorer la lisibilité du texte -->
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>

    <!-- Contenu -->
    <div class="relative text-white text-center space-y-6 p-6">
        <h1 class="text-4xl sm:text-6xl font-bold animate-fade-in">Bienvenue sur <span class="text-blue-400">Notre Site</span></h1>
        <p class="text-lg sm:text-xl animate-fade-in delay-300">Connectez-vous pour explorer nos services incroyables.</p>

        <!-- Boutons -->
        <div class="flex space-x-4 justify-center mt-6">
            <a href="index.php?action=register" 
                class="px-6 py-3 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-600 transition duration-300 ease-in-out">
                Inscription
            </a>
            <a href="index.php?action=login" 
                class="px-6 py-3 bg-gray-700 text-white font-semibold rounded-lg shadow-md hover:bg-gray-800 transition duration-300 ease-in-out">
                Connexion
            </a>
        </div>

        <!-- Animation Lottie -->
        <lottie-player src="https://assets3.lottiefiles.com/private_files/lf30_e3pteeho.json" 
            background="transparent" speed="1" style="width: 200px; height: 200px; margin: auto;" loop autoplay></lottie-player>
    </div>

    <style>
        /* Animations personnalisées */
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 1s ease-out;
        }
        .delay-300 {
            animation-delay: 0.3s;
        }
    </style>

</body>
</html>
