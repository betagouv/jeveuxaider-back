<header>

    <div class="bg-primary pb-32">
        <div class="container mx-auto px-4">
            <div
                class="flex flex-wrap items-center justify-center md:h-16 border-b border-[#070191]"
            >
                <div class="flex-shrink-0 my-4 md:my-0">
                    <a href="/">
                        <img
                            class="h-6"
                            src="@/assets/images/logo-header.png"
                            alt="JeVeuxAider.gouv.fr"
                        />
                    </a>
                </div>

                <div class="mb-3 md:ml-auto md:mb-0">
                    <div class="flex flex-wrap items-center justify-center -m-2">
                        <a
                            href="/missions-benevolat"
                            class="{{ (request()->is('missions')) ? 'bg-blue-700' : '' }} m-2 px-3 py-2 rounded-md text-sm font-medium text-white transition focus:bg-gray-700 hover:bg-blue-700"
                        >
                            Trouver une mission
                        </a>

                        <a
                            href="/user/missions"
                            class="{{ (request()->is('user/missions')) ? 'bg-blue-700' : '' }} m-2 px-3 py-2 rounded-md text-sm font-medium text-white transition hover:text-white hover:bg-blue-700"
                        >
                            Mes missions
                        </a>
                        <a
                            href=""
                            class="{{ (request()->is('regles-de-securite')) ? 'bg-blue-700' : '' }} m-2 px-3 py-2 rounded-md text-sm font-medium text-white transition hover:text-white hover:bg-blue-700"
                        >
                            Règles de sécurité
                        </a>
                    </div>
                </div>
            </div>

            <div class="pt-10">
                <h1 class="text-3xl font-bold text-white">
                    @yield('page_title')
                </h1>
            </div>
        </div>
    </div>

</header>
