<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=`, initial-scale=1.0">
    <title>{{ $_ENV['APP_NAME'] }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="text-[#FFFFFF] bg-[#010101]">
@if (! $auth_status)
        <header class="flex justify-between bg-[#121212] w-[100%] h-[80px] px-[24px] fixed top-0">
            <div class="flex items-center">
                <a class="max-[387px]:hidden" href="{{ route('welcome') }}">
                    <img src="../upload/AretmBet.svg" class="w-[134px] h-[24px]" alt="">
                    <p class="text-[13px] font-[600] text-center mt-[3px]">Ставки и спорт</p>
                </a>

                <div class="ml-[16px]">
                    <div class="text-[16px] font-[400] max-[660px]:hidden ">
                        <a class="px-[8px]" href="#">Спорт</a>
                        <a class="px-[8px]" href="#">Live</a>
                        <a class="px-[8px]" href="#">Киберспорт</a>
                        <a class="px-[8px] max-[802px]:hidden" href="#">Быстрые ставки</a>
                        <a class="px-[8px] max-[945px]:hidden" href="#">Акции и бонусы</a>
                    </div>
                </div>
            </div>

            
            <div class="max-[387px]:m-auto flex items-center">

                <a class="text-[#000000] text-center text-[14px] rounded-[6px] bg-[#FF0025] py-[10px] px-[15px] mr-[16px]" href="{{ route('login') }}">Вход</a>
                <a class="text-[#000000] text-center text-[14px] rounded-[6px] bg-[#FFF340] py-[10px] px-[15px] whitespace-nowrap" href="{{ route('register') }}">Регистрация</a>

            </div>

        </header>
    @else
        <header class="flex justify-between bg-[#121212] w-[100%] h-[80px] px-[24px] fixed top-0">
                <div class="flex items-center">
                    <a class="max-[387px]:hidden" href="{{ route('welcome') }}">
                        <img src="../upload/AretmBet.svg" class="w-[134px] h-[24px]" alt="">
                        <p class="text-[13px] font-[600] text-center mt-[3px]">Ставки и спорт</p>
                    </a>

                    <div class="ml-[16px]">
                        <div class="text-[16px] font-[400] max-[805px]:hidden ">
                            <a class="px-[8px]" href="{{ route('pari') }}">Мои пари</a>
                            <a class="px-[8px]" href="{{ route('category') }}">Категории</a>
                            <a class="px-[8px]" href="{{ route('addbalance') }}">Пополнить баланс</a>
                            <a class="px-[8px]" href="{{ route('logout') }}">Выйти</a>
                        </div>
                    </div>
                </div>

                
                <div class="max-[387px]:m-auto flex items-center">

                    <a class="text-[#000000] text-center text-[14px] rounded-[6px] bg-green-500 py-[10px] px-[15px] mr-[16px]" href="{{ route('withdrawal') }}">Вывод</a>
                    <a class="text-[#000000] text-center text-[14px] rounded-[6px] bg-[#FFF340] py-[10px] px-[15px] whitespace-nowrap" href="{{ route('addbalance') }}">{{ Auth::user()->balance }} RUB</a>

                </div>

            </header>
    @endif
    <main>
        <section class="pl-[20px] w-fit mt-[126px]">
            @foreach (json_decode($event->parameters) as $key => $value)
                <p class="text-[24px] font-[700]">{{ $key }}:</p>
                @foreach ($value as $key_2 => $value_2)
                    @foreach ($value_2 as $key_3 => $value_3)
                        @if ($value_3->status === 'waited')
                            @if (! str_contains($key_3, '_hide'))
                                <form method="POST">
                                    @csrf
                                    <input type="hidden" name="parameters" value='{"id": {{ $event->id }},"name": "{{ $key }}", "team": "{{ $key_2 }}", "result": "{{ $key_3 }}"}' autocomplete="off">
                                    <p class="mb-[5px]">{{ $key_2 }} | <span class="text-blue-500">{{ $key_3 }}</span> | Коэффициент: <span class="text-green-500"> {{ $value_3->coefficient }}</span> | <input class="shadow appearance-none border rounded w-[130px] text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="number" placeholder="Сумма ставки" name="sum"> | <input class=" bg-blue-500 hover:bg-blue-700 text-white font-bold rounded focus:outline-none focus:shadow-outline" type="submit" value="Поставить"></p>
                                </form>
                            @else
                                <form method="POST">
                                    @csrf
                                    <input type="hidden" name="parameters" value='{"id": {{ $event->id }},"name": "{{ $key }}", "team": "{{ $key_2 }}", "result": "{{ $key_3 }}"}' autocomplete="off">
                                    <p class="mb-[5px]">{{ $key_2 }} | Коэффициент: <span class="text-green-500"> {{ $value_3->coefficient }}</span> | <input class="shadow appearance-none border rounded w-[130px] text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="number" placeholder="Сумма ставки" name="sum"> | <input class=" bg-blue-500 hover:bg-blue-700 text-white font-bold rounded focus:outline-none focus:shadow-outline" type="submit" value="Поставить"></p>
                                </form>
                            @endif
                        @else
                            @if (! str_contains($key_3, '_hide'))
                                    <form method="POST">
                                        @csrf
                                        <p class="mb-[5px]">{{ $key_2 }} | <span class="text-blue-500">{{ $key_3 }}</span> | Коэффициент: <span class="text-green-500"> {{ $value_3->coefficient }}</span> | Закрыто для ставок </p>
                                    </form>
                                @else
                                    <form method="POST">
                                        @csrf
                                        <p class="mb-[5px]">{{ $key_2 }} | Коэффициент: <span class="text-green-500"> {{ $value_3->coefficient }}</span> | Закрыто для ставок </p>
                                    </form>
                                @endif
                        @endif
                    @endforeach
                @endforeach
                <br>
            @endforeach
        </section>
    </main>

    @if (! $auth_status)
        <header class="min-[661px]:hidden flex bg-[#121212] w-[100%] h-[80px] fixed bottom-0 justify-between items-center px-[24px]">
            <div class="flex items-center">
                <a href="#">
                    <svg class="ml-[12px]" width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12.91 3.474a.667.667 0 0 0-.82 0l-7.5 5.833a.667.667 0 0 0-.257.526V19a2.333 2.333 0 0 0 2.334 2.333h11.666A2.333 2.333 0 0 0 20.667 19V9.833c0-.205-.095-.4-.258-.526l-7.5-5.833ZM15.666 20h2.666a1 1 0 0 0 1-1v-8.84L12.5 4.844l-6.833 5.314V19a1 1 0 0 0 1 1h2.666v-7.667c0-.368.299-.666.667-.666h5c.368 0 .667.298.667.666V20Zm-5 0h3.666v-7h-3.666v7Z" fill="#9D9D9D"></path></svg>
                    <p class="text-[13px] font-[600] text-center mt-[3px]">Главная</p>
                </a>
            </div>

            <div class="flex items-center">
                <a href="#">
                    <svg class="ml-[7px]" width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.614 5.636c-3.509 3.509-3.509 9.219 0 12.728 3.509 3.509 9.219 3.509 12.728 0 3.509-3.509 3.509-9.219 0-12.728-3.509-3.509-9.22-3.509-12.728 0Zm11.038.038c-1.68.832-3.949 2.525-5.093 5.824-1.396.267-2.633.22-3.716-.018 1.915-4.592 5.78-6.099 7.574-6.556.43.208.842.46 1.235.75ZM13.66 4.316c-2.533-.55-5.286.15-7.25 2.116A7.818 7.818 0 0 0 4.394 9.9c.637.474 1.424.93 2.361 1.27 1.657-4.092 4.715-5.993 6.904-6.854Zm-9.5 6.803c1.72 1.152 4.268 2.116 7.583 1.486.992 1.143 1.575 2.309 1.897 3.403-4.212.45-7.383-1.007-9.369-2.419a7.926 7.926 0 0 1-.11-2.47Zm2.25 6.45c2.037 2.038 4.923 2.715 7.532 2.046a9.02 9.02 0 0 0-.047-2.489c-3.841.45-6.893-.585-9.061-1.835.378.824.898 1.6 1.575 2.277Zm10.607.478c-.587.49-1.23.879-1.907 1.17.133-1.86-.198-4.655-2.493-7.316.424-1.237 1.01-2.233 1.684-3.022 2.575 3.322 2.933 6.754 2.716 9.168Zm-1.92-9.98c2.434 3.076 3.095 6.248 3.094 8.762 2.388-3.07 2.191-7.507-.598-10.346a9.058 9.058 0 0 0-2.497 1.584Z" fill="#737373"></path></svg>                
                    <p class="text-[13px] font-[600] text-center mt-[3px]">Спорт</p>
                </a>
            </div>

            <div class="flex items-center">
                <a href="#">
                    <svg class="ml-[25px]" width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M18.422 6A5.585 5.585 0 0 1 24 11.579a5.578 5.578 0 0 1-9.98 3.426H9.98a5.578 5.578 0 0 1-4.401 2.152A5.585 5.585 0 0 1 0 11.58 5.585 5.585 0 0 1 5.579 6h12.843ZM5.578 7.299c-2.36 0-4.28 1.92-4.28 4.28 0 2.36 1.92 4.28 4.28 4.28a4.281 4.281 0 0 0 3.537-1.87.65.65 0 0 1 .536-.283h4.697c.215 0 .416.106.537.283a4.282 4.282 0 0 0 3.536 1.87c2.36 0 4.28-1.92 4.28-4.28 0-2.36-1.92-4.28-4.28-4.28H5.578Zm10.255 4.282a.752.752 0 1 1 .752.751.752.752 0 0 1-.752-.75Zm4.423-.76a.751.751 0 0 0 .003 1.504h.002a.752.752 0 0 0-.005-1.504Zm-1.835 1.843h.005a.751.751 0 1 1 .006 1.503h-.011a.752.752 0 0 1 0-1.503Zm-.003-2.17h.001a.752.752 0 1 0-.001 0Zm-12.22-.402v.866h.867a.62.62 0 1 1 0 1.24H6.2v.867a.62.62 0 1 1-1.24 0V12.2h-.867a.62.62 0 1 1 0-1.24h.866v-.867a.62.62 0 1 1 1.24 0Z" fill="#737373"></path></svg>                
                    <p class="text-[13px] font-[600] text-center mt-[3px]">Киберспорт</p>
                </a>
            </div>
        </header>
    @else

        <header class="min-[806px]:hidden flex bg-[#121212] w-[100%] h-[80px] fixed bottom-0 justify-between items-center px-[24px]">
                <div class="flex items-center">
                    <a href="{{ route('pari') }}">
                        <svg class="ml-[17px]" width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18.663 5.337A7.929 7.929 0 0 0 13.019 3a7.93 7.93 0 0 0-5.643 2.337C6.359 6.354 5.726 7.52 5.492 8.802c-.177.971.017 1.926.204 2.849.293 1.446.546 2.694-.591 3.831l-1.4 1.4c-.94.94-.94 2.47 0 3.411A2.397 2.397 0 0 0 5.413 21c.644 0 1.25-.251 1.706-.707l1.399-1.399c.58-.58 1.208-.84 2.033-.84.572 0 1.168.121 1.798.25.663.134 1.349.273 2.044.273.286 0 .549-.023.806-.07 1.282-.233 2.448-.867 3.464-1.884A7.928 7.928 0 0 0 21 10.98c0-2.131-.83-4.135-2.337-5.643ZM10.55 16.941c-1.113 0-2.036.382-2.82 1.166l-1.4 1.4a1.29 1.29 0 0 1-.918.38 1.3 1.3 0 0 1-.919-2.218l1.399-1.4c1.56-1.56 1.207-3.302.895-4.84a15.253 15.253 0 0 1-.211-1.204l7.199 7.198c-.39-.046-.79-.127-1.205-.21-.657-.134-1.337-.272-2.02-.272Zm7.325-1.105c-.777.778-1.65 1.286-2.597 1.516l-8.63-8.63c.23-.947.738-1.82 1.515-2.597a6.823 6.823 0 0 1 4.856-2.012c1.835 0 3.56.715 4.856 2.012a6.874 6.874 0 0 1 0 9.711Z" fill="#737373"></path></svg>
                        <p class="whitespace-nowrap	text-[13px] font-[600] text-center mt-[3px]">Мои пари</p>
                    </a>
                </div>

                <div class="flex items-center">
                    <a href="{{ route('category') }}">
                        <svg class="ml-[20px]" width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.614 5.636c-3.509 3.509-3.509 9.219 0 12.728 3.509 3.509 9.219 3.509 12.728 0 3.509-3.509 3.509-9.219 0-12.728-3.509-3.509-9.22-3.509-12.728 0Zm11.038.038c-1.68.832-3.949 2.525-5.093 5.824-1.396.267-2.633.22-3.716-.018 1.915-4.592 5.78-6.099 7.574-6.556.43.208.842.46 1.235.75ZM13.66 4.316c-2.533-.55-5.286.15-7.25 2.116A7.818 7.818 0 0 0 4.394 9.9c.637.474 1.424.93 2.361 1.27 1.657-4.092 4.715-5.993 6.904-6.854Zm-9.5 6.803c1.72 1.152 4.268 2.116 7.583 1.486.992 1.143 1.575 2.309 1.897 3.403-4.212.45-7.383-1.007-9.369-2.419a7.926 7.926 0 0 1-.11-2.47Zm2.25 6.45c2.037 2.038 4.923 2.715 7.532 2.046a9.02 9.02 0 0 0-.047-2.489c-3.841.45-6.893-.585-9.061-1.835.378.824.898 1.6 1.575 2.277Zm10.607.478c-.587.49-1.23.879-1.907 1.17.133-1.86-.198-4.655-2.493-7.316.424-1.237 1.01-2.233 1.684-3.022 2.575 3.322 2.933 6.754 2.716 9.168Zm-1.92-9.98c2.434 3.076 3.095 6.248 3.094 8.762 2.388-3.07 2.191-7.507-.598-10.346a9.058 9.058 0 0 0-2.497 1.584Z" fill="#737373"></path></svg>                
                        <p class="whitespace-nowrap	text-[13px] font-[600] text-center mt-[3px]">Категории</p>
                    </a>
                </div>

                <div class="flex items-center">
                    <a href="{{ route('addbalance') }}">
                        <svg class="ml-[45px]" width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M18.422 6A5.585 5.585 0 0 1 24 11.579a5.578 5.578 0 0 1-9.98 3.426H9.98a5.578 5.578 0 0 1-4.401 2.152A5.585 5.585 0 0 1 0 11.58 5.585 5.585 0 0 1 5.579 6h12.843ZM5.578 7.299c-2.36 0-4.28 1.92-4.28 4.28 0 2.36 1.92 4.28 4.28 4.28a4.281 4.281 0 0 0 3.537-1.87.65.65 0 0 1 .536-.283h4.697c.215 0 .416.106.537.283a4.282 4.282 0 0 0 3.536 1.87c2.36 0 4.28-1.92 4.28-4.28 0-2.36-1.92-4.28-4.28-4.28H5.578Zm10.255 4.282a.752.752 0 1 1 .752.751.752.752 0 0 1-.752-.75Zm4.423-.76a.751.751 0 0 0 .003 1.504h.002a.752.752 0 0 0-.005-1.504Zm-1.835 1.843h.005a.751.751 0 1 1 .006 1.503h-.011a.752.752 0 0 1 0-1.503Zm-.003-2.17h.001a.752.752 0 1 0-.001 0Zm-12.22-.402v.866h.867a.62.62 0 1 1 0 1.24H6.2v.867a.62.62 0 1 1-1.24 0V12.2h-.867a.62.62 0 1 1 0-1.24h.866v-.867a.62.62 0 1 1 1.24 0Z" fill="#737373"></path></svg>                
                        <p class="whitespace-nowrap	text-[13px] font-[600] text-center mt-[3px]">Пополнить баланс</p>
                    </a>
                </div>

                <div class="flex items-center">
                    <a href="{{ route('logout') }}">
                    <svg class="ml-[9px]" width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 17.25H6.75V6.75H12v-1.5H6.75c-.825 0-1.5.675-1.5 1.5v10.5c0 .825.675 1.5 1.5 1.5H12v-1.5Z" fill="#737373"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M16.065 11.438 14.13 9.495l1.057-1.057 3.75 3.75-3.75 3.75-1.057-1.058 1.935-1.943H8.812v-1.5h7.253Z" fill="#737373"></path></svg>
                    <p class="whitespace-nowrap	text-[13px] font-[600] text-center mt-[3px]">Выйти</p>
                    </a>
                </div>
            </header>

    @endif
</body>
</html>