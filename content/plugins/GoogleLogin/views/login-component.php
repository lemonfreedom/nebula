<script src="https://accounts.google.com/gsi/client" async defer></script>
<script src="https://unpkg.com/jwt-decode/build/jwt-decode.js"></script>
<script>
    function handleCredentialResponse(response) {
        console.log(response.credential);
        console.log(jwt_decode(response.credential));
        // fetch('https://www.googleapis.com/plus/v1/people/me?access_token=' + response.credential)
        //     .then(res => res.json())
        //     .then(res => {
        //         console.log(res);
        //     })
    }
    window.onload = function() {
        google.accounts.id.initialize({
            client_id: "762030044736-k8tvmo2sf50pp8nmttpuvnfb793oplud.apps.googleusercontent.com",
            callback: handleCredentialResponse,
            scope: 'profile',
            cookiepolicy: 'addds'
        });
        google.accounts.id.renderButton(
            document.getElementById("buttonDiv"), {
                theme: "outline",
                size: "large"
            } // customization attributes
        );
        google.accounts.id.prompt(); // also display the One Tap dialog
    }

    function addds(a) {
        console.log(a);
    }
</script>
<div style="margin-top: 1rem;width: 100%" id="buttonDiv"></div>
