<article>
    <h2>Login</h2>
    <form method="post" action="?page=login">
        <?= csrf_field() ?>
        <label>
            Username
            <input type="text" name="username" required autofocus>
        </label>
        <label>
            Password
            <input type="password" name="password" required>
        </label>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="?page=register">Register</a></p>
</article>
