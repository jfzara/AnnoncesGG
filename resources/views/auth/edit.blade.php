<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Profil - AnnoncesGG</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #f0f2f5; margin: 0; }
        .profile-container { background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 100%; max-width: 500px; }
        h2 { text-align: center; color: #333; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #555; }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn-submit {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        .btn-submit:hover { background-color: #0056b3; }
        .btn-back {
            padding: 10px 20px;
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            margin-right: 10px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-back:hover { background-color: #545b62; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px; padding: 10px; margin-bottom: 15px; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px; padding: 10px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="profile-container">
        <h2>Modifier mon Profil</h2>

        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT') {{-- Indique que c'est une requête PUT pour la mise à jour --}}

            <div class="form-group">
                <label for="name">Nom :</label>
                <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
            </div>

            <div class="form-group">
                <label for="email">Adresse Email :</label>
                <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
            </div>

            <div class="form-group">
                <label for="current_password">Mot de passe actuel (pour confirmer les changements) :</label>
                <input type="password" id="current_password" name="current_password">
                <small>Laissez vide si vous ne souhaitez pas changer de mot de passe.</small>
            </div>

            <div class="form-group">
                <label for="password">Nouveau mot de passe :</label>
                <input type="password" id="password" name="password">
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmer le nouveau mot de passe :</label>
                <input type="password" id="password_confirmation" name="password_confirmation">
            </div>

            <a href="{{ route('accueil') }}" class="btn-back">Retour</a>
            <button type="submit" class="btn-submit">Mettre à jour</button>
        </form>
    </div>
</body>
</html>
