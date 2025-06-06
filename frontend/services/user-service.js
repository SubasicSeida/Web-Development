var UserService = {

    init: function () {
        var token = localStorage.getItem("user_token");
        if (token && token !== undefined) {
            window.location.replace("index.html");
        }
        $("#login-form").validate({
            rules: {
                "email": {
                    required: true,
                    email: true
                },
                "password": {
                    required: true,
                    minlength: 8
                }
            },
            messages: {
                email: "Enter a valid email",
                password: "Password must be at least 8 characters"
            },
            submitHandler: function (form) {
                var entity = Object.fromEntries(new FormData(form).entries());
                UserService.login(entity);
            },
        });
    },

    login: function (entity) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "auth/login",
            type: "POST",
            data: JSON.stringify(entity),
            contentType: "application/json",
            dataType: "json",
            success: function (result) {
                console.log(result);
                localStorage.setItem("user_token", result.data.token);
                toastr.success("Login Successful!");
                setTimeout(() => {
                    window.location.replace("index.html");
                }, 1500);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                toastr.error(JSON.parse(XMLHttpRequest.responseText)?.error || "An error occurred");
            },
        });
    },


    register: function () {
        $("#register-form").validate({
            rules: {
                "first-name": "required",
                "email": {
                    required: true,
                    email: true
                },
                "phone": {
                    required: true,
                    minlength: 7,
                    maxlength: 15
                },
                "password": {
                    required: true,
                    minlength: 8
                }
            },
            messages: {
                "first-name": "First name is required",
                email: "Enter a valid email",
                phone: "Enter a valid phone number",
                password: "Password must be at least 8 characters"
            },
            submitHandler: function (form) {
                let formData = Object.fromEntries(new FormData(form).entries());

                let entity = {
                    first_name: formData["first-name"],
                    last_name: formData["last-name"],
                    email: formData.email,
                    phone_number: formData.phone,
                    password: formData.password
                };

                $.ajax({
                    url: Constants.PROJECT_BASE_URL + "auth/register",
                    type: "POST",
                    data: JSON.stringify(entity),
                    contentType: "application/json",
                    dataType: "json",
                    success: function (result) {
                        toastr.success("Registration successful! Redirecting to login...");
                        setTimeout(() => {
                            window.location.href = "login.html";
                        }, 1500);
                    },
                    error: function (xhr) {
                        toastr.error(xhr?.responseJSON?.error || "Registration failed.");
                    }
                });
            }
        });
    },


    logout: function () {
        localStorage.clear();
        window.location.replace("login.html");
    },

    generateDashboard: function () {
        const token = localStorage.getItem("user_token");

        let nav = `
            <a href="#home" class="nav-item nav-link">Home</a>
            <a href="#properties" class="nav-item nav-link">Properties</a>
            <a href="#agents" class="nav-item nav-link">Agents</a>
        `;

        let main = `
            <section id="properties"></section>
            <section id="agents"></section>
            <section id="view-listing"></section>
            <section id="home" data-load="home.html"></section>
        `;

        if (token) {
            const user = Utils.parseJwt(token).user;

            console.log("User role:", user.user_role);
            console.log("Expected (customer):", Constants.CUSTOMER_ROLE);

            if (user.user_role === Constants.CUSTOMER_ROLE) {
                nav += `
                <a href="#account" class="nav-item nav-link">My Account</a>
                <button class="btn btn-outline-danger ms-3" onclick="UserService.logout()">Logout</button>
            `;
                main += `
                <section id="account"></section>
            `;
            } else if (user.user_role === Constants.AGENT_ROLE) {
                nav += `
                <a href="#create-listing" class="nav-item nav-link">Create Listing</a>
                <a href="#account" class="nav-item nav-link">My Account</a>
                <button class="btn btn-outline-danger ms-3" onclick="UserService.logout()">Logout</button>
            `;
                main += `
                <section id="create-listing"></section>
                <section id="account"></section>
            `;
            } else if (user.user_role === Constants.ADMIN_ROLE) {
                nav += `
                <a href="#admin-dashboard" class="nav-item nav-link">Admin Dashboard</a>
                <button class="btn btn-outline-danger ms-3" onclick="UserService.logout()">Logout</button>
            `;
                main += `
                <section id="admin-dashboard"></section>
            `;
            }
        } else {
            // guest mode
            nav += `<button class="btn btn-outline-primary py-0" onclick="window.location.href='login.html'">Login</button>`;
        }


        $("#navbarCollapse .navbar-nav").html(nav);
        $("#spapp").append(main);
    }
};
