<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/dasboard.css">
    <title>Document</title>
    <script>
        // Tampilkan alert jika ada error
        window.onload = function() {
            <?php if ($error_message): ?>
                alert("<?php echo $error_message; ?>");
            <?php endif; ?>
        };
    </script>
</head>
<body>
    <div class="form-structor">
        <div class="signup">
            <h2 class="form-title" id="signup"><span>or</span>Sign up</h2>
            <div class="form-holder">
                <form action="register_user.php" method="post">
                    <input type="text" class="input" name="name" placeholder="Name" required />
                    <input type="email" class="input" name="email" placeholder="Email" required />
                    <input type="password" class="input" name="password" placeholder="Password" required />
                    <button type="submit" class="submit-btn">Sign up</button>
                </form>
            </div>
        </div>
        <div class="login slide-up">
            <div class="center">
                <h2 class="form-title" id="login"><span>or</span>Log in</h2>
                <div class="form-holder">
                    <form action="login_user.php" method="post">
                        <input type="email" class="input" name="email" placeholder="Email" required />
                        <input type="password" class="input" name="password" placeholder="Password" required />
                        <button type="submit" class="submit-btn">Log in</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="js/dasboard.js"></script>
</body>
</html>
