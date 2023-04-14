<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="./js/login.js" defer></script>

    <title>SignUp | MieiLuighi.com</title>
</head>
<body class="h-screen w-screen grid place-items-center bg-red-500 p-4">
    <form id="form" class="flex flex-col items-start rounded-lg bg-white p-8 w-full md:w-1/2 xl:w-1/3">
        <h1 class="text-4xl font-semibold mb-8 self-center">SignUp</h1>
        <label for="username_input" class="block font-medium">Username</label>
        
        <input type="text" id="username_input" class="block p-1 mb-2 focus:outline-0 focus:ring-2 focus:ring-red-500/75 focus:border-transparent rounded border border-black ">
        
        <label for="password_input" class="block font-medium">Password</label>
        <input type="password" id="password_input" class="block p-1 mb-2 focus:outline-0 focus:ring-2 focus:ring-red-500/75 focus:border-transparent rounded border border-black ">

        <label for="confirm_password_input" class="block font-medium">Confirm password</label>
        <input type="password" id="confirm_password_input" class="block p-1 focus:outline-0 focus:ring-2 focus:ring-red-500/75 focus:border-transparent rounded border border-black ">
        
        <input type="submit" value="SignUp" class="bg-red-500 hover:bg-red-600 focus:bg-red-600 focus:outline-0 rounded-md self-stretch mt-6 p-2 text-white font-semibold">
        <div class="self-center mt-2"><span class="text-sm mr-2 font-medium">Already have an account?</span><a href="../signin" class="text-sm text-red-500 font-semibold">Login</a></div>
    </form>
</body>
</html>