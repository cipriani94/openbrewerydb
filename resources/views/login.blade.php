<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/css/adminlte.min.css">
</head>

<body class="login-page bg-body-secondary">
    <div class="login-box">
        <div class="login-logo">
            <a href=""><b>Openbrewerydb</b></a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in</p>

                <form action="" method="post" id="login-form">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="username" value="root" id="username"
                            required />
                        <div class="input-group-text">
                            <span class="bi bi-envelope"></span>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password" value="password"
                            id="password" required />
                        <div class="input-group-text">
                            <span class="bi bi-lock-fill"></span>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary">Sign In</button>
                    </div>
                    <!--end::Row-->
                </form>


                <!-- /.social-auth-links -->
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>


    <script src="/js/adminlte.min.js"></script>

    <script>
        document.getElementById('login-form').addEventListener('submit', async function(event) {
            event.preventDefault();
            const login = {
                username: document.getElementById('username').value,
                password: document.getElementById('password').value
            }

            const contentJson = JSON.stringify(login);
            const response = await fetch('/api/login', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: contentJson
            });

            if (!response.ok) {
                alert('Login failed');
                return;
            }

            const data = await response.json();
            const token = data.data.token;
            const expiresAt = new Date(data.data.expire);
            document.cookie =
                `auth_token=${token}; expires=${expiresAt.toUTCString()}; path=/;SameSite=Strict`;
            window.location.href = '/breweries/list';
        });
    </script>
</body>

</html>
