<form action="{{ route('login') }}" method="POST">
    @csrf
    <input type="email" name="email" required>
    <input type="password" name="password" required>
    <button type="submit">Se connecter</button>
</form>