<template>
    <section id="home" class="md:h-[65vh] lg:h-[85vh] flex items-center bg-light-primary dark:bg-dark-primary pt-32 lg:py-0 overflow-hidden">
        <div class="container mx-auto" v-motion-slide-right>
            <div class="flex flex-col md:flex-row items-center">
                <div class="lg:flex flex-none md:flex-1 items-center mt-8 md:mt-0 md:mr-10 lg:mr-20 max-w-xl">
                    <img src="img/profile 2.jpg" alt="Hero" class="rounded-lg" /><br>
                </div>
                <div class="flex flex-col items-center lg:items-end text-right">
                    <p class="text-xl font-bold text-accent mb-[2px] mt-5 lg:mt-0">Hey I'm Raleigh! </p><blockquote class="text-center md:text-right">(Raw-lee)</blockquote>
                    <h1 class="text-4xl leading-[44px] md:text-5xl md:leading-tight lg:text-7xl text-center lg:text-right lg:leading-[1.2] font-bold md:tracking-[-2px]">
                        I make web apps.
                    </h1>
                    <p class="pt-4 md:pt-6 max-w-[480px] text-lg">Ditch the spreadsheets.</p>
                    <p class="text-lg">Upgrade your workflow.</p>
                    <p class="text-lg">Get an app.</p>
                    <div class="py-12">
                        <h5 class="text-lg flex items-center justify-center lg:justify-end  dark:text-white mb-2">Connect with me!</h5>
                        <div class="flex items-center justify-between gap-2 sm:justify-start mb-2">
                            <a target="_blank" href="mailto:raleighgroesbeck@gmail.com" class="bg-accent p-1 rounded-full transition ease-in-out hover:scale-110 duration-100">
                                <v-icon size="40" class="text-light-primary">mdi-email</v-icon>
                            </a>
                            <a target="_blank" href="https://github.com/DigitalDadKC" class="bg-black p-1 rounded-full transition ease-in-out hover:scale-110 duration-100">
                                <v-icon size="40" class="text-light-secondary">mdi-github</v-icon>
                            </a>
                            <a target="_blank" href="https://www.linkedin.com/in/digitaldadkc" class="p-1 rounded-full transition ease-in-out hover:scale-110 duration-100">
                                <v-icon size="40" class="text-blue-500 dark:text-white">mdi-linkedin</v-icon>
                            </a>
                        </div>
                    </div>
                    <h5 class="text-lg flex items-center justify-center lg:justify-end dark:text-white mb-2">Schedule a call!</h5>
                    <a target="_blank" href="https://calendly.com/digitaldadkc" class="bg-accent  p-2 rounded-full transition ease-in-out hover:scale-110 duration-100">
                        <v-icon size="40" class="text-light-primary">mdi-calendar</v-icon>
                    </a>
                </div>
            </div>
        </div>
    </section>
</template>
