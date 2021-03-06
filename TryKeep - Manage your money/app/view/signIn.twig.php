{% extends 'template/main.twig.php' %}

{% block head %}
    <link rel="stylesheet" href="../public/fontawesome/css/all.css">
    <link rel="stylesheet" href="../public/css/form.css">
    <link rel="stylesheet" href="../public/css/signIn.css">
    <title>TryKeep | Sign In</title>
{% endblock %}

{% block body %}

        <!-- Container -->

        <section id="container">
        <div id="platform">
            <div id="icon">
                <img src="../public/svg/iconSignIn.svg" alt="Contact Icon">
            </div>
            <div id="form">
                <div id="helper">
                    <h3>Hi :)</h3>
                    <form id="signInForm">
                        <div id="form-container">
                            <input id="emailInput" type="text" name="email" placeholder="Email">
                            <input type="password" name="password" placeholder="Password">
                            <button type="submit">Sign In</button>
                        </div>
                    </form>
                    <p>New here?
                        <span>
                            <a href="signUp">Sign up</a>
                        </span>
                    </p>
                    <p id="forgotPassword"><a>I forgot my password</a></p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Scripts -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../public/js/main.js"></script>
    <script type="module" src="../public/js/signIn.js"></script>

{% endblock %}