<article>
    <h2>Register</h2>
    <form method="post" action="?page=register">
        <?= csrf_field() ?>
        <label>
            Username
            <input type="text" name="username" required minlength="3" autofocus>
        </label>
        <label>
            Display Name
            <input type="text" name="display_name" placeholder="Optional">
        </label>
        <label>
            Email
            <input type="email" name="email" required>
        </label>
        <label>
            Password
            <input type="password" name="password" required minlength="6">
        </label>
        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="?page=login">Login</a></p>
</article>
