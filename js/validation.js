const validation = new JustValidate("#signup");

validation
    .addField("#login", [
        {
            rule: "required"
        },
        {
            validator: (value) => () => {
                return fetch("php/validate-login.php?login=" +
                encodeURIComponent(value))
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(json) {
                        return json.available;
                    });
            },
            errorMessage: "Login already taken"
        }
    ])
    .addField("#password", [
        {
            rule: "required"
        },
        {
            rule: "password"
        }
    ])
    .addField("#repeat-password", [
        {
            validator: (value, fields) => {
                return value === fields["#password"].elem.value;
            },
            errorMessage: "Passwords must match"
        }
    ])
    .onSuccess((event) => {
        document.getElementById("signup").submit();
    });