<?php
const CLIENT_ID = "client_606c5bfe886e14.91787997";
const CLIENT_SECRET = "2ce690b11c94aca36d9ec493d9121f9dbd5c96a5";


/**
 * AUTH_CODE WORKFLOW
 *  => Get CODE
 *  => EXCHANGE CODE => TOKEN
 *  => GET USER by TOKEN
 */
/**
 * PASSWORD WORKFLOW
 * => GET USERNAME/PASSWORD (form)
 * => EXHANGE U/P => TOKEN
 * => GET USER by TOKEN
 */

function getInfos($token)
{
    // GET USER by TOKEN
    $context = stream_context_create([
        'http' => [
            'method' => "GET",
            'header' => "Authorization: Bearer " . $token
        ]
    ]);
    $result = file_get_contents("http://oauth-server:8081/api", false, $context);
    $user = json_decode($result, true);
    return $user;
}

function handleCodeType($code)
{
    // ECHANGE CODE => TOKEN
    $result = file_get_contents("http://oauth-server:8081/token?"
        . "grant_type=authorization_code"
        . "&client_id=" . CLIENT_ID
        . "&client_secret=" . CLIENT_SECRET
        . "&code=" . $code);
    $token = json_decode($result, true);

    if($token['access_token'])
    {
        return var_dump(getInfos($token['access_token']));
    }else{
        echo 'Une erreur est survenue';
        return;
    }
}

function handlePasswordType($username, $password)
{
    $result = file_get_contents("http://oauth-server:8081/token?"
            . "grant_type=password"
            . "&client_id=" . CLIENT_ID
            . "&client_secret=" . CLIENT_SECRET
            . "&username=" . $username 
            . "&password=" . $password
        );
    $content = json_decode($result, true);
    if(isset($content["access_token"]))
    {
        echo var_dump(getInfos($content["access_token"]));
        exit;
    }else{
        echo 'Une erreur est survenue';
    }
}

$route = strtok($_SERVER['REQUEST_URI'], '?');
switch ($route) {
    case '/auth-code':
        // Gérer le workflow "authorization_code" jusqu'à afficher les données utilisateurs
        echo '<h1>Login with Auth-Code</h1>';
        echo "<a href='http://localhost:8081/auth?"
            . "response_type=code"
            . "&client_id=" . CLIENT_ID
            . "&scope=basic&state=dsdsfsfds'>Login with oauth-server</a>";
        break;
    case '/success':
        // GET CODE
        ["code" => $code, "state" => $state] = $_GET;
        handleCodeType($code, $state);
        break;

    case '/error':
        ["state" => $state] = $_GET;
        echo "Auth request with state {$state} has been declined";
        break;
        
    case '/password':
        if( isset($_POST['username']) && isset($_POST['password']))
        {
            [ 'username' => $username, 'password' => $pwd ] = $_POST;
            handlePasswordType($username, $pwd);
        }
        // Gérer le workflow "password" jusqu'à afficher les données utilisateurs
        echo '<h1>Login with password</h1>';
        echo "
            <form method='POST' action='/password'>
                <input type='text' name='username' placeholder='username'/>
                <input type='password' name='password' placeholder='password'/>
                <input type='submit' value='Login'/>
            <form>
        ";
        // Lien Token
        break;
    default:
        echo 'not_found';
        break;
}
