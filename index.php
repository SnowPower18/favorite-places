<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="./secrets/secrets.js"></script>
    <script>
        (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
            key: MAPS_API_KEY
        });
    </script>
    <script src="./js/index.js" defer></script>

    <script src="https://cdn.tailwindcss.com"></script>
    
    <title>MieiLuighi.com</title>
</head>
<body class="w-screen h-screen bg-slate-300 flex flex-col">
    <nav>
        <div class="h-14 bg-red-500 flex justify-between items-center px-6">
            <h1 class="font-bold text-white text-2xl">MieiLuoghi.com</h1>
            <?php if (!isset($_SESSION["authenticated"])):?>
                    <a href="./signin" class="px-2 py-1 text-lg text-white font-semibold border-2 rounded-lg focus:outline-0 focus:border-white">Login</a>
                <?php else:?>
                    <div class="space-x-2">
                        <span class="text-white font-medium">Logged in as <?= $_SESSION["username"]?></span>
                        <a href="./logout.php" class="px-2 py-1 text-lg text-white font-semibold border-2 rounded-lg focus:outline-0 focus:border-white">Log out</a>
                    </div>
            <?php endif;?>
        </div>
        <div class="h-1 bg-red-700"></div>
    </nav>
    <div class="grow grid grid-cols-4">
    <div id="map" class="col-span-3"></div>
    <div class="bg-green-500/50 flex flex-col">
        <div class="h8 bg-blue-300/50 flex justify-between p-4">
            <span class="text-md font-bold">Nome</span>
            <button class="aspect-square h-6 bg-red-500/25">x</button>
        </div>
    </div>
    </div>
</body>
</html>