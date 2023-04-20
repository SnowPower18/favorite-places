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
    <script type="module" src="./js/index.js" defer></script>

    <script src="https://cdn.tailwindcss.com"></script>
    
    <title>MieiLuighi.com</title>
</head>
<body class="w-screen h-screen bg-slate-300 flex flex-col">
    <?php if (isset($_SESSION["authenticated"])):?>
        <!-- check div to know if user is authenticated -->
        <div id="authenticated" class="hidden"></div>
    <?php endif;?>
    <nav>
        <div class="h-14 bg-red-500 flex justify-between items-center px-6">
            <h1 class="font-bold text-white text-2xl">MieiLuoghi.com</h1>
            <?php if (isset($_SESSION["authenticated"])):?>
                <div class="space-x-2">
                    <span class="text-white font-semibold">Logged in as <?= $_SESSION["username"]?></span>
                    <a href="./logout.php" class="px-2 py-1 text-lg text-white font-semibold border-2 rounded-lg focus:outline-0 focus:ring-2 focus:ring-white/50 hover:ring-2 hover:ring-white/50">Log out</a>
                </div>
            <?php else:?>
                <a href="./signin" class="px-2 py-1 text-lg text-white font-semibold border-2 rounded-lg focus:outline-0 focus:ring-2 focus:ring-white hover:ring-2 hover:ring-white">Login</a>
            <?php endif;?>
        </div>
        <div class="h-1 bg-red-700"></div>
    </nav>
    <div class="grow grid grid-cols-4 lg:grid-cols-5">
        <div id="map" class="col-span-3 lg:col-span-4"></div>
        <aside class="bg-red-500 flex flex-col">
            <?php if (isset($_SESSION["authenticated"])):?>
                
                <section class="bg-red-700 pt-3 px-4 pb-5 rounded-b-lg">
                    <h2 class="text-white text-xl font-bold mb-2">Add location</h2>
                    <div class="flex flex-col">
                        <label for="location_name_input" class="text-white text-md font-semibold">Name</label>
                        <input type="text" name="location_name" id="location_name_input" class="text-md font-semibold rounded-sm mt-1 pl-1 border border-transparent focus:ring focus:ring-red-500 focus:outline-0">
                        <div class="flex mt-1 items-center">
                            <label for="location_name_input" class="text-white text-md font-semibold">Public</label>
                            <input type="checkbox" name="location_publicity" id="location_publicity_input" class="mx-auto">
                        </div>
                        <button id="add_location_button" class="mt-4 p-2 text-white text-md font-semibold bg-red-500  disabled:bg-red-400 rounded-md disabled:cursor-no-drop" disabled>Add location</button>
                    </div>
                </section>

            <!-- accordion-like menu -->

                <!-- in order to collapse one menu, remove grow, shrink, basis-auto, h-0 class and add to locations_list hidden class -->
                <!-- private locations -->
                <section id="private_locations_section" class="flex flex-col space-y-2 p-2 pr-0 grow shrink basis-auto h-0">
                    <div class="flex justify-between items-center mr-2">
                        <h2 class="text-white text-xl font-bold">Saved Locations</h2>
                        <button id="toggle_private_locations" class="transition-transform duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                    </div>

                    <div id="private_locations_list" class="flex flex-col space-y-2 pr-2 overflow-y-auto">
                        
                    </div>
                </section>

                <!-- public locations -->
                <section id="public_locations_section" class="flex flex-col space-y-2 p-2  pr-0 grow shrink basis-auto h-0">
                    <div class="flex justify-between items-center mr-2">
                        <h2 class="text-white text-xl font-bold">Public Locations</h2>
                        <button id="toggle_public_locations" class="transition-transform duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                    </div>
                    <div id="public_locations_list" class="flex flex-col space-y-2 pr-2 overflow-y-auto">
                        
                    </div>
                </section>

            <?php else:?>
                
            <section id="public_locations_section" class="flex flex-col space-y-2 p-2  pr-0 grow shrink basis-auto h-0">
                    <div class="flex justify-between items-center mr-2">
                        <h2 class="text-white text-xl font-bold">Public Locations</h2>
                        <button id="toggle_public_locations" class="transition-transform duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                    </div>
                    <div id="public_locations_list" class="flex flex-col space-y-2 pr-2 overflow-y-auto">
                        
                    </div>
                </section>
            
            <?php endif;?>
        </aside>
    </div>
    
    <!-- template elements for javascript use -->

    <!-- public location entry -->
    <template id="public_location_entry_template">
        <div id= class="h8 bg-white flex justify-between p-3 rounded-md">
            <div class="flex space-x-1 items-center">
                <span id="template_public_location_name" class="text-md font-semibold"></span>
            </div>
        </div>
    </template>

    <!-- private location entry -->
    <template id="private_location_entry_template">
        <div class="h8 bg-white flex justify-between p-3 rounded-md">
            <div class="flex space-x-1 items-center">
                <button id="template_toggle_publicity_button" class="aspect-square rounded-sm grid place-content-center">
                </button>
                <span id="template_private_location_name" class="text-md font-semibold"></span>
            </div>
            <div class="flex space-x-2">
                <button id="template_edit_button" class="aspect-square h-6 bg-red-500/25 rounded-sm grid place-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                </button>
                <button id="template_delete_button" class="delete_button aspect-square h-6 bg-red-500/25 rounded-sm grid place-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </template>

    <!-- show icon -->
    <template id="show_icon_template">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
    </template>
    
    <!-- show icon -->
    <template id="hide_icon_template">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
        </svg>
    </template>
</body>
</html>
