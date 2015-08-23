<html>
<head>
    <?php
    echo Assets::style(\App\Libraries\Assets\Collection::createByGroup('style'));
    ?>
    <style>
        body {
            background: url('http://farm3.staticflickr.com/2832/12303719364_c25cecdc28_b.jpg') fixed;
            background-size: cover;
            padding: 0;
            margin: 0;
        }

        .wrap {
            width: 100%;
            height: 100%;
            min-height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 99;
        }

        p.form-title {
            font-family: 'Open Sans', sans-serif;
            font-size: 20px;
            font-weight: 600;
            text-align: center;
            color: #FFFFFF;
            margin-top: 5%;
            text-transform: uppercase;
            letter-spacing: 4px;
        }

        form {
            width: 250px;
            margin: 0 auto;
        }

        form.login input[type="text"], form.login input[type="password"] {
            width: 100%;
            margin: 0;
            padding: 5px 10px;
            background: 0;
            border: 0;
            border-bottom: 1px solid #FFFFFF;
            outline: 0;
            font-style: italic;
            font-size: 12px;
            font-weight: 400;
            letter-spacing: 1px;
            margin-bottom: 5px;
            color: #FFFFFF;
            outline: 0;
        }

        form.login input[type="submit"] {
            width: 100%;
            font-size: 14px;
            text-transform: uppercase;
            font-weight: 500;
            margin-top: 16px;
            outline: 0;
            cursor: pointer;
            letter-spacing: 1px;
        }

        form.login input[type="submit"]:hover {
            transition: background-color 0.5s ease;
        }

        form.login label, form.login a {
            font-size: 12px;
            font-weight: 400;
            color: #FFFFFF;
        }

        form.login a {
            transition: color 0.5s ease;
        }

        form.login a:hover {
            color: #2ecc71;
        }

        .pass-reset label {
            font-size: 12px;
            font-weight: 400;
            margin-bottom: 15px;
        }

        .pass-reset input[type="email"] {
            width: 100%;
            margin: 5px 0 0 0;
            padding: 5px 10px;
            background: 0;
            border: 0;
            border-bottom: 1px solid #000000;
            outline: 0;
            font-style: italic;
            font-size: 12px;
            font-weight: 400;
            letter-spacing: 1px;
            margin-bottom: 5px;
            color: #000000;
            outline: 0;
        }

        .pass-reset input[type="submit"] {
            width: 100%;
            border: 0;
            font-size: 14px;
            text-transform: uppercase;
            font-weight: 500;
            margin-top: 10px;
            outline: 0;
            cursor: pointer;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="wrap">
                <p class="form-title">
                    Sign In
                </p>

                    <?php if ($error) {?>
                        <div class="alert alert-danger" role="alert">
                            Nom d'utilisateur ou mot de passe incorrect
                        </div>
                    <?php } ?>

                <form class="login" method="post" action="<?php echo  route('auth.login') ?>">
                    <input type="hidden" name="_token" value="<?php echo csrf_token() ?>">
                    <input type="text" name="username" placeholder="Username"/>
                    <input type="password" name="password" placeholder="Password"/>
                    <input type="submit" value="Sign In" class="btn btn-success btn-sm"/>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.forgot-pass').click(function (event) {
            $(".pr-wrap").toggleClass("show-pass-reset");
        });

        $('.pass-reset-submit').click(function (event) {
            $(".pr-wrap").removeClass("show-pass-reset");
        });
    });
</script>
</body>
</html>
